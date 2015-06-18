<?php

/* UTF-8 Header setzen, sonst werden Umlaute nicht richtig dargestellt */
header('Content-Type: text/html; charset=utf-8');

/* Google Klassen einbinden	*/
require_once DIR_LIBS.'google-calendar/Google_Client.php';
require_once DIR_LIBS.'google-calendar/contrib/Google_CalendarService.php';

/* Session starten */
session_start();

$client = new Google_Client();
$client->setApplicationName('Google Calendar PHP Starter Application');

/* Verbindungsdaten von Google eingeben */
// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {
    $client->setClientId('208320746933.apps.googleusercontent.com');
    $client->setClientSecret('lh78Obig1Jr2RcOuVB5_SgD3');
    $client->setRedirectUri('https://localhost/igor_neu/tools/google-calendar/google-api-php-client/examples/calendar/simple.php');
} else {
    $client->setClientId('573362229231.apps.googleusercontent.com');
    $client->setClientSecret('SGglnEhczGlrFBnu4k50Hg9M');
    $client->setRedirectUri('http://tischlerei-kebernik.de/neu/index.php?site=kalender');
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
    header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
}

/* Token an Klasse übergeben */
if (isset($_SESSION['token'])) {
    $client->setAccessToken($_SESSION['token']);
}
if ($client->getAccessToken()) {
    $_SESSION['token'] = $client->getAccessToken();

    $message = '';
    $messageType = '';

    /* Event eintragen */
    /* Formularfelder definieren */
    $fields = array(
    array(
        'name' => 'Terminname',
        'input' => array('name' => 'name'),
        ),
    array(
        'name' => 'Start-Datum',
        'input' => array(
            'name' => 'start-date',
            'type' => 'date',
            ),
        ),
    array(
        'name' => 'Start-Zeit',
        'input' => array(
            'name' => 'start-time',
            'type' => 'time',
            ),
        ),
    array(
        'name' => 'End-Datum',
        'input' => array(
            'name' => 'end-date',
            'type' => 'date',
            ),
        ),
    array(
        'name' => 'End-Zeit',
        'input' => array(
            'name' => 'end-time',
            'type' => 'time',
            ),
        ),
    );
    /* Formular auswerten */
    if (isset($_GET['eintragen'])) {
        $event = new Google_Event();
        /* Titel setzen	*/
        $event->setSummary($_POST['name']);
        $start = new Google_EventDateTime();
        /* Startzeit setzen	*/
        $start->setDateTime($_POST['start-date'].'T'.$_POST['start-time'].':00.000+01:00');
        $event->setStart($start);
        $end = new Google_EventDateTime();
        $end->setDateTime($_POST['end-date'].'T'.$_POST['end-time'].':00.000+01:00');
        $event->setEnd($end);
        /* Event erstellen	*/
        $createdEvent = $cal->events->insert($db_info['einstellungen']['kalender_id'], $event);
        if ($createdEvent !== null) {
            $message = 'Termin wurde erfolgreich eingetragen!';
            $messageType = 'success';
        }
    }
    /* Event löschen */
    if (isset($_GET['sure'])) {
        $cal->events->delete($db_info['einstellungen']['kalender_id'], $_GET['id']);
    }

    if ($message != '') {
        $smarty->assign('message', $message);
        $smarty->assign('messageType', $messageType);
    }
    $smarty->assign('fields', $fields);
} else {
    $authUrl = $client->createAuthUrl();
    print "<a class='login' href='$authUrl'>Mit Kalender verbinden</a>";
}
