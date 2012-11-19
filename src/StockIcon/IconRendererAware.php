<?php

namespace StockIcon;

interface IconRendererAware
{
    /**
     * Set icon renderer
     *
     * @param IconRenderer $iconRenderer Icon rendered
     */
    public function setIconRenderer(IconRenderer $iconRenderer);

    /**
     * Get icon renderer
     *
     * @return \StockIcon\IconRenderedAware Icon renderer
     */
    public function getIconRenderer();
}
