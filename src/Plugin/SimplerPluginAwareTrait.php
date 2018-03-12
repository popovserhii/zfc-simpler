<?php

namespace Popov\Simpler\Plugin;

trait SimplerPluginAwareTrait
{
    /**
     * @var SimplerPlugin $simplerPlugin
     */
    protected $simplerPlugin;

    /**
     * @return SimplerPlugin
     */
    public function getSimplerPlugin()
    {
        return $this->simplerPlugin;
    }

    /**
     * @param SimplerPlugin $simplerPlugin
     */
    public function setSimplerPlugin(SimplerPlugin $simplerPlugin)
    {
        $this->simplerPlugin = $simplerPlugin;
    }
}