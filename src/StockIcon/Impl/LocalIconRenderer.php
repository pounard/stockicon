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
class LocalIconRenderer extends ReadonlyIconRenderer
{
    /**
     * @var string
     */
    private $publicDir;

    /**
     * Default constructor
     *
     * @param string $publicRoot        Path of current webroot, considered as
     *                                  public, any URL coming from there will be
     *                                  considered as public
     * @param string $publicDir         Public dir where to store generated or
     *                                  copied icons
     * @param ToolkitInterface $toolkit Image toolkit if different from default
     * @param array $publicSchemes      List of stream wrapper schemes known to
     *                                  give only files accessible publicly
     *                                  from HTTP (remote or local doesn't
     *                                  matter)
     */
    public function __construct(
        $publicRoot,
        $publicDir = null,
        ToolkitInterface $toolkit = null,
        array $publicSchemes = null)
    {
        parent::__construct($publicRoot, $publicSchemes);

        if (null !== $toolkit) {
            $this->setToolkit($toolkit);
        }
        if (null !== $publicDir) {
            $this->setPublicDir($publicDir);
        }
    }

    /**
     * Set public working directory where generated icons will be stored
     *
     * @param string $publicDir Public working directory
     */
    final public function setPublicDir($publicDir)
    {
        if (!is_writable($publicDir)) {
            throw new \InvalidArgumentException(sprintf(
                "Directory '%s' is not writable", $publicDir));
        }

        $this->publicDir = $publicDir;
    }

    /**
     * Get public working directory where generated icons will be stored
     *
     * @param string Public working directory
     */
    final public function getPublicDir()
    {
        return $this->getPublicDir();
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

            if ($this->isPublic($uri)) {
                if ($relativePath = $this->getRelativePath($uri)) {
                    return $relativePath;
                } else {
                    // The caller gave us a scheme'd URI we cannot guess where
                    // the file really is, it is the business layer
                    // responsability to be able to create the real URL
                    return $uri;
                }
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

            try {
                return $this->getToolkit()->svgToPng($source->getURI(), $size, $destFile);
            } catch (\Exception $e) {
                // Sorry, toolkit may have it wrong, case in which we surely
                // don't want to leave the exception pass
                return null;
            }
        }
    }
}
