<?php

namespace StockIcon\Tests;

use StockIcon\IconInfo;
use StockIcon\Impl\DesktopIconTheme;
use StockIcon\Impl\LocalIconRenderer;

class LocalFileRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var \StockIcon\IconTheme
     */
    private $theme;

    public function setUp()
    {
        // @todo CONFIGURABRU!!!
        $this->path  = '/usr/share/icons/gnome';

        $this->theme = new DesktopIconTheme($this->path, 'symbolic');

        if (!is_dir($this->path)) {
            $this->markTestSkipped("Please set up the gnome-icon-theme-symbolic path for testing.");
        }

        parent::setUp();
    }

    public function testNameMap()
    {
        $iconName     = 'text-x-generic';
        $scalableSize = IconInfo::SCALABLE;
        $targetSize   = IconInfo::ICON_SIZE_32;

        $renderer = new LocalIconRenderer('/', sys_get_temp_dir());

        // Try with a public fixed existing item
        $icon = $this->theme->getIconInfo($iconName, $targetSize);
        $uri = $renderer->render($icon, $targetSize);
        $this->assertNotNull($uri);
        $this->assertStringEndsWith('.png', $uri);

        // Try with a public scalable item
        $icon = $this->theme->getIconInfo($iconName, $scalableSize);
        $uri = $renderer->render($icon, $targetSize);
        $this->assertNotNull($uri);
        $this->assertStringEndsWith('.png', $uri);

        // Small trick, change the renderer root at runtime to make it
        // believe there is nothing that can be in webroot
        $renderer->setPublicRoot('/no-way');

        // Try with a non public fixed existing item
        $icon = $this->theme->getIconInfo($iconName, $targetSize);
        $uri = $renderer->render($icon, $targetSize);
        $this->assertNotNull($uri);
        $this->assertStringEndsWith('.png', $uri);

        // Try with a non public scalable item
        $icon = $this->theme->getIconInfo($iconName, $scalableSize);
        $uri = $renderer->render($icon, $targetSize);
        $this->assertNotNull($uri);
        $this->assertStringEndsWith('.png', $uri);

        // Try with an external item
        // @todo

        // Try with an unknown item
        // @todo
    }
}


