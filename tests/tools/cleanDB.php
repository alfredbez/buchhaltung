<?php
function cleanDB()
{

  if (getenv('mysqlhost') || file_exists(ROOT_DIR.'.test.lock')) {
      $mysqlhost = getenv('mysqlhost') ?: 'localhost';
      $mysqluser = getenv('mysqluser') ?: 'dbuser';
      $mysqlpwd = getenv('mysqlpwd') ?: '123';
      $mysqldb = getenv('mysqldb') ?: 'buchhaltung_test';
  }

  $cmd = 'mysql -u ' . $mysqluser . ' -p' . $mysqlpwd . ' ' . $mysqldb . ' < db/test.sql';
  // mute command
  // $cmd .= ' > /dev/null 2>&1';
  shell_exec($cmd);
}
