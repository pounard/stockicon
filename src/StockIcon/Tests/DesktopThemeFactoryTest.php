<?php

namespace StockIcon\Tests;

use StockIcon\Impl\DesktopThemeFactory;

class DesktopThemeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $path;

    public function setUp()
    {
        // @todo CONFIGURABRU!!!
        $this->path = '/tmp/gnome-icon-theme-symbolic';

        if (!is_dir($this->path)) {
            $this->markTestSkipped("Please set up the gnome-icon-theme-symbolic path for testing.");
        }

        parent::setUp();
    }

    public function testDiscovery()
    {
        $factory = new DesktopThemeFactory();
        $factory->addPath($this->path);

        $this->assertTrue($factory->hasTheme('gnome'));

        $theme = $factory->getTheme('gnome');
        $this->assertInstanceOf('\StockIcon\Impl\DesktopIconTheme', $theme);
    }
}
