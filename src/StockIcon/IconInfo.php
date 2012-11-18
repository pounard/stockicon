<?php

namespace StockIcon;

/**
 * Object containing information about and icon in an icon theme
 */
interface IconInfo extends IconThemeAware
{
    /**
     * Icon size is scalable
     */
    const SCALABLE = 'scalable';

    /**
     * Icon size is 16x16
     */
    const ICON_SIZE_16 = '16x16';

    /**
     * Icon size is 32x32
     */
    const ICON_SIZE_32 = '32x32';

    /**
     * Icon size is 64x64
     */
    const ICON_SIZE_64 = '64x64';

    /**
     * Get icon name
     *
     * @return string Icon name
     */
    public function getIconName();

    /**
     * Get icon URI
     *
     * URI can be local either inside or outside of the webroot, or can be
     * external, depending on your IconTheme implementation
     *
     * @return string Icon URI
     */
    public function getURI();

    /**
     * Get base icon size
     *
     * @return string Icon size
     */
    public function getBaseSize();
}
