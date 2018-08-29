<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2018 Serhii Popov
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Popov
 * @package Popov_Simpler
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\Simpler;

use Closure;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Stdlib\Exception\InvalidArgumentException;

class SimplerHelper
{
    const DEFAULT_FIELD = 'id';

    /** @var array */
    protected $context;

    protected $contextType;

    /** @var array */
    protected $handlers = [];

    protected $contextIdentifierField;

    protected $result;

    /**
     * @see JsonModel example for options
     * @var array
     */
    public $options = [
        // only array option
        'preSaveId' => false,
    ];

    /**
     * Every $filed property of domain object implode in string demarcated by comma
     *
     * @usage $this->simpler($collection)->asString() // "1,2,5,6,7"
     *      $this->simpler($collection)->asString('name') // "Kyiv,London,Paris,Berlin"
     *
     * @param string $field
     * @return string
     */
    public function asString($field = self::DEFAULT_FIELD)
    {
        $method = 'get' . ucfirst($field);
        $string = '';
        foreach ($this->context as $item) {
            $string .= ',' . $item->{$method}();
        }

        return ltrim($string, ',');
    }

    /**
     * @todo Необхідність конвертувати рекурсивно колекцію у стрічку виникла поки лише з повідомленями форми після валідації
     *  {@var \Magere\Status\Controller\StatusController::changeAction()}. Якщо така необхідність виникне знову,
     *  тоді потрбно реалізувати повноціний прохід по колекції і генерувати стрічку/масив для результату.
     *  Також потрібно буде реалізувати тест для різних випадків: колекція об'єктів, повідомлення форми, звичайний масив і т.д.
     *
     * @param string $field
     * @param bool|false $recursive
     */
    public function asStr($field = self::DEFAULT_FIELD, $recursive = false)
    {
        static $message = '';

        foreach ($this->context as $key => $row) {
            if (is_array($row)) {
                foreach ($row as $keyer => $rower) {
                    foreach ($rower as $k => $r) {
                        $message .= $r . "\n";
                    }
                }
            } elseif (is_object($row)) {
                if ($this->isIterable($row)) {
                } else {
                    $method = 'get' . ucfirst($field);
                    if (method_exists($row, $method)) {
                    }
                }
            }
        }
    }

    /**
     * Every $filed property of domain object add in array result
     *
     * @usage $this->simpler($collection)->asString() // [1, 2, 5, 6, 7]
     *      $this->simpler($collection)->asString('name') // ['Kyiv', 'London', 'Paris', 'Berlin']
     *
     * @param string $field
     * @return array
     */
    public function asArray($field = self::DEFAULT_FIELD, $options = [])
    {
        !$options || $this->prepareOptions($options);
        $preSaveId = $this->getOption('preSaveId');

        $array = [];
        switch ($this->contextType) {
            case 'object':
                $method = 'get' . ucfirst($field);
                foreach ($this->context as $item) {
                    $value = $this->handle($item->{$method}());
                    if ($preSaveId) {
                        $array[$item->getId()] = $value;
                    } else {
                        $array[] = $value;
                    }
                }
                break;
            case 'array':
                foreach ($this->context as $item) {
                    if (isset($item[$this->contextIdentifierField])) {
                        $array[$item[$this->contextIdentifierField]] = $item[$field];
                    } else {
                        $array[] = $item[$field];
                    }
                }
                break;
        }
        return $array;
    }

    public function asMultiFieldArray(array $fields = [self::DEFAULT_FIELD])
    {
        $array = [];
        switch ($this->contextType) {
            case 'object':
                foreach ($this->context as $item) {
                    $itemArray = [];
                    foreach ($fields as $field) {
                        $method = 'get' . ucfirst($field);
                        $itemArray[$field] = $this->handle($item->{$method}());
                    }
                    $array[$item->getId()] = $itemArray;
                }
                break;
            case 'array':
                foreach ($this->context as $item) {
                    $itemArray = [];
                    foreach ($fields as $field) {
                        $itemArray[$field] = $item[$field];
                    }
                    $array[$item[$this->contextIdentifierField]] = $itemArray;
                }
                break;
        }

        return $array;
    }

