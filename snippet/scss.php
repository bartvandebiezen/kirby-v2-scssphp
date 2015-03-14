<?php

// Using 'realpath' seems to work best in different situations.
$root = realpath(__DIR__ . "/../..");

// Your main SCSS file.
$sourceFile = $root . "/assets/scss/style.scss";

// Your final CSS file.
$compiledFile = $root . "/assets/css/style.css";

// Compile only when needed.
if (filemtime($sourceFile) > filemtime($compiledFile)) {

	// Activate SCSSPHP plugin.
	require "site/plugins/scssphp/scss.inc.php";
	$scss = new scssc();

	// Use compression provided by SCSSPHP plugin.
	$scss->setFormatter("scss_formatter_compressed");

	// Make relative @import paths in your SCSS files work.
	$importPath = $root . "/assets/scss";
	$scss->addImportPath($importPath);

	// Place SCSS file in buffer.
	$buffer = file_get_contents($sourceFile);

	// Compile content in buffer.
	$buffer = $scss->compile($buffer);

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

	// Remove last semi-colon within a CSS rule.
	$buffer = str_replace(';}', '}', $buffer);

	// Update your CSS file.
	file_put_contents($compiledFile, $buffer);
}

?>
<link rel="stylesheet" href="<?php echo url('assets/css/style.css') ?>">
