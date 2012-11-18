<?php

namespace StockIcon;

/**
 * Look up icons by name and size
 */
interface IconTheme
{
    /**
     * Get theme name
     *
     * @return string
     */
    public function getThemeName();

    /**
     * Tell if the current theme has the given icon
     *
     * @param string $iconName Icon name
     *
     * @return bool            True if icon exists
     */
    public function hasIcon($iconName);

    /**
     * Get icon info
     *
     * If the icon doesn't exist in the given size but has a scalable variant,
     * the scalable variant will be returned instead
     *
     * @param string $iconName      Icon name
     * @param string $size          Icon size
     *
     * @return \StockIcon\IconInfo  Icon info instance
     */
    public function getIconInfo($iconName, $size);

    /**
     * List icon contextes
     *
     * @return array List of string context names
     */
    public function getContextLists();

    /**
     * List icons
     *
     * @param string $context If given list only icons of the given context
     *
     * @return array          List of string icon names
     */
    public function getIconList($context = null);

    /**
     * Get icon available sizes
     *
     * @param string $iconName Icon name
     *
     * @return array           List of integer icon sizes including
     *                         IconInfo::SCALABLE if icon has a scalable
     *                         variant 
     */
    public function getIconSizes($iconName);
}