    /**
     * Reset native key numeration by item field value
     *
     * Old name toArrayKeyField
     *
     * @param string $field
     * @param bool $addKeyInt
     * @return array
     */
    public function asAssociate($field, $addKeyInt = false)
    {
        $result = [];
        $method = 'get' . ucfirst($field);
        foreach ($this->context as $item) {
            if (!is_object($item)) {
                $key = (isset($item[$field])) ? $item[$field] : $item[0]->{$method}();
            } else {
                $key = $item->{$method}();
            }

            if ($addKeyInt) {
                $result[$key][] = $item;
            } else {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /**
     * @param string $field
     * @param object[] $items
     * @param bool $addKeyInt
     * @return array
     * @deprecated
     */
    public function toArrayKeyField($field, $items, $addKeyInt = false) {
        die(__METHOD__);
        $result = [];
        $method = 'get' . ucfirst($field);
        foreach ($items as $item) {
            if (!is_object($item)) {
                $key = (isset($item[$field])) ? $item[$field] : $item[0]->$method();
            } else {
                $key = $item->$method();
            }
            if ($addKeyInt) {
                $result[$key][] = $item;
            } else {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /**
     * @param $field
     * @param array $items
     * @param string $template
     * @return array
     * @deprecated
     */
    public function getValuesArray($field, array $items, string $template = null)
    {
        $itemsArray = [];

        foreach ($items as $item)
        {
            $val = $template ? sprintf($template, $item[$field]) : $item[$field];
            $itemsArray[] = $val;
        }

        return $itemsArray;
    }

    /**
     * Old name toArrayKeyVal
     *
     * @param string $valField
     * @param string $keyField
     * @return array
     */
    public function asArrayValue($valField, string $keyField = null)
    {
        $result = [];
        $method = 'get' . ucfirst($valField);
        $methodKey = $keyField ? 'get' . ucfirst($keyField) : '';

        foreach ($this->getContext() as $item) {
            if (is_object($item)) {
                $val = $this->handle($item->$method());
                if ($keyField) {
                    $key = $item->$methodKey();
                }
            } else {
                //$val = isset($item[$valField]) ? $item[$valField] : $item[0]->$method();
                $val = (array_key_exists($valField, $item))
                    ? $this->handle($item[$valField])
                    : $this->handle($item[0]->$method());
                if ($keyField) {
                    $key = isset($item[$keyField]) ? $item[$keyField] : $item[0]->$methodKey();
                }
            }
            if ($keyField) {
                $result[$key] = $val;
            } else {
                $result[] = $val;
            }
        }

        return $result;
    }


    protected function handle($value)
    {
        foreach ($this->getHandlers() as $handler) {
            $value = $handler($value);
        }

        return $value;
    }

    protected function reset()
    {
        $this->handlers = [];
    }

    /**
     * Add Closure handler
     *
     * Usage:
     *  $simpler->setContext($array)->addHandler(function($value) { return sprintf('%00', $value); })->asArray('id');
     *
     * @param Closure $closure
     * @return $this
     */
    public function addHandler(Closure $closure)
    {
        $this->handlers[] = $closure;

        return $this;
    }

    public function getHandlers()
    {
        return $this->handlers;
    }

    public function setOption($key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    public function getOption($key)
    {
        return isset($this->options[$key]) ? $this->options[$key] : false;
    }

    public function prepareOptions($options)
    {
        static $defaultOptions = [];

        if (!$defaultOptions) {
            $defaultOptions = $this->options;
        }

        if ($options) {
            $this->options = array_merge($defaultOptions, $options);
        }

        return $this;
    }

    public function setContext($context, $identifier = 'id')
    {
        $this->reset();
        if ($context && $this->isIterable($context)) {
            // ResultInterface - це lifehack для роботи з результатами Zend\Db\Table
            if (is_array($context) && is_array(next($context)) || $context instanceof ResultInterface) {
                $this->contextType = 'array';
            } elseif ($this->isIterable($context)) {
                $this->contextType = 'object';
            } else {
                throw new InvalidArgumentException('Invalid context ' . gettype($context[0]));
            }
        }
        $this->context = $context;
        $this->contextIdentifierField = $identifier;

        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function isIterable($var)
    {
        return (is_array($var) || $var instanceof \Traversable);
    }

    public function __invoke()
    {
        if ($args = func_get_args()) {
            $this->setContext($args[0]);
        }

        return $this;
    }
}