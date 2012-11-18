<?php

namespace StockIcon;

interface IconThemeAware
{
    /**
     * Set icon theme
     *
     * @param IconTheme $theme Icon theme
     */
    public function setIconTheme(IconTheme $iconTheme);

    /**
     * Get icon theme
     *
     * @return \StockIcon\IconTheme Icon theme
     */
    public function getIconTheme();
}
