<?php

namespace Popov\Simpler;

interface SimplerHelperAwareInterface
{
    /**
     * @return SimplerHelper
     */
    public function getSimplerHelper();

    /**
     * @param SimplerHelper $simplerHelper
     * @return void
     */
    public function setSimplerHelper(SimplerHelper $simplerHelper);
}