Stock icon
==========

Icon set/Stock icons handling API for reusing common UNIX Desktop icons themes
easily.

Sample (almost complete) usage
------------------------------

This example demonstrate the normal runtime flow.

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
