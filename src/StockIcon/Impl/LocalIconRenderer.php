<?php

namespace StockIcon\Impl;

use StockIcon\IconInfo;
use StockIcon\IconRenderer;
use StockIcon\IconTheme;
use StockIcon\Toolkit\AbstractToolkitAware;
use StockIcon\Toolkit\ToolkitHelper;
use StockIcon\Toolkit\ToolkitInterface;

/**
 * Render local icons by copying them if non public or resizing them from the
 * scalable version if non existing at the given size
 */
class LocalIconRenderer extends AbstractToolkitAware implements IconRenderer
{
    /**
     * @var string
     */
    private $publicDir;

    /**
     * @var string
     */
    private $publicRoot;

    /**
     * Default constructor
     *
     * @param string $publicRoot        Path of current webroot, considered as
     *                                  public, any URL coming from there will be
     *                                  considered as public
     * @param string $publicDir         Public dir where to store generated or
     *                                  copied icons
     * @param ToolkitInterface $toolkit Image toolkit if different from default
     */
    public function __construct(
        $publicRoot,
        $publicDir = null,
        ToolkitInterface $toolkit = null)
    {
        $this->setPublicRoot($publicRoot);

        if (null !== $toolkit) {
            $this->setToolkit($toolkit);
        }
        if (null !== $publicDir) {
            $this->setPublicDir($publicDir);
        }
    }

    /**
     * Get relative path from the webroot to this given file URI
     *
     * @param string $uri File URI
     *
     * @return string     File relative path if the file is inside the webroot
     *                    null otherwise
     */
    public function getRelativePath($uri)
    {
        // Handle PHP streams
        if (false !== strpos($uri, '://')) {

            list($scheme, $target) = explode('://', $uri, 2);

            if (!stream_is_local($uri)) {
                return null;
            }

            // HOW? @todo find native file system path
            // This is important: this will cause problems with Drupal
        }

        if (0 === strpos($uri, $this->publicRoot)) {
            // File is public
            return substr($uri, strlen($this->publicRoot));
        } else {
            return null;
        }
    }

    /**
     * Set public root directory
     *
     * @param string $publicRoot Public root directory
     */
    public function setPublicRoot($publicRoot)
    {
        $this->publicRoot = $publicRoot;
    }

    /**
     * Set public working directory where generated icons will be stored
     *
     * @param string $publicDir Public dir
     */
    public function setPublicDir($publicDir)
    {
        if (!is_writable($publicDir)) {
            throw new \InvalidArgumentException(sprintf(
                "Directory '%s' is not writable", $publicDir));
        }

        $this->publicDir = $publicDir;
    }

    /**
     * Get destination filename for either a copy or a new generated file
     *
     * @return string Path
     */
    protected function getDestFilename(IconInfo $source, $size)
    {
        $themeName = $source->getThemeName();
        $destDir   = $this->publicDir . '/' . $themeName . '/' . $size;
        $destFile  = $destDir . '/' . $source->getIconName() . '.png';

        if (!is_dir($destDir)) {
            if (!mkdir($destDir, 0755, true)) {
                // Destination directory cannot be created
                // FIXME: Logging
                return null;
            }
        }

        return $destFile;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconRenderer::render()
     */
    public function render(IconInfo $source, $size)
    {
        if (IconInfo::SCALABLE === $size) {
            // Cannot render a scalable size
            return null;
        }

        // Scalable icons must be regenerated to fixed size images because
        // most browsers won't be able to use them otherwise
        if ($source->getBaseSize() === $size) {

            $uri = $source->getURI();

            if ($relativePath = $this->getRelativePath($uri)) {
                return $relativePath;
            } else {
                if (!$destFile = $this->getDestFilename($source, $size)) {
                    // Could not create the destination dir
                    return null;
                }

                if (!copy($uri, $destFile)) {
                    // Could not copy file
                    return null;
                }

                return $destFile;
            }
        } else if (IconInfo::SCALABLE === $source->getBaseSize()) {

            if (null === $this->publicDir) {
                // Cant generate anything return nothing
                return null;
            }

            if (!$destFile = $this->getDestFilename($source, $size)) {
                // Could not create the destination dir
                return null;
            }

            return $this->getToolkit()->svgToPng($source->getURI(), $size, $destFile);
        }
    }
}
