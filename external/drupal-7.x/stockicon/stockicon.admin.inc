<?php

/**
 * @file
 * Stock icon admin UI.
 */

use StockIcon\ThemeFactory;
use StockIcon\IconInfo;
use StockIcon\IconTheme;
use StockIcon\Toolkit\ToolkitHelper;

/**
 * Known stock icons consumers list.
 */
function stockicon_admin_appearance($form, &$form_state) {

  $options = array();
  foreach (stockicon_theme_factory_get()->getAllThemeNames() as $name) {
    $options[$name] = $name;
  }

  $form['stockicon_default_theme'] = array(
    '#type'          => 'select',
    '#title'         => t("Default theme"),
    '#options'       => $options,
    '#default_value' => variable_get('stockicon_default_theme'),
  );

  return system_settings_form($form);
}

/**
 * Known themes list.
 */
function stockicon_admin_appearance_theme() {
  $factory = stockicon_theme_factory_get();

  $header = array(t("Name"), t("Preview"), t("Operations"));
  $rows[] = array();

  $prefered_size = IconInfo::ICON_SIZE_24;
  $display_icons = 5;

  foreach ($factory->getAllThemeNames() as $theme_name) {
    $row = array();

    $theme = $factory->getTheme($theme_name);
    $row[] = check_plain($theme_name);

    $output = '';
    $i = 0;
    foreach ($theme->getContextLists() as $context) {
      foreach ($theme->getIconList($context) as $icon_name) {
        if ($uri = $theme->renderIcon($icon_name, $prefered_size)) {
          $output .= '<img src="' . file_create_url($uri) . '"/>';
          if (++$i >= $display_icons) {
            break 2;
          }
        }
      }
    }
    $row[] = $output;

    $links = array();
    $links[] = array(
      'title' => t("View all"),
      'href'  => 'admin/appearance/stockicon-theme/' . $theme_name,
    );

    $row[] = theme('links', array('links'  => $links));

    $rows[] = $row;
  }

  return theme('table', array(
    'header' => $header,
    'rows'   => $rows,
  ));
}

/**
 * Theme preview.
 */
function stockicon_admin_appearance_theme_preview(IconTheme $theme) {
  $build = array();

  $prefered_size = IconInfo::ICON_SIZE_32;

  foreach ($theme->getContextLists() as $context) {
    $output = '';
    foreach ($theme->getIconList($context) as $icon_name) {
      $sizes = $theme->getIconSizes($icon_name);
      if (in_array($prefered_size, $sizes) || in_array(IconInfo::SCALABLE, $sizes)) {
        $size = $prefered_size;
      } else {
        $size = $sizes[0];
      }

      $attributes = array(
        'class' => 'stockicon-preview',
        'title' => sprintf('%s/%s - %s', $context, $icon_name, $size),
      );

      if ($uri = $theme->renderIcon($icon_name, $size)) {
        list($x, $y) = ToolkitHelper::getDimensionsFromSize($size);
        $attributes['src'] = file_create_url($uri);
        $attributes['width'] = $x;
        $attributes['height'] = $y;
        $attributes['style'] = 'overflow: hidden;';
        $output .= '<img ' . drupal_attributes($attributes) . '/>';
      } else {
        $attributes['class'] = 'stockicon-broken';
        $output .= '<span ' . drupal_attributes($attributes) . '></span>';
      }
    }

    $build[] = array(
      'title' => array(
        '#prefix' => '<h2>',
        '#suffix' => '</h2>',
        '#markup' => check_plain($context),
      ),
      'table' => array(
        '#markup' => $output,
      ),
    );
  }

  return $build;
}

function stockicon_admin_appearance_theme_preview_table(IconTheme $theme) {
  $build = array();

  $prefered_size = IconInfo::ICON_SIZE_32;

  foreach ($theme->getContextLists() as $context) {
    $rows = array();

    foreach ($theme->getIconList($context) as $icon_name) {
      $row   = array();
      $sizes = $theme->getIconSizes($icon_name);

      if (in_array($prefered_size, $sizes) || in_array(IconInfo::SCALABLE, $sizes)) {
        $size = $prefered_size;
      } else {
        $size = $sizes[0];
      }

      $attributes = array(
        'class' => 'stockicon-preview',
        'title' => sprintf('%s/%s - %s', $context, $icon_name, $size),
      );

      $row[] = check_plain($icon_name);

      if ($uri = $theme->renderIcon($icon_name, $size)) {
        list($x, $y) = ToolkitHelper::getDimensionsFromSize($size);
        $attributes['src'] = file_create_url($uri);
        $attributes['width'] = $x;
        $attributes['height'] = $y;
        $attributes['style'] = 'overflow: hidden;';
        $row[] = '<img ' . drupal_attributes($attributes) . '/>';
      } else {
        $attributes['class'] = 'stockicon-broken';
        $row[] = '<span ' . drupal_attributes($attributes) . '></span>';
      }

      $rows[] = $row;
    }

    $build[] = array(
      'title' => array(
        '#prefix' => '<h2>',
        '#suffix' => '</h2>',
        '#markup' => check_plain($context),
      ),
      'table' => array(
        '#markup' => theme('table', array('rows' => $rows)),
      ),
    );
  }

  return $build;  
}
