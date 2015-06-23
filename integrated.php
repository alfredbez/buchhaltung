<?php

$baseUrl = 'http://127.0.0.1/igor';
if(getenv('TRAVIS'))
{
  $baseUrl = 'http://localhost:8000';
}

return [
  'baseUrl' => $baseUrl,
  'pdo' => [
    'connection' => 'mysql:host='.getenv('mysqlhost').';dbname='.getenv('mysqldb'),
    'username' => getenv('mysqluser'),
    'password' => getenv('mysqlpwd'),
  ],
];
