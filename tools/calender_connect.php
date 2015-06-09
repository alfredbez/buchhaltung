<?php

/* 
Mail    igor.kebernik@googlemail.com
PW      inatschka
*/

/* UTF-8 Header setzen, sonst werden Umlaute nicht richtig dargestellt */
header("Content-Type: text/html; charset=utf-8");

/* Google Klassen einbinden	*/
require_once '../libs/google-calendar/Google_Client.php';
require_once '../libs/google-calendar/contrib/Google_CalendarService.php';

/* Session starten */
session_start();


$client = new Google_Client();
$client->setApplicationName("Google Calendar PHP Starter Application");

/* Verbindungsdaten von Google eingeben */
// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
if( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1'){
	$client->setClientId('208320746933.apps.googleusercontent.com');
	$client->setClientSecret('lh78Obig1Jr2RcOuVB5_SgD3');
	$client->setRedirectUri('https://localhost/igor_neu/tools/google-calendar/google-api-php-client/examples/calendar/simple.php');
}
else{
	$client->setClientId('573362229231.apps.googleusercontent.com');
	$client->setClientSecret('SGglnEhczGlrFBnu4k50Hg9M');
	$client->setRedirectUri('http://tischlerei-kebernik.de/neu/tools/calender_connect.php');
}
// $client->setDeveloperKey('insert_your_developer_key');
$cal = new Google_CalendarService($client);

/* Bei Bedarf ausloggen */
if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
}

/* Bei Google einloggen */
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

/* Token an Klasse übergeben */
if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}
if ($client->getAccessToken()) {
	$_SESSION['token'] = $client->getAccessToken();

	/* Kalendar auflisten */

  $calList = $cal->calendarList->listCalendarList();
  print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Verbinden</a>";
}