<?php

/* UTF-8 Header setzen, sonst werden Umlaute nicht richtig dargestellt */
header("Content-Type: text/html; charset=utf-8");

include_once('../config.php');
include_once('controller.php');

/* Google Klassen einbinden	*/
require_once DIR_LIBS . 'google-calendar/Google_Client.php';
require_once DIR_LIBS . 'google-calendar/contrib/Google_CalendarService.php';

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

	/* persoönliche Events auslesen */ 
	$options = array(
		'timeMin' => '2013-06-01T12:30:00+02:00',
		'fields' => 'items(id,end,start,summary)'
		);
	$events = $cal->events->listEvents($db_info['einstellungen']['kalender_id'],$options);
	foreach($events['items'] as $key=>$event){
		if(isset($event['start']['date'])){
			$start = new DateTime($event['start']['date']);
			$start = $start->getTimestamp();
		}
		else if(isset($event['start']['dateTime'])){
			$start = new DateTime($event['start']['dateTime']);
			$start = $start->getTimestamp();
		}
		else{
			$start = '?';
		}
		if(isset($event['end']['date'])){
			$end = new DateTime($event['end']['date']);
			$end = $end->getTimestamp();
		}
		else if(isset($event['end']['dateTime'])){
			$end = new DateTime($event['end']['dateTime']);
			$end = $end->getTimestamp();
		}
		else{
			$end = '?';
		}
		$events['items'][$key]['start'] = ($start * 1000)+100;
		$events['items'][$key]['end'] = ($end * 1000)-1000;
		$events['items'][$key]['title'] = $event['summary'];
		$events['items'][$key]['class'] = 'event-info';
		$events['items'][$key]['url'] = 'index.php?site=kalender&delete=true&id=' . $event['id'];
	}

	/* deutsche feiertage auslesen */
	$de_events = $cal->events->listEvents('de.german#holiday@group.v.calendar.google.com');
	foreach ($de_events['items'] as $key => $event) {
		$id = uniqid();

		$start = new DateTime($event['start']['date']);
		$start = $start->getTimestamp();
		$end = new DateTime($event['end']['date']);
		$end = $end->getTimestamp();

		$events['items'][$id]['start'] = ($start * 1000)+100;
		$events['items'][$id]['end'] = ($end * 1000)-100;
		$events['items'][$id]['title'] = $event['summary'];
		$events['items'][$id]['class'] = 'event-inverse';
	}

	$json = array(
		'success' => 1,
		'result' => $events['items']
		);
	echo json_encode($json);
}

?>