<?php

namespace DrupalStockIcon;

use StockIcon\IconInfo;
use StockIcon\Impl\ReadonlyIconRenderer;

/**
 * Specific icon renderer implementation that will provide a dynamically
 * generated file URI instead of generating during the script runtime, this
 * working the same way as image style does
 */
class DrupalIconRenderer extends ReadonlyIconRenderer
{
    /**
     * @var string
     */
    private $publicDir;

    /**
     * Default constructor
     *
     * @param string $publicRoot   Public webserver root
     * @param string $publicDir    Folder where to save generated files
     * @param array $publicSchemes Public known schemes
     */
    public function __construct($publicRoot, $publicDir, array $publicSchemes = null)
    {
        parent::__construct($publicRoot, $publicSchemes);

        $this->publicDir = $publicDir;
    }

    /**
     * Get folder where generated files will be saved
     *
     * @return string Folder where to save generated files
     */
    public function getPublicDir()
    {
        return $this->publicDir;
    }

    /**
     * Get menu router path
     *
     * @return string Menu router path
     */
    public function getMenuRouterPath()
    {
        if (false === strpos($this->publicDir, '://')) {
            return $this->publicDir;
        }

        list($scheme, $target) = explode('://', $this->publicDir);

        if (($wrapper = file_stream_wrapper_get_instance_by_scheme($scheme)) &&
           $wrapper instanceof \DrupalPublicStreamWrapper)
        {
            return $wrapper->getDirectoryPath() . '/' . $target;
        } else {
            return null;
        }
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconRenderer::render()
     */
    public function render(IconInfo $source, $size)
    {
        // The read-only implementation is smart enought to build URI for us
        // that we will be able to render from fixed static public existing
        // images
        $result = parent::render($source, $size);

        if (null !== $result || IconInfo::SCALABLE === $size) {
            return $result;
        }

        // We can copy the image from where it comes from or we can scale
        // the image from whatever it comes from if it is scalable
        if ($source->getBaseSize() === $size ||
            IconInfo::SCALABLE === $source->getBaseSize())
        {
            return implode('/', array(
                $this->publicDir,
                $source->getThemeName(),
                $size,
                $source->getIconName() . '.png',
            ));
        }
    }
}
