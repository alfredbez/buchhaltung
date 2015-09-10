<?php
$files = glob(__DIR__ . '/../../templates_c/*.tpl.php');
if ($_GET['action'] === 'check') {
    echo count($files);
}
if ($_GET['action'] === 'clear') {
    foreach ($files as $file) {
        unlink($file);
    }
}
