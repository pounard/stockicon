<?php

namespace StockIcon\Impl;

use StockIcon\DefaultIconInfo;
use StockIcon\IconInfo;
use StockIcon\IconTheme;

/**
 * Static map based icon theme, ideally you should always use this
 * implementation along with a caching layer whatever is your icon source
 */
class MapBasedIconTheme implements IconTheme
{
    /**
     * @var string
     */
    private $name;

    /**
     * Known images map
     *
     * @var array
     */
    protected $imageMap;

    /**
     * Default constructor
     *
     * @param string $themeName This theme name, if none provided it will be
     *                          the first search path basename
     * @param array $imageMap   Image map, see class documentation for format
     */
    public function __construct($themeName, array $imageMap)
    {
        $this->name     = $themeName;
        $this->imageMap = $imageMap;
    }

    /**
     * For caching purposes, you might need to dump the image map
     *
     * @return array
     */
    public function dumpMap()
    {
       return $this->imageMap;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getThemeName()
     */
    final public function getThemeName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::hasIcon()
     */
    public function hasIcon($iconName)
    {
        return isset($this->map[$iconName]);
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getIconInfo()
     */
    public function getIconInfo($iconName, $size)
    {
        if (!isset($this->imageMap[$iconName])) {
            throw new \InvalidArgumentException(sprintf(
                "Icon '%s' does not exists", $iconName));
        }

        $sizes = $this->imageMap[$iconName][0];

        if (!isset($sizes[$size]) && IconInfo::SCALABLE !== $size) {
            // Allow scalable variant to replace any fixed size image
            if (isset($sizes[IconInfo::SCALABLE])) {
                $uri  = $sizes[IconInfo::SCALABLE];
                $size = IconInfo::SCALABLE;
            } else {
                throw new \InvalidArgumentException(sprintf(
                    "Size '%s' for icon '%s' does not exists", $size, $iconName));
            }
        } else {
            $uri = $sizes[$size];
        }

        return new DefaultIconInfo($iconName, $size, $uri, $this);
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getContextLists()
     */
    public function getContextLists()
    {
        $ret = array();

        foreach ($this->imageMap as $imageName => $data) {
            if (isset($data[1]) && !isset($ret[$data[1]])) {
                $ret[$data[1]] = $data[1];
            }
        }

        return $ret;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getIconList()
     */
    public function getIconList($context = null)
    {
        return array_keys($this->imageMap);
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconTheme::getIconSizes()
     */
    public function getIconSizes($iconName)
    {
        if (!isset($this->imageMap[$iconName])) {
            throw new \InvalidArgumentException(sprintf(
                "Icon '%s' does not exists", $iconName));
        }

        return $this->imageMap[$iconName][0];
    }
}
