<?php

namespace Agere\Simpler\Plugin;

interface SimplerPluginAwareInterface
{
    /**
     * @return SimplerPlugin
     */
    public function getSimplerPlugin();

    /**
     * @param SimplerPlugin $simplerPlugin
     * @return void
     */
    public function setSimplerPlugin(SimplerPlugin $simplerPlugin);
}