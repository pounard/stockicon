<?php

namespace StockIcon\Tests;

use StockIcon\IconInfo;
use StockIcon\Impl\DesktopIconTheme;
use StockIcon\Renderer\LocalFileRenderer;

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
        $iconName = 'text-x-generic';
        $renderer = new LocalFileRenderer($this->theme, sys_get_temp_dir());

        $uri = $renderer->render($iconName, IconInfo::ICON_SIZE_64);
    }
}


