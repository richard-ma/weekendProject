<?php

$options = array(
    'remove_whitespace' => FALSE,
    'remove_comment' => FALSE,
    'ob_int' => FALSE,
    'ob_str' => FALSE,
    'ob_str_method' => -1,
    'ob_var' => TRUE,
);

require_once "./dealer.php";
ob_implicit_flush(TRUE);

$inputDir = $argv[1];
$outputDir = $argv[2];

if ($argc < 3) {
	echo "PLEASE give inputDir and outputDir";
	echo "php " . $argv[0] . "/path/to/inputDir /path/to/outputDir";
}

if (file_exists($inputDir) == FALSE) {
	echo "PLEASE check " . $inputDir . " exists or not.";
}

if (!file_exists($outputDir)) {
    mkdir($outputDir);
}

$files = glob($inputDir . '/*.php');
foreach ($files as $file) {
    $target_file = $file;
	$target_file = str_replace($inputDir, $outputDir, $target_file);
    obfuscatePHPFile($file, $target_file, $options);

    $old_output = $output = array();
    // run encoded & old script
    exec('php -d error_reporting=0 "' . $target_file . '"', $output);
    exec('php -d error_reporting=0 "' . $file . '"', $old_output);

    $output     = implode("\n", $output);
    $old_output = implode("\n", $old_output);
    $old_output = strtr($old_output, [realpath($file) => realpath($target_file)]);
    // compare result
    if ($old_output != $output) {
        echo str_repeat('===', 5);
        echo $file;
        echo str_repeat('===', 5);
        echo "\r\nold=", trim($old_output), "\r\n";
        echo str_repeat('===', 5);
        echo "\r\nnew=", trim($output), "\r\n";
    }
}