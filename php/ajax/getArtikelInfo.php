<?php
include_once('../classes/mysql.inc.php');
include_once('../../config.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(0);

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

/* ID und HerstellerID extrahieren */
$id           = mysql_real_escape_string(extractArtikelId($_GET['id'])[0]);
$herstellerid = mysql_real_escape_string(extractArtikelId($_GET['id'])[1]);

/* Informationen aus der Hersteller Tabelle anzeigen */
$db->query("select * from artikel where id=" . $id . " && herstellerID='" . $herstellerid . "'");
if($db->count()>0){
	/* Damit man alle Datenbankbefehle mit AJAX leicht handhaben kann, wird die ID gespeichert */
	echo '<div id="id" class="hide">' . $id . '</div>';
	echo '<div id="herstellerid" class="hide">' . $herstellerid . '</div>';
	/* Löschen Button einbauen */
	echo '<button class="btn btn-danger delete-artikel"><i class="icon-trash icon-white"></i> Diesen Artikel löschen</button><br /><br />';
	echo '<table id="artikelInfo" class="table table-striped table-bordered">';
	$res = $db->fetchRow();
	foreach ($res as $key => $value) {
		echo "<tr><td>" . ucfirst($key) . "</td><td class=\"editArtikel\">$value</td></tr>";
	}
	echo '</table>';
}

?>