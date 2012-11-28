<?php

namespace DrupalStockIcon;

use StockIcon\Impl\MapBasedThemeFactory;

class CachedThemeFactory extends MapBasedThemeFactory
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
     * @param string $className Nested instance class name
     * @param array $args       Nested instance constructor arguments
     */
    public function __construct($className, array $args = null, $cacheBin = 'cache')
    {
        $this->className = $className;
        $this->args      = $args;
        $this->cacheBin  = $cacheBin;

        $map = $this->buildMap();

        parent::__construct($map);
    }

    /**
     * Build map from cache, if available, or from nested instance
     *
     * @param string $themeName Theme name
     *
     * @return array            Icon map
     */
    protected function buildMap()
    {
        $cid = 'stockicon:factory:' . md5($this->className);

        if ($cached = cache_get($cid, $this->cacheBin)) {
            return $cached->data;
        }

        $r = new \ReflectionClass($this->className);

        if (null === $this->args) {
            $instance = $r->newInstance();
        } else {
            $instance = $r->newInstanceArgs($this->args);
        }

        if (!$instance instanceof MapBasedThemeFactory) {
            throw new \InvalidArgumentException(
                "Nested instance must be a '\StockIcon\Impl\MapBasedThemeFactory' instance");
        }

        $map = $instance->dumpMap();

        cache_set($cid, $map, $this->cacheBin);

        return $map;
    }
}
