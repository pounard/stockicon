<?php

namespace StockIcon\Impl;

use StockIcon\IconInfo;

/**
 * Icon theme implementation that will be able to use *NIX desktop themes
 *
 * This implementation will work over classical desktop icon themes and their
 * directory structure
 *
 * Path can be either in or outside of the webroot (relative or absolute)
 */
class DesktopIconTheme extends MapBasedIconTheme
{
    /**
     * Base path
     *
     * @var string
     */
    private $path;

    /**
     * Default constructor
     *
     * @param string $path      Theme base path
     * @param string $themeName This theme name, if none provided it will be
     *                          the path basename
     */
    public function __construct($path, $themeName = null)
    {
        $this->path = $path;

        if (null === $themeName) {
            $themeName = basename($this->path);
        }

        parent::__construct($themeName, $this->parsePath());
    }

    /**
     * Parse file path and populate an image map
     *
     * @param string $path Local file path
     *
     * @return array       File map compatible with parent class internal map
     */
    protected function parsePath()
    {
        $map = array();

        $basePath = $this->getPath();
        $strLen   = strlen($basePath) + 1;
        $suffix   = '-' . $this->getThemeName();

        $files = new \RecursiveIteratorIterator( 
            new \RecursiveDirectoryIterator(
                $basePath,
                \FileSystemIterator::KEY_AS_PATHNAME |
                \FileSystemIterator::SKIP_DOTS |
                \FileSystemIterator::CURRENT_AS_FILEINFO));

        foreach ($files as $uri => $file) {
            if ($file->isFile()) {

                $relPath = substr($uri, $strLen);
                $parts   = explode(DIRECTORY_SEPARATOR, $relPath);

                // 3 parts are SIZE/CONTEXT/IMAGE.EXT
                if (3 === count($parts)) {

                    $size     = $parts[0];
                    $context  = $parts[1];
                    $iconName = $parts[2];

                    if (false !== ($pos = strrpos($iconName, '.'))) {
                        $iconName = substr($parts[2], 0, $pos);
                    }

                    // Some themes, such as gnome symbolic theme will suffix
                    // all their icons with the theme name, ensure this does
                    // not disturb normal functionning. Gnome developers did
                    // that to ensure that only applications looking up for
                    // the symbolic items espcially will find it and fallback
                    // to default if not, but we want them to be found in the
                    // end
                    // FIXME: Find a better way (maybe by specifying a possible
                    // suffix list into constructor?)
                    if ($pos = strrpos($iconName, $suffix)) {
                        $iconName = substr($iconName, 0, $pos);
                    }

                    $map[$iconName][0][$size] = $uri;
                    $map[$iconName][1]        = $context;
                }
            }
        }

        return $map;
    }

    /**
     * Get path
     *
     * @return string $path
     */
    public function getPath()
    {
        return $this->path;
    }
}
