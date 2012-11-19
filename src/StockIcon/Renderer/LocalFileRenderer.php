<?php

namespace StockIcon\Renderer;

use StockIcon\IconInfo;
use StockIcon\IconTheme;
use StockIcon\Toolkit\ToolkitHelper;
use StockIcon\Toolkit\ToolkitInterface;

/**
 * Front object that will proceed to image transformation or copy if needed
 * before sending the URL to the actual image that will be displayed by the
 * browser
 */
class LocalFileRenderer
{
    /**
     * @var \StockIcon\IconTheme
     */
    private $iconTheme;

    /**
     * @var \StockIcon\Toolkit\ToolkitInterface
     */
    private $toolkit;

    /**
     * @var string
     */
    private $publicDir;

    /**
     * Default constructor
     *
     * @param IconTheme $iconTheme      Icon theme
     * @param string $publicDir         Public dir where to store generated or
     *                                  copied icons
     * @param ToolkitInterface $toolkit Image toolkit if different from default
     */
    public function __construct(
        IconTheme $iconTheme,
        $publicDir = null,
        ToolkitInterface $toolkit = null)
    {
        $this->iconTheme = $iconTheme;

        if (null !== $toolkit) {
            $this->toolkit = $toolkit;
        }

        if (null !== $publicDir) {
            $this->setPublicDir($publicDir);
        }
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
     * Get toolkit for image transformation if necessary
     *
     * @return \StockIcon\Toolkit\ToolkitInterface Toolkit
     */
    public function getToolkit()
    {
        if (null === $this->toolkit) {
            return ToolkitHelper::getToolkit();
        } else {
            return $this->toolkit;
        }
    }

    /**
     * Render icon real URI
     *
     * @param string $iconName Icon name
     * @param string $size     Icon size
     */
    public function render($iconName, $size)
    {
        if (IconInfo::SCALABLE === $size) {
            // Cannot render a scalable size
            return null;
        }

        try {
            $iconInfo = $this->iconTheme->getIconInfo($iconName, $size);

            // Scalable icons must be regenerated to fixed size images because
            // most browsers won't be able to use them otherwise
            if (IconInfo::SCALABLE === $iconInfo->getBaseSize()) {
                if (null === $this->publicDir) {
                    // Cant generate anything return nothing
                    return null;
                }

                $destDir  = $this->publicDir . '/' . $this->iconTheme->getThemeName() . '/' . $size;
                $destFile = $destDir . '/' . $iconName . '.png';

                if (!is_dir($destDir)) {
                    if (!mkdir($destDir, 0755, true)) {
                        // Destination directory cannot be created
                        // FIXME: Logging
                        return null;
                    }
                }

                return $this->getToolkit()->svgToPng($iconInfo->getURI(), $size, $destFile);
            }
        } catch (\InvalidArgumentException $e) {
            // Icon does not  return nothing
            return null;
        }
    }
}
