<?php

namespace StockIcon\Impl;

use StockIcon\IconTheme;
use StockIcon\ThemeFactory;

/**
 * Default implementation of the theme factory working with a simple hashmap
 * of themes
 */
class MapBasedThemeFactory extends AbstractIconRendererAware implements
    ThemeFactory
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
        return $this->themeMap;
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
        $isNew = false;

        if (!isset($this->themeMap[$themeName])) {
            throw new \InvalidArgumentException(
                sprintf("Theme '%s' does not exist", $themeName));
        }

        $theme = $this->themeMap[$themeName];

        if (!$theme instanceof IconTheme) {
            if (is_callable($theme)) {
                $theme = call_user_func($theme);

                if (!$theme instanceof IconTheme) {
                    throw new \LogicException(sprintf(
                        "Callback provided a wrong instance for theme '%s'",
                        $themeName));
                }

                $isNew = true;
            } else if (is_array($theme)) {
                $className = array_shift($theme);
                $r = new \ReflectionClass($className);

                if (empty($theme)) {
                    $theme = $r->newInstance();
                } else {
                    $theme = $r->newInstanceArgs(array_shift($theme));
                }

                $isNew = true;
            } else if (is_string($theme) && class_exists($theme)) {
                $theme = new $theme();

                $isNew = true;
            } else {
                throw new \LogicException(sprintf(
                    "Invalid theme data for theme '%s'", $themeName));
            }
        }

        if ($isNew) {
            $theme->setIconRenderer($this->getIconRenderer());
            $this->themeMap[$themeName] = $theme;
        }

        return $theme;
    }
}
