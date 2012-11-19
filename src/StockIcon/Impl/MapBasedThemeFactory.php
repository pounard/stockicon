<?php

namespace StockIcon\Impl;

use StockIcon\IconTheme;
use StockIcon\ThemeFactory;

/**
 * Default implementation of the theme factory working with a simple hashmap
 * of themes
 */
class MapBasedThemeFactory implements ThemeFactory
{
    /**
     * @var array
     */
    protected $themeMap;

    /**
     * Default constructor
     *
     * @param array $themeMap Key value pairs, keys are theme names and values
     *                        are either a full blown instance, or a callable
     *                        which create an instance, or a class name
     */
    public function __construct(array $themeMap)
    {
        $this->themeMap = $themeMap;
    }

    /**
     * For caching purposes, you might need to dump the image map
     *
     * @return array
     */
    final public function dumpMap()
    {
        return $this->imageMap;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\ThemeFactory::getAllThemeNames()
     */
    final public function getAllThemeNames()
    {
        return array_keys($this->themeMap);
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\ThemeFactory::hasTheme()
     */
    final public function hasTheme($themeName)
    {
        return isset($this->themeMap[$themeName]);
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\ThemeFactory::getTheme()
     */
    public function getTheme($themeName)
    {
        if (!isset($this->themeMap[$themeName])) {
            throw new \InvalidArgumentException(
                sprintf("Theme '%s' does not exist", $themeName));
        }

        $theme = $this->themeMap[$themeName];

        if ($theme instanceof IconTheme) {
            return $theme;
        } else if (is_callable($theme)) {
            $theme = call_user_func($theme);

            if (!$theme instanceof IconTheme) {
                throw new \LogicException(sprintf(
                    "Callback provided a wrong instance for theme '%s'",
                    $themeName));
            }

            return $this->themeMap[$themeName] = $theme;
        } else if (is_string($theme) && class_exists($theme)) {
            return $this->themeMap[$themeName] = new $theme();
        } else {
            throw new \LogicException(sprintf(
                "Invalid theme data for theme '%s'", $themeName));
        }
    }
}
