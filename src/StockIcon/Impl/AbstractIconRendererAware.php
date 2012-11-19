<?php

namespace StockIcon\Impl;

use StockIcon\IconRenderer;
use StockIcon\IconRendererAware;

abstract class AbstractIconRendererAware implements IconRendererAware
{
    /**
     * @var \StockIcon\IconRenderer
     */
    private $iconRenderer;

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconRendererAware::setIconRenderer()
     */
    public function setIconRenderer(IconRenderer $iconRenderer)
    {
        $this->iconRenderer = $iconRenderer;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconRendererAware::getIconRenderer()
     */
    public function getIconRenderer()
    {
        if (null === $this->iconRenderer) {
            throw new \LogicException("No icon renderer set");
        }

        return $this->iconRenderer;
    }
}
