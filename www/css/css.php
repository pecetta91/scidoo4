<?php
header("Content-type: text/css; charset: UTF-8");
$scripts = $_GET['script'];
$contents = "";
foreach ($scripts as $script) {
	// validate the $script here to prevent inclusion of arbitrary files
	$contents .= file_get_contents($script);
}

// post processing here
// eg. jsmin, google closure, etc.
echo $contents;

?>