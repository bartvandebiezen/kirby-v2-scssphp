# SCSSPHP Plugin for Kirby 2

This is a preprocessor for SCSS files. Built using the [scssphp library](https://github.com/leafo/scssphp) by Leaf Corcoran. This Kirby v2 plugin will automatically process SCSS files when changed.

1. Copy folder ‘scssphp’ inside ‘plugins’ to Kirby’s plugin folder.
2. Copy file ‘scss.php’ inside ‘snippet’ to Kirby’s snippet folder.
3. Call the snippet with <?php snippet('scss') ?> in your HTML head.
4. Create a folder ‘scss’ inside Kirby’s assets folder.
5. Create a file ‘style.scss’ and place it inside ‘assets/scss’.
6. Make sure 'css/style.css’ exists inside Kirby's assets folder.
