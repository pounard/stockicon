<?php

namespace StockIcon\Tests;

use StockIcon\IconInfo;
use StockIcon\IconTheme;
use StockIcon\Impl\DesktopIconTheme;
use StockIcon\Impl\MapBasedIconTheme;

class DesktopIconThemeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $path;

    public function setUp()
    {
        // @todo CONFIGURABRU!!!
        $this->path = '/tmp/gnome-icon-theme-symbolic/gnome';

        if (!is_dir($this->path)) {
            $this->markTestSkipped("Please set up the gnome-icon-theme-symbolic path for testing.");
        }

        parent::setUp();
    }

    public function testNameMap()
    {
        $theme = new DesktopIconTheme($this->path);

        // Tests that the name is computed from the path when not provided
        $this->assertEquals('gnome', $theme->getThemeName());

        $theme = new DesktopIconTheme($this->path, 'symbolic');

        // Tests that the name is the one given if given
        $this->assertEquals('symbolic', $theme->getThemeName());
    }

    public function testCreateMap()
    {
        $theme = new DesktopIconTheme($this->path, 'symbolic');

        $map = $theme->dumpMap();

        // Simple one, but ensures we have something, at least
        $this->assertNotEmpty($map);

        /*
         * Those are known icons:
         *  - 'text-x-generic', In 'mimetypes' context
         *  - 'audio-card',     In 'devices' context
         *  - 'face-kiss',      In 'emotes' context
         *  - 'system-users',   In 'apps' context
         */ 

        $this->assertTrue(isset($map['text-x-generic'][0]['scalable']));
        $this->assertEquals('mimetypes', $map['text-x-generic'][1]);

        $this->assertTrue(isset($map['audio-card'][0]['scalable']));
        $this->assertEquals('devices', $map['audio-card'][1]);

        $this->assertTrue(isset($map['face-kiss'][0]['scalable']));
        $this->assertEquals('emotes', $map['face-kiss'][1]);

        $this->assertTrue(isset($map['system-users'][0]['scalable']));
        $this->assertEquals('apps', $map['system-users'][1]);
    }

    public function testGetIconInfo(IconTheme $theme = null)
    {
        if (null === $theme) {
            $theme = new DesktopIconTheme($this->path, 'symbolic');
        }

        // Ensure get works
        $iconInfo = $theme->getIconInfo('text-x-generic', IconInfo::SCALABLE);
        $this->assertEquals('text-x-generic', $iconInfo->getIconName());
        $this->assertEquals(IconInfo::SCALABLE, $iconInfo->getBaseSize());

        // Ensure fallback works
        $iconInfo = $theme->getIconInfo('face-kiss', IconInfo::ICON_SIZE_32);
        $this->assertEquals('face-kiss', $iconInfo->getIconName());
        $this->assertEquals(IconInfo::SCALABLE, $iconInfo->getBaseSize());
    }

    public function testRestoreDump()
    {
        $theme = new DesktopIconTheme($this->path, 'symbolic');
        $map   = $theme->dumpMap();
        $theme = new MapBasedIconTheme('symbolic', $map); 

        $this->testGetIconInfo($theme);
    }
}
