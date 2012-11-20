<?php

namespace StockIcon;

/**
 * Theme factory allows you to find themes in the current context
 */
interface ThemeFactory extends IconRendererAware
{
    /**
     * List all known theme names
     *
     * @return array List of theme names
     */
    public function getAllThemeNames();

    /**
     * Tell if the given theme name is known
     *
     * @param string $themeName Theme name
     */
    public function hasTheme($themeName);

    /**
     * Get theme instance
     *
     * @param string $themeName          Theme name
     *
     * @return \StockIcon\IconTheme      Icon theme instance
     *
     * @throws \InvalidArgumentException If theme does not exist
     */
    public function getTheme($themeName);
}
