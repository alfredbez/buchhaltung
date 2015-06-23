<?php
function cleanDB()
{

  if(getenv('TRAVIS'))
  {
    $cmd = 'mysql -h 127.0.0.1 -u root --password="" buchhaltung_test < db/test.sql';
  }
  else
  {
    $mysqlhost = getenv('mysqlhost') ?: '127.0.0.1';
    $mysqluser = getenv('mysqluser') ?: 'root';
    $mysqlpwd = getenv('mysqlpwd') ?: '123';
    $mysqldb = getenv('mysqldb') ?: 'buchhaltung_test';
    $cmd = 'mysql -u ' . $mysqluser . ' -p' . $mysqlpwd . ' ' . $mysqldb . ' < db/test.sql';
  }

  // mute command
  $cmd .= ' > /dev/null 2>&1';
  shell_exec($cmd);
}
