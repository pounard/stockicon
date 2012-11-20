<?php

namespace StockIcon\Impl;

use StockIcon\IconTheme;

class NullIconTheme extends AbstractIconRendererAware implements IconTheme
{
    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getThemeName()
     */
    public function getThemeName()
    {
        return 'null';
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::hasIcon()
     */
    public function hasIcon($iconName)
    {
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getIconInfo()
     */
    public function getIconInfo($iconName, $size)
    {
        throw new \InvalidArgumentException(
            sprintf("Icon '%s' does not exist", $iconName));
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::renderIcon()
     */
    public function renderIcon($iconName, $size)
    {
        return null;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getContextLists()
     */
    public function getContextLists()
    {
        return array();
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getIconList()
     */
    public function getIconList($context = null)
    {
        return array();
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getIconSizes()
     */
    public function getIconSizes($iconName)
    {
        throw new \InvalidArgumentException(
            sprintf("Icon '%s' does not exist", $iconName));
    }
}
