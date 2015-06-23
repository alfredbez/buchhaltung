<?php
function cleanDB()
{

  if (getenv('mysqlhost') || file_exists(ROOT_DIR.'.test.lock')) {
      $mysqlhost = getenv('mysqlhost') ?: '127.0.0.1';
      $mysqluser = getenv('mysqluser') ?: 'root';
      $mysqlpwd = getenv('mysqlpwd') ?: '';
      $mysqldb = getenv('mysqldb') ?: 'buchhaltung_test';
  }

  if($mysqlpwd === '')
  {
    $passwordFlag = '--password=""';
  }
  $passwordFlag = $passwordFlag ?: '-p' . $mysqlpwd;
  $cmd = 'mysql -u ' . $mysqluser . ' ' . $passwordFlag . ' ' . $mysqldb . ' < db/test.sql';
  // mute command
  // $cmd .= ' > /dev/null 2>&1';
  echo shell_exec($cmd);
}
