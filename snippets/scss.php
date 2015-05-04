<?php

/**
 * SCSS Snippet
 * @author    Bart van de Biezen <bart@bartvandebiezen.com>
 * @link      https://github.com/bartvandebiezen/kirby-v2-scssphp
 * @return    CSS and HTML
 * @version   0.9.1
 */

// Using realpath seems to work best in different situations.
$root = realpath(__DIR__ . '/../..');

// Set file paths. Used for checking and updating CSS file for current template.
$template     = $page->template();
$SCSS         = $root . '/assets/scss/' . $template . '.scss';
$CSS          = $root . '/assets/css/'  . $template . '.css';
$CSSKirbyPath = 'assets/css/' . $template . '.css';

// Set default SCSS if there is no SCSS for current template. If template is default, skip check.
if ($template = 'default' or !file_exists($SCSS)) {
	$SCSS         = $root . '/assets/scss/default.scss';
	$CSS          = $root . '/assets/css/default.css';
	$CSSKirbyPath = 'assets/css/default.css';
}

// Get file modification times. Used for checking if update is required and as version number for caching.
$SCSSFileTime = filemtime($SCSS);
$CSSFileTime = filemtime($CSS);

// Update CSS when needed.
if (!file_exists($CSS) or $SCSSFileTime > $CSSFileTime ) {

	// Activate library.
	require_once $root . '/site/plugins/scssphp/scss.inc.php';
	$parser = new scssc();

	// Setting compression provided by library.
	$parser->setFormatter('scss_formatter_compressed');

	// Setting relative @import paths.
	$importPath = $root . '/assets/scss';
	$parser->addImportPath($importPath);

	// Place SCSS file in buffer.
	$buffer = file_get_contents($SCSS);

	// Compile content in buffer.
	$buffer = $parser->compile($buffer);

	// Minify the CSS even further.
	require_once $root . '/site/plugins/scssphp/minify.php';
	$buffer = minifyCSS($buffer);

	// Update CSS file.
	file_put_contents($CSS, $buffer);
}

?>
<link rel="stylesheet" property="stylesheet" href="<?php echo url($CSSKirbyPath) ?>?version=<?php echo $CSSFileTime ?>">
