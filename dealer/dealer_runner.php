<?php

$options = array(
    'remove_whitespace' => TRUE,
    'remove_comment' => TRUE,
    'ob_var' => TRUE,
);

require_once "./dealer.php";
ob_implicit_flush(TRUE);

$inputFile = $argv[1];
$outputFile = $argv[2];

if ($argc < 3) {
	echo "PLEASE give inputFile and outputFile";
	echo "php " . $argv[0] . "/path/to/inputFile /path/to/outputFile";
}

if (file_exists($inputFile) == FALSE) {
	echo "PLEASE check " . $inputFile . " exists or not.";
}

if (isset($options)) {
    obfuscatePHPFile($inputFile, $outputFile, $options);
} else {
    obfuscatePHPFile($inputFile, $outputFile);
}