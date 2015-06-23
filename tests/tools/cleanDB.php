<?php
function cleanDB()
{

  if(getenv('TRAVIS'))
  {
    $cmd = 'mysql -u root --password="" buchhaltung_test < db/test.sql';
    print_r($cmd);
    print_r(shell_exec($cmd));
    print_r("\n");
    die('TRAVIS and die');
  }
  else
  {
    $mysqlhost = getenv('mysqlhost') ?: '127.0.0.1';
    $mysqluser = getenv('mysqluser') ?: 'root';
    $mysqlpwd = getenv('mysqlpwd') ?: '123';
    $mysqldb = getenv('mysqldb') ?: 'buchhaltung_test';
  }

  $cmd = 'mysql -u ' . $mysqluser . ' -p' . $mysqlpwd . ' ' . $mysqldb . ' < db/test.sql';
  // mute command
  // $cmd .= ' > /dev/null 2>&1';
  die(var_dump(shell_exec($cmd)));
}
