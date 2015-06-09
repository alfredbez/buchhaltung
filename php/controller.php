<?php

/********************************/
/*	Klassen einbinden 			
/*	eventuell mit Autoload ersetzen...
/**/
include_once(DIR_CLASSES . 'mysql.inc.php');	//	Mysql-Klasse
include_once(DIR_CLASSES . 'table.inc.php');	//	Tabellenklasse
include_once(DIR_CLASSES . 'forms.inc.php');	//	Formularklassen (Rechnungs und Angebotsformulare)
include_once(DIR_CLASSES . 'pdf.inc.php');		//	pdf-Klassen

/* PDF-Klassen einbinden */
include_once(DIR_CLASSES . 'rechnung.inc.php');
include_once(DIR_CLASSES . 'rechnung_print.inc.php');
include_once(DIR_CLASSES . 'angebot.inc.php');
include_once(DIR_CLASSES . 'angebot_print.inc.php');



$hiddenSite = false;

$site = 'start';	// default-Seite festlegen
/************************************/
/*	GET-Parameter 'site' pruefen	*/
/************************************/
if(isset($_GET['site'])){
	$s = $_GET['site'];	// GET-Parameter in $s speichern

	if(file_exists(DIR_SITES . $s . '.inc.php')){
		$site = $s;
	}
	else{
		$site = $errorsite;
		if(file_exists(DIR_SITES . '0' . $s . '.inc.php')){
			$site = $s;
			$hiddenSite = true;
		}
	}
}

/* Neue Navi */
$siteTitle = '';
include_once(DIR_PHP_SKRIPTS . 'navigation.inc.php');
foreach($sites as $k=>$v){
	if(!is_array($v)){
		$sites_new[] = array(
			'name' => $k,
			'file' => $v
		);
		if($site == $v){
			$siteTitle .= $k;
		}
	}
	else{
		$unterseiten = array();
		foreach($v as $kk=>$vv){
			$unterseiten[] = array(
				'name' => $kk,
				'file' => $vv
			);
			if($site == $vv){
				$siteTitle = $k . ' - ' . $kk;
			}
		}
		$sites_new[] = array(
			'name' => $k,
			'file' => $unterseiten
		);
	}
}
$smarty->assign('siteTitle',$siteTitle);
$smarty->assign('sites',$sites);
$smarty->assign('sites',$sites_new);
$smarty->assign('site',$site);
$smarty->assign('hiddenSite',$hiddenSite);
$smarty->assign('content','');

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

error_reporting(0);
error_reporting(-1);

/********************************************/
/*	Informationen aus Datenbank lesen		*/
/********************************************/
include_once(DIR_PHP_SKRIPTS . 'getInfoDB.inc.php');

if($hiddenSite){
	include_once(DIR_SITES . '0' . $site . '.inc.php');	//	Seite einbinden
}
else{
	include_once(DIR_SITES . $site . '.inc.php');	//	Seite einbinden
}
?>
