<?php
/**
 * @category Popov
 * @package Popov_Simpler
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @datetime: 17.05.15 18:12
 */
namespace Popov\Simpler\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Popov\Simpler\SimplerHelper as Simpler;


class SimplerPlugin extends AbstractPlugin
{
    /**
     * @var SimplerHelper
     */
    protected $simpler;

    /**
     * @param SimplerHelper $numberPlugin
     * @return $this
     */
    public function setSimpler(SimplerHelper $numberPlugin)
    {
        $this->simpler = $numberPlugin;

        return $this;
    }

    /**
     * @return SimplerHelper
     */
    public function getSimpler()
    {
        if (null === $this->simpler) {
            $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();
            $this->simpler = $sm->get(SimplerHelper::class);
        }

        return $this->simpler;
    }

    public function __invoke()
    {
        $params = func_get_args();

        return call_user_func_array($this->getSimpler(), $params);
    }
}
