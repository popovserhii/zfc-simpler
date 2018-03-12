<?php
namespace Popov\Simpler\Helper;

use Zend\View\Helper\AbstractHelper;
use Popov\Simpler\SimplerHelper as Simpler;

/**
 * Using Simpler plugin
 * All references point out to Popov\Simpler\Plugin\Simpler
 *
 * @author Sergiy Popov
 */
class SimplerHelper extends AbstractHelper
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