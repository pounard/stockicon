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

    /**
     * Get real and public image internal or external URI
     *
     * All themes will be asked following the internal order until the icon
     * has been found or all themes have been iterated over
     *
     * This method is error safe, it won't throw exceptions and return a
     * straight null in case of any error
     *
     * @param string $iconName Icon name
     * @param string $size     Icon size
     *
     * @return string          Icon displayable URI in HTML, or null if no
     *                         icon could be find
     */
    public function renderIcon($iconName, $size);
}
