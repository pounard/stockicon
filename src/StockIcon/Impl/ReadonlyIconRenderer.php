<?php

namespace StockIcon\Impl;

use StockIcon\IconInfo;
use StockIcon\IconRenderer;
use StockIcon\IconTheme;
use StockIcon\Toolkit\AbstractToolkitAware;

/**
 * Most simple version of the renderer, which will never ever try to copy
 * or resize images, but will return null instead
 *
 * It extends \StockIcon\Toolkit\AbstractToolkitAware to allow the local
 * renderer to extend this same class
 */
class ReadonlyIconRenderer extends AbstractToolkitAware implements IconRenderer
{
    /**
     * @var string
     */
    private $publicRoot;

    /**
     * @var array
     */
    private $publicSchemes = array(
        'http' => true,
        'file' => true,
    );

    /**
     * Default constructor
     *
     * @param string $publicRoot        Path of current webroot, considered as
     *                                  public, any URL coming from there will be
     *                                  considered as public
     * @param array $publicSchemes      List of stream wrapper schemes known to
     *                                  give only files accessible publicly
     *                                  from HTTP (remote or local doesn't
     *                                  matter)
     */
    public function __construct(
        $publicRoot,
        array $publicSchemes = null)
    {
        $this->setPublicRoot($publicRoot);

        if (null !== $publicSchemes) {
            $this->publicSchemes += array_flip($publicSchemes);
        }
    }

    /**
     * Tell if given URI is publicly readable from HTTP
     *
     * @param string $uri File URI
     *
     * @return boolean    True if ressource is public
     */
    final public function isPublic($uri)
    {
        if (false !== strpos($uri, '://')) {

            list($scheme) = explode('://', $uri, 2);

            if (isset($this->publicSchemes[$scheme])) {
                return true;
            }
        } else {
            return 0 === strpos($uri, $this->publicRoot);
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
    final public function getRelativePath($uri)
    {
        // Drop PHP stream URI
        if (false !== strpos($uri, '://')) {

            list($scheme, $uri) = explode('://', $uri, 2);

            // 'file://' scheme is a special case since it points to a local
            // known file using the VFS style: we can treat this exception
            // safely in most cases
            if ('file' !== $scheme) {
                return null;
            }
        }

        if (0 === strpos($uri, $this->publicRoot)) {
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
    final public function setPublicRoot($publicRoot)
    {
        $this->publicRoot = $publicRoot;
    }

    /**
     * Get public root directory
     *
     * @return string Public root directory
     */
    final public function getPublicRoot()
    {
        return $this->publicRoot;
    }

    /**
     * (non-PHPdoc)
     * @see \StockIcon\IconRenderer::render()
     */
    public function render(IconInfo $source, $size)
    {
        if (IconInfo::SCALABLE !== $size &&
            $source->getBaseSize() === $size &&
            ($uri = $source->getURI()) &&
            $this->isPublic($uri))
        {
            if ($relativePath = $this->getRelativePath($uri)) {
                return $relativePath;
            } else {
                // The caller gave us a scheme'd URI we cannot guess where
                // the file really is, it is the business layer
                // responsability to be able to create the real URL
                return $uri;
            }
        }
    }
}
