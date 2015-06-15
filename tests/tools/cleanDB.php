<?php
function cleanDB()
{
  require 'php/mysql_config.php';
  $cmd = 'mysql -u ' . $mysqluser . ' -p' . $mysqlpwd . ' ' . $mysqldb . ' < db/test.sql';
  // mute command
  $cmd .= ' > /dev/null 2>&1';
  shell_exec($cmd);
}