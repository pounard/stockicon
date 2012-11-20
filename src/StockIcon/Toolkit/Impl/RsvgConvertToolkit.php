<?php

namespace StockIcon\Toolkit\Impl;

use StockIcon\Toolkit\ToolkitHelper;
use StockIcon\Toolkit\ToolkitInterface;

/**
 * Imagick implementation
 */
class RsvgConvertToolkit implements ToolkitInterface
{
    /**
     * @var string
     */
    private $rsvgBin;

    /**
     * Default constructor
     *
     * @param string $rsvgBin RSVG convert tool binary
     */
    public function __construct($rsvgBin = null)
    {
        if (null === $rsvgBin) {
            $this->rsvgBin = shell_exec("which rsvg-convert");
        } else {
            $this->rsvgBin = $rsvgBin;
        }
    }

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

        $command = sprintf("%s %s -w %s -h %d > %s",
            escapeshellcmd($this->rsvgBin),
            escapeshellarg($sourceFile),
            $x, $y,
            escapeshellarg($destFile));

        shell_exec($command);

        if (!file_exists($destFile)) {
            throw new \RuntimeException(sprintf(
                "Could not generated file '%s'", $destFile));
        }

        return $destFile;
    }
}
