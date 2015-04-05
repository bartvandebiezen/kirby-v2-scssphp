<?php

// Using 'realpath' seems to work best in different situations.
$root = realpath(__DIR__ . '/../..');

// For checking and creating absolute paths in your inlined CSS.
$siteUrl = $site->url();

// For checking and creating critical SCSS file for the current template.
$templateName = $page->template();
$sourceTemplateFile = $root . '/assets/scss/' . $templateName . '.critical.scss';
$compiledTemplateFile = $root . '/assets/css/' . $templateName . '.critical.css';

if ( $templateName != 'default' and file_exists($sourceTemplateFile) ) {
	$sourceFile = $sourceTemplateFile;
	$compiledFile = $compiledTemplateFile;
} else {
	$sourceFile = $root . '/assets/scss/default.critical.scss';
	$compiledFile = $root . '/assets/css/default.critical.css';
}

// Compile when: (1) the CSS file doesn't exist; (2) the source file is newer; (3) Absolute URLs from the current server cannot be found.
if ( !file_exists($compiledFile) or filemtime($sourceFile) > filemtime($compiledFile) or !strpos(file_get_contents($compiledFile), $siteUrl) ) {

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

	// Remove all CSS comments.
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

	// Remove lines and tabs.
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);

	// Remove unnecessary spaces.
	$buffer = preg_replace('!\s+!', ' ', $buffer);
	$buffer = str_replace(': ', ':', $buffer);
	$buffer = str_replace('} ', '}', $buffer);
	$buffer = str_replace('{ ', '{', $buffer);
	$buffer = str_replace('; ', ';', $buffer);
	$buffer = str_replace(', ', ',', $buffer);
	$buffer = str_replace(' }', '}', $buffer);
	$buffer = str_replace(' {', '{', $buffer);
	$buffer = str_replace(' )', ')', $buffer);
	$buffer = str_replace(' (', '(', $buffer);
	$buffer = str_replace(') ', ')', $buffer);
	$buffer = str_replace('( ', '(', $buffer);
	$buffer = str_replace(' ;', ';', $buffer);
	$buffer = str_replace(' ,', ',', $buffer);

	// Fix spacing in media queries.
	$buffer = str_replace('and(', 'and (', $buffer);
	$buffer = str_replace(')and', ') and', $buffer);

	// Remove last semi-colon within a CSS rule.
	$buffer = str_replace(';}', '}', $buffer);

	// Make relative URLs absolute
	// CRED: <http://stackoverflow.com/questions/9798378/preg-replace-regex-to-match-relative-url-paths-in-css-files>
	$compiledPath = $siteUrl . '/assets/css/';
	$relativePath = '#url\((?!\s*[\'"]?(?:https?:)?//)\s*([\'"])?#';
	$absolutePath = "url($1{$compiledPath}";
	$buffer = preg_replace($relativePath, $absolutePath, $buffer);

	// Update critical CSS file.
	file_put_contents($compiledFile, $buffer);
}

?>
<style type="text/css"><?php echo file_get_contents($compiledFile); ?> </style>
