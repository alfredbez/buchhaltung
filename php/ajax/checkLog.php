<?php
function countLines($file)
{
    if (!file_exists($file)) {
        return 0;
    }
    $linecount = 0;
    $handle = fopen($file, "r");
    while (!feof($handle)) {
        $line = fgets($handle);
        $linecount++;
    }
    fclose($handle);

    return $linecount;
}

$path = __DIR__ . '/../../';
if ($_GET['type'] === 'error') {
    echo countLines($path . 'php_error_log');
} else {
    echo countLines($path . 'log');
}
