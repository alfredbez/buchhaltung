<?php

require_once 'vendor/autoload.php';

define('ROOT_DIR', getcwd() . '/');

// start with clean database
require_once 'cleanDB.php';
cleanDB();