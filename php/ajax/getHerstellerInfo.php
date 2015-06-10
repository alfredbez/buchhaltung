<?php
include_once('../classes/mysql.inc.php');
include_once('../../config.php');

//

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$id=mysql_real_escape_string($_GET['id']);

/* Informationen aus der Hersteller Tabelle anzeigen */
$db->query("select * from hersteller where id='" . $id . "'");
if($db->count()>0){
	/* Damit man alle Datenbankbefehle mit AJAX leicht handhaben kann, wird die ID gespeichert */
	echo '<div id="id" class="hide">' . $id . '</div>';
	/* Löschen Button einbauen */
	echo '<button class="btn btn-danger delete-hersteller"><i class="icon-trash icon-white"></i> Diesen Hersteller löschen</button><br /><br />';
	echo '<table id="herstellerInfo" class="table table-striped table-bordered">';
	$res = $db->fetchRow();
	foreach ($res as $key => $value) {
		echo "<tr><td>" . ucfirst($key) . "</td><td class=\"editHersteller\">$value</td></tr>";
	}
	echo '</table>';
}
/* Informationen aus der Vertreter Tabelle anzeigen */
$db->query("select * from vertreter where herstellerID='" . $id . "'");
if($db->count()>0){
	echo '<h4>Vertreter</h4>';
	while($res = $db->fetchRow()){
		/* Löschen Button einbauen */
		echo '<button class="btn btn-danger delete-vertreter" data-id="' . $res["id"] . '"><i class="icon-trash icon-white"></i> Diesen Vertreter löschen</button><br /><br />';
		echo '<table class="table table-striped table-bordered" data-id="' . $res["id"] . '">';
		foreach ($res as $key => $value) {
			echo "<tr><td>" . ucfirst($key) . "</td><td class=\"editVertreter\">$value</td></tr>";
		}
		echo '</table>';
	}
}

?>