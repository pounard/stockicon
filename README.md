Stock icon
==========

Icon set/Stock icons handling API for reusing common UNIX Desktop icons themes
easily.

Getting started
===============

Basic usage
-----------

Basic sample usage of a single theme.

    use StockIcon\IconInfo;
    use StockIcon\Impl\DesktopIconTheme;

    // Assuming you're working on a *NIX box with some icons themes
    // already being there: this for sample purposes, but in real life
    // you're advised to download manually the icon themes you'd want
    // to use on your site somewhere else
    // Let's say you are using KDE, and want the oxygen icons theme
    $themePath = '/usr/share/icons/oxygen';

    try {
        $theme = new DesktopIconTheme($themePath);

        // From now on, you can fetch any icon information: for example,
        // the video-display icon is under the 'devices' context
        $iconInfo = $theme->getIconInfo(
            'video-display', IconInfo::ICON_SIZE_64);

        // From this point, you can get its URI
        $uri = $iconInfo->getURI();

    } catch (\InvalidArgumentException $e) {
        // Either theme or icon does not exists in the specified size
    }

Factory usage
-------------

This example demonstrate a more advanced yet normal runtime flow in an
environment where you'd handle more than one theme.

    use StockIcon\IconInfo;
    use StockIcon\Impl\DesktopThemeFactory;
    use StockIcon\Impl\DesktopIconTheme;
    use StockIcon\Toolkit\ToolkitHelper;

    // Assuming you're working on a *NIX box with some icons themes
    // already being there: this for sample purposes, but in real life
    // you're advised to download manually the icon themes you'd want
    // to use on your site somewhere else
    $themesPath = '/usr/share/icons';

    // First create a new Theme Factory (optional)
    $factory = new DesktopThemeFactory($themesPath);

    try {
        // Let's say you are using KDE, and want the oxygen icons theme
        $theme = $factory->getTheme('oxygen');

        // Let's say you want to display 64x64 icons, from here you have
        // three methods to fetch the size parameter
        // Manual:
        $size = "64x64";
        // By constant:
        $size = IconInfo::ICON_SIZE_64;
        // Using the toolkit helper (not recommended)
        $size = ToolkitHelper::getSizeFromDimensions(64, 64);

        // From now on, you can fetch any icon information: for example,
        // the video-display icon is under the 'devices' context
        $iconInfo = $theme->getIconInfo('video-display', $size);

        // From this point, you can get its URI
        $uri = $iconInfo->getURI();

        // Remember that you're getting an internal URI, which is absolute
        // in your filesystem, this won't be visible from the webroot: see
        // next chapters about helpers

    } catch (\InvalidArgumentException $e) {
        // Either theme or icon does not exists in the specified size
    }

File scanning and performance
=============================

Default implementations will scan the file system each time you will
instanciate either the factory or a theme: nobody would ever want to do that
under normal circumstances on a live site. In order to prevent this to happen
it is advised to write a discovery script, then write the found file map in
plain PHP in order to be able to use the _map based_ implementations.

For example, consider the following code:

    use StockIcon\Impl\DesktopThemeFactory;
    use StockIcon\Impl\MapBasedThemeFactory;

    // Try to reach a theme from some cache entry
    $themeMap = apc_fetch('known_themes');

    if ($themeMap) {
        $factory = new MapBasedThemeFactory($themeMap);
    } else {
        // We have no cache entry, instanciate the fallback
        $factory = new DesktopThemeFactory('/some/path');
        apc_store('known_themes', $factory->dumpMap());
    }

This is rudimentary code, but in the future some helpers using the proxy
pattern may rise.

The ideal situation would also make you use the same approach for the theme
themselves. @todo

