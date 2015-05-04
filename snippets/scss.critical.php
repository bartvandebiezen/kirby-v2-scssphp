<?php

/**
 * Critical SCSS Snippet
 * @author    Bart van de Biezen <bart@bartvandebiezen.com>
 * @link      https://github.com/bartvandebiezen/kirby-v2-scssphp
 * @return    CSS
 * @version   0.8.5
 */

// Using realpath seems to work best in different situations.
$root = realpath(__DIR__ . '/../..');

// Get site URL. Used for checking and creating absolute paths in critical CSS.
$siteUrl = $site->url();

// Set file paths. Used for checking non critical SCSS and checking and compiling critical CSS for current template.
$template     = $page->template();
$criticalSCSS = $root . '/assets/scss/' . $template . '.critical.scss';
$criticalCSS  = $root . '/assets/css/'  . $template . '.critical.css';
$templateSCSS = $root . '/assets/scss/' . $template . '.scss';
$defaultSCSS  = $root . '/assets/scss/default.scss';

// Check if there is a critical SCSS. If not, use default. If template is default, skip check.
if ($template == 'default' or !file_exists($criticalSCSS)) {
	$criticalSCSS = $root . '/assets/scss/default.critical.scss';
	$criticalCSS  = $root . '/assets/css/default.critical.css';
}

// Check if there is a non critical template SCSS. If not, use default. If template is default, skip check.
if ($template == 'default' or !file_exists($templateSCSS)) {
	$templateSCSS = $defaultSCSS;
}

// Set variable for checking if critical CSS needs to be compiled. I use a variable instead of combined if statement to reduce the number of times PHP needs to access different files.
$needsCompiling = false;

// Compile critical SCSS when critical CSS doesn't exists.
if (!file_exists($criticalCSS)) {
	$needsCompiling = true;
} else {
	// Compile when template or default non critical SCSS is newer than critical CSS.
	$templateSCSSFileTime = filemtime($templateSCSS);
	$defaultSCSSFileTime = filemtime($defaultSCSS);
	$criticalCSSFileTime = filemtime($criticalCSS);
	if ($templateSCSSFileTime > $criticalCSSFileTime or $defaultSCSSFileTime > $criticalCSSFileTime) {
		$needsCompiling = true;
	} else {
		// Compile when critical SCSS is newer than critical CSS.
		$criticalSCSSFileTime = filemtime($criticalSCSS);
		if ($criticalSCSSFileTime > $criticalCSSFileTime) {
			$needsCompiling = true;
		} else {
			// Compile when critical CSS contains incorrect URLs, but only when it contains URLs.
			$criticalCSSContents = file_get_contents($criticalCSS);
			if (strpos($criticalCSSContents, 'url') and !strpos($criticalCSSContents, $siteUrl)) {
				$needsCompiling = true;
			}
		}
	}
}

if ($needsCompiling) {

	// Activate library.
	require_once $root . '/site/plugins/scssphp/scss.inc.php';
	$parser = new scssc();

	// Set compression provided by library.
	$parser->setFormatter('scss_formatter_compressed');

	// Set relative @import paths.
	$importPath = $root . '/assets/scss';
	$parser->addImportPath($importPath);

	// Put SCSS in buffer.
	$buffer = file_get_contents($criticalSCSS);

	// Compile SCSS and place CSS in buffer.
	$buffer = $parser->compile($buffer);

	// Minify CSS even further.
	require_once $root . '/site/plugins/scssphp/minify.php';
	$buffer = minifyCSS($buffer);

	// Make relative URLs absolute.
	// CRED: <http://stackoverflow.com/questions/9798378/preg-replace-regex-to-match-relative-url-paths-in-css-files>.
	$compiledPath = $siteUrl . '/assets/css/';
	$relativePath = '#url\((?!\s*[\'"]?(?:https?:)?//)\s*([\'"])?#';
	$absolutePath = "url($1{$compiledPath}";
	$buffer = preg_replace($relativePath, $absolutePath, $buffer);

	// Update critical CSS.
	file_put_contents($criticalCSS, $buffer);
}

?>
<style type="text/css"><?php echo file_get_contents($criticalCSS); ?></style>
