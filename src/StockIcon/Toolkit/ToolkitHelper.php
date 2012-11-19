<?php

namespace StockIcon\Toolkit;

use StockIcon\Toolkit\Impl\ImagickToolkit;
use StockIcon\Toolkit\Impl\RsvgConvertToolkit;

/**
 * Static size handling helper
 */
class ToolkitHelper
{
    /**
     * @var \StockIcon\Toolkit\ToolkitInterface
     */
    static private $toolkit;

    /**
     * Get default toolkit
     *
     * @return \StockIcon\Toolkit\ToolkitInterface Toolkit 
     */
    final static public function getToolkit()
    {
        if (null === self::$toolkit) {
            // Attempt to find a suitable toolkit for this system
            if (class_exists('Imagick')) {
                // Most obvious and easy choice
                self::$toolkit = new ImagickToolkit();
            } else if (shell_exec("which rsvg-convert")) {
                self::$toolkit = new RsvgConvertToolkit();
            }

            if (null === self::$toolkit) {
                throw new \RuntimeException(
                    "Could not find a working toolkit on this system");
            }
        }

        return self::$toolkit;
    }

    /**
     * Static toolkit init
     *
     * @param ToolkitInterface Toolkit
     */
    final static public function setToolkit(ToolkitInterface $toolkit)
    {
        self::$toolkit = $toolkit;
    }

    /**
     * Get dimensions from size
     *
     * @param string $size Size string such as "32x32"
     *
     * @return array       First value is x, second value is y
     */
    final static public function getDimensionsFromSize($size)
    {
        if (!is_string($size)) {
            throw new \InvalidArgumentException("Invalid size given");
        }

        $dimensions = explode('x', $size);

        if (!is_numeric($dimensions[0]) ||
            !is_numeric($dimensions[0]) ||
            count($dimensions) !== 2)
        {
            throw new \InvalidArgumentException(
                sprintf("Invalid size given '%s'", $size));
        }

        return $dimensions;
    }

    /**
     * Get size from dimensions
     *
     * @param int $x  X value
     * @param int $y  Y value
     *
     * @return string Size string
     */
    final static public function getSizeFromDimensions($x, $y)
    {
        return $x . 'x' . $y;
    }
}
