<?php

namespace StockIcon\Toolkit;

/**
 * Basic image toolkit for image transformation
 */
interface ToolkitInterface
{
    /**
     * Convert SVG image to PNG image
     *
     * This method will be used for scalable images to be dumped at the asked
     * size
     *
     * @param string $sourceFile Source file
     * @param string $size       Size string such as "32x32" for example
     * @param string $destFile   Optional destination file, without this
     *                           parameter file will go to temporary dir
     *
     * @return string            New file path
     */
    public function svgToPng($sourceFile, $size, $destFile = null);
}
