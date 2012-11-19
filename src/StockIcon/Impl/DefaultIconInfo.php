<?php

namespace StockIcon\Impl;

use StockIcon\IconInfo;
use StockIcon\IconTheme;

/**
 * Default icon info implementation suitable for most needs
 */
class DefaultIconInfo extends AbstractIconThemeAware implements IconInfo
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $size;

    /**
     * @var string
     */
    private $uri;

    /**
     * Default constructor
     *
     * @param string $name Icon name
     * @param string $size Icon size
     * @param string $uri  Icon URI
     */
    public function __construct($name, $size, $uri, IconTheme $iconTheme = null)
    {
        $this->name = $name;
        $this->size = $size;
        $this->uri  = $uri;

        if (null !== $iconTheme) {
            $this->setIconTheme($iconTheme);
        }
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconInfo::getIconName()
     */
    public function getIconName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconInfo::getURI()
     */
    public function getURI()
    {
        return $this->uri;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconInfo::getBaseSize()
     */
    public function getBaseSize()
    {
        return $this->size;
    }
}
