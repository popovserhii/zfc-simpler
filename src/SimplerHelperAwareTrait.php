<?php

namespace Popov\Simpler;

trait SimplerHelperAwareTrait
{
    /**
     * @var SimplerHelper $simplerHelper
     */
    protected $simplerHelper;

    /**
     * @return SimplerHelper
     */
    public function getSimplerHelper()
    {
        return $this->simplerHelper;
    }

    /**
     * @param SimplerHelper $simplerHelper
     */
    public function setSimplerHelper(SimplerHelper $simplerHelper)
    {
        $this->simplerHelper = $simplerHelper;
    }
}