<?php
namespace Agere\Simpler\Helper;

use Zend\View\Helper\AbstractHelper;
use Agere\Simpler\Plugin\SimplerPlugin as SimplerPlugin;

/**
 * Using Simpler plugin
 * All references point out Agere\Simpler\Plugin\Simpler
 *
 * @author Sergiy Popov
 */
class SimplerHelper extends AbstractHelper
{
    /**
     * @var SimplerPlugin
     */
    protected $simplerPlugin;

    /**
     * @param SimplerPlugin $numberPlugin
     * @return $this
     */
    public function setSimplerPlugin(SimplerPlugin $numberPlugin)
    {
        $this->simplerPlugin = $numberPlugin;

        return $this;
    }

    /**
     * @return SimplerPlugin
     */
    public function getSimplerPlugin()
    {
        if (null === $this->simplerPlugin) {
            $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();
            $this->simplerPlugin = $sm->get('ControllerPluginManager')->get('simpler');
        }

        return $this->simplerPlugin;
    }

    public function __invoke()
    {
        $params = func_get_args();

        return call_user_func_array($this->getSimplerPlugin(), $params);
    }
}