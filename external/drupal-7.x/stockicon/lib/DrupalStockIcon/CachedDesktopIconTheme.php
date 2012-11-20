<?php

namespace DrupalStockIcon;

use StockIcon\Impl\MapBasedIconTheme;

class CachedMapIconTheme extends MapBasedIconTheme
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var array
     */
    private $args;

    /**
     * @var string
     */
    private $cacheBin;

    /**
     * Default constructor
     *
     * @param string $themeName Theme name
     * @param string $className Nested instance class name
     * @param array $args       Nested instance constructor arguments
     */
    public function __construct($themeName, $className, array $args = null, $cacheBin = 'cache')
    {
        $this->className = $className;
        $this->args      = $args;
        $this->cacheBin  = $cacheBin;

        $map = $this->buildMap($name);

        parent::__construct($themeName, $map);
    }

    /**
     * Build map from cache, if available, or from nested instance
     *
     * @param string $themeName Theme name
     *
     * @return array            Icon map
     */
    protected function buildMap($themeName)
    {
        $cid = 'stockicon:' . $name;

        if ($cached = cache_get($cid, $this->cacheBin)) {
            return $cached->data;
        }

        $r = new \ReflectionClass($this->className);

        if (null === $this->args) {
            $instance = $r->newInstance();
        } else {
            $instance = $r->newInstanceArgs($this->args);
        }

        if (!$instance instanceof MapBasedIconTheme) {
            throw new \InvalidArgumentException(
                "Nested instance must be a '\StockIcon\Impl\MapBasedIconTheme' instance");
        }

        $map = $instance->dumpMap();

        cache_set($cid, $map, $this->cacheBin);

        return $map;
    }
}
