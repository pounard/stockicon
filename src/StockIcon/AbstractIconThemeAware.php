<?php

namespace StockIcon;

class AbstractIconThemeAware implements IconThemeAware
{
    /**
     * @var \StockIcon\IconTheme
     */
    private $iconTheme;

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconThemeAware::setIconTheme()
     */
    final public function setIconTheme(IconTheme $iconTheme)
    {
        $this->iconTheme = $iconTheme;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconThemeAware::getIconTheme()
     */
    final public function getIconTheme()
    {
        if (null === $this->iconTheme) {
            throw new \LogicException("No icon theme set");
        }

        return $this->iconTheme;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconThemeAware::getThemeName()
     */
    final public function getThemeName()
    {
        return $this->getIconTheme()->getThemeName();
    }
}
