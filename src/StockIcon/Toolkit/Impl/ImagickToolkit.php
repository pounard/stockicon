<?php

namespace StockIcon\Toolkit\Impl;

use StockIcon\Toolkit\ToolkitHelper;
use StockIcon\Toolkit\ToolkitInterface;

/**
 * Imagick implementation
 */
class ImagickToolkit implements ToolkitInterface
{
    /**
     * (non-PHPdoc)
     * @see \StockIcon\Toolkit\ToolkitInterface::svgToPng()
     */
    public function svgToPng($sourceFile, $size, $destFile = null)
    {
        list($x, $y) = ToolkitHelper::getDimensionsFromSize($size);

        if (null === $destFile) {
            if (!$destFile = tempnam(sys_get_temp_dir(), 'stk')) {
                throw new \RuntimeException("Could not acquire temporary file");
            }
        }

        $image = new Imagick();
        $image->readImageBlob(file_get_contents($sourceFile));
        $image->setImageFormat("png24");
        $image->resizeImage($x, $y, Imagick::FILTER_LANCZOS, 1);
        $image->writeImage($destFile);

        return $destFile;
    }
}
