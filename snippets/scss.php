<?php

// Using 'realpath' seems to work best in different situations.
$root = realpath(__DIR__ . '/../..');

// For checking and creating SCSS file for the current template.
$templateName = $page->template();
$sourceTemplateFile = $root . '/assets/scss/' . $templateName . '.scss';
$compiledTemplateFile = $root . '/assets/css/' . $templateName . '.css';

if ( $templateName != 'default' and file_exists($sourceTemplateFile) ) {
	$sourceFile = $sourceTemplateFile;
	$compiledFile = $compiledTemplateFile;
	$compiledFileKirbyPath = 'assets/css/' . $templateName . '.css';
} else {
	$sourceFile = $root . '/assets/scss/default.scss';
	$compiledFile = $root . '/assets/css/default.css';
	$compiledFileKirbyPath = 'assets/css/default.css';
}

// Compile when needed.
if ( !file_exists($compiledFile) or filemtime($sourceFile) > filemtime($compiledFile) ) {

	// Activate library.
	require "site/plugins/scssphp/scss.inc.php";
	$parser = new scssc();

	// Use compression provided by library.
	$parser->setFormatter("scss_formatter_compressed");

	// Make relative @import paths in your SCSS files work.
	$importPath = $root . "/assets/scss";
	$parser->addImportPath($importPath);

	// Place SCSS file in buffer.
	$buffer = file_get_contents($sourceFile);

	// Compile content in buffer.
	$buffer = $parser->compile($buffer);

	// Minify the CSS even further.
	require 'site/plugins/scssphp/minify.php';
	$buffer = minifyCSS($buffer);

	// Update CSS file.
	file_put_contents($compiledFile, $buffer);
}

?>
<link rel="stylesheet" href="<?php echo url($compiledFileKirbyPath) ?>">
