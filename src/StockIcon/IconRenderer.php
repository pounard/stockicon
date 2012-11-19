<?php

namespace StockIcon;

/**
 * Object responsible for rendering real publicly accessible icons URI
 */
interface IconRenderer
{
    /**
     * Render icon real URI
     *
     * @param IconInfo $source Icon info
     * @param string $size     Icon size
     *
     * @return string          Public fixed size icon URI which can point to
     *                         a copy of the source image, or a new generated
     *                         version if needs scaling
     */
    public function render(IconInfo $source, $size);
}
