<?php

namespace StockIcon\Impl;

/**
 * The theme factory is able to find out themes in specified search path(s)
 * and provide easy listing capabilities
 *
 * Override is possible if you need caching in order to avoid useless file
 * system lookups in a performance critical production environment
 */
class DesktopThemeFactory extends MapBasedThemeFactory
{
    /**
     * @var array
     */
    protected $pathList = array();

    /**
     * Default constructor
     *
     * @param array $pathList Initial path list in which to lookup for themes
     */
    public function __construct(array $pathList = null)
    {
        parent::__construct(array());

        if (null !== $pathList) {
            foreach ($pathList as $path) {
                $this->addPath($path);
            }
        }
    }

    /**
     * Path lookup for themes
     *
     * @param string $path Path to refresh
     */
    protected function lookupPath($path)
    {
        $iterator = new \DirectoryIterator($path);

        foreach ($iterator as $file) {
            if ($file->isDir() && !$file->isDot()) {

                $filePath  = $file->getPath() . DIRECTORY_SEPARATOR . $file->getBasename();
                $indexPath = $filePath . DIRECTORY_SEPARATOR . 'index.theme';

                if (file_exists($indexPath)) {
                    $this->themeMap[$file->getBasename()] = new DesktopIconTheme($filePath);
                }
            }
        }
    }

    /**
     * Append path search list with new path
     *
     * @param string $path Path
     */
    public function addPath($path)
    {
        if (!in_array($path, $this->pathList)) {
            $this->pathList[] = $path;
            $this->lookupPath($path);
        }
    }
}
