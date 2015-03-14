<?php

$root = realpath(__DIR__ . "/../..");
$sourceFile = $root . "/assets/scss/style.scss";
$compiledFile = $root . "/assets/css/style.css";
$importPath = $root . "/assets/scss";

if (filemtime($sourceFile) > filemtime($compiledFile)) {
	require "site/plugins/scssphp/scss.inc.php";
	$scss = new scssc();
	$scss->setFormatter("scss_formatter_compressed");
	$scss->addImportPath($importPath);

	$scssIn = file_get_contents($sourceFile);
	$cssOut = $scss->compile($scssIn);
	file_put_contents($compiledFile, $cssOut);
}

?>
<link rel="stylesheet" href="<?php echo url('assets/css/style.css') ?>">
