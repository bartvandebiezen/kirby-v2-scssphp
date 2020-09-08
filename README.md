# SCSSPHP Plugin for Kirby 3

This is a preprocessor for SCSS files. Built using the [scssphp library](https://github.com/scssphp/scssphp) by Leaf Corcoran. This Kirby 3 plugin will automatically process SCSS files when changed. As an option, you can use this plugin to create 'critical CSS'.

## Installing SCSS

1. Copy folder ‘scssphp’ inside ‘plugins’ to Kirby’s plugins folder.
2. Copy file ‘scss.php’ inside ‘snippets’ to Kirby’s snippets folder.
3. Call the SCSS snippet with `<?php snippet('scss') ?>` in your HTML head.
4. Create a folder ‘scss’ inside Kirby’s assets folder.
5. Create a file ‘default.scss’ and place it inside ‘assets/scss’.
6. Make sure the folder ‘assets/css’ exists on your server.
7. Add `'scssNestedCheck' => true` to the config of your dev environment. [Read more about multi environment setup for Kirby](https://getkirby.com/docs/guide/configuration#multi-environment-setup).

## Using SCSS plugin

After installing this plugin, 'assets/css/default.css' will be overwritten automatically. Make sure you backup your original CSS.

It is possible to create different SCSSs for each Kirby template. Just use the name of your template file for the SCSS file (e.g. 'article.scss' for 'templates/article.php'), and place it in 'assets/scss'. If no SCSS file for a template can be found, 'default.scss' will be used.

## Critical SCSS (a.k.a. above the fold)

If you would like to improve the performance of your website, you can use the 'scss.critical.php' in combination with the 'scss.php' snippet. This part of the plugin is optional and still experimental. Using critical CSS means inlining any CSS that is used to render content directly visible when you open a page. Before you start using critical CSS, my advice is to read more about this concept on [CSS Tricks](https://css-tricks.com/authoring-critical-fold-css/) or [Google PageSpeed Insights](https://developers.google.com/speed/docs/insights/PrioritizeVisibleContent).

### Installing Critical SCSS

1. Follow the instructions for installing SCSS.
2. Copy file ‘scss.critical.php’ inside ‘snippets’ to Kirby’s snippets folder.
3. Call the critical snippet with <?php snippet('scss.critical') ?> in your HTML head.
4. Call the SCSS snippet with <?php snippet('scss') ?> below your page footer instead of your HTML head.
5. Create a file ‘default.critical.scss’ and place it inside ‘assets/scss’.

### Using Critical SCSS

Everything in 'default.critical.scss' will be compiled and placed in your HTML head. All relative URLs in your critical SCSS will be automatically converted to the correct absolute URLs in your critical CSS.

This plugin does not (yet) detect which CSS should be placed in your critical CSS. Therefor you need to manually place or import SCSS that will affect everything that has a large chance to be directly visible 'above the fold' when loaded. Do not forget to include your SCSS utilities (e.g. mixins) and settings (i.e. global variables) in your critical SCSS file.

The critical CSS will also be updated after you upload it to another server. This is necessary because the absolute URLs in your critical CSS need to be updated.

It is possible to create different critical SCSSs for each Kirby template. Just use the name of your template file for the critical SCSS file (e.g. 'article.critical.scss' for 'templates/article.php'), and place it in 'assets/scss'. If no critical SCSS file for a template can be found, 'default.critical.scss' will be used.

My advice is not to remove any SCSS from your main SCSS file 'default.scss'.

## Compatibility

The scssphp library implements SCSS 3.2.12. It does not support the older SASS syntax or SCSS 3.3 features. As a result Bourbon 4.0 isn't yet supported, use [Bourbon 3.2.x](https://github.com/thoughtbot/bourbon/tree/v3.2.4), which contains many of the 4.0 features, instead.
