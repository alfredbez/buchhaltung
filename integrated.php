<?php
return [
  "baseUrl"   => "http://127.0.0.1/igor",
  "pdo"       => [
    "connection"  => "mysql:host=" . getenv('mysqlhost') . ";dbname=" . getenv('mysqldb'),
    "username"    => getenv('mysqluser'),
    "password"    => getenv('mysqlpwd')
  ]
];