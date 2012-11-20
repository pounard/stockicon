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
     *                         If the upper layer gave a PHP stream URI instead
     *                         of a plain file path, this function will return
     *                         the exact same URI if stream is considered as
     *                         public since it cannot guess the real file path
     */
    public function render(IconInfo $source, $size);
}
