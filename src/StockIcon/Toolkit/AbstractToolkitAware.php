<?php

namespace StockIcon\Toolkit;

abstract class AbstractToolkitAware
{
    /**
     * @var \StockIcon\Toolkit\ToolkitInterface
     */
    private $toolkit;

    /**
     * Set toolkit
     *
     * @param ToolkitInterface $toolkit Toolkit
     */
    public function setToolkit(ToolkitInterface $toolkit)
    {
        $this->toolkit = $toolkit;
    }

    /**
     * Get toolkit
     *
     * @return \StockIcon\Toolkit\ToolkitInterface
     */
    public function getToolkit()
    {
        if (null === $this->toolkit) {
            return ToolkitHelper::getToolkit();
        } else {
            return $this->toolkit;
        }
    }
}
