<?php
/**
 * Erzeugt eine Meldung, die später mit Bootstrap gestylt wird
 * @param  string $string 	Text der Meldung
 * @param   $type 	NULL 		=>	Hinweis
 * 	                        'error' 	=>	Fehlermeldung
 * 	                        'success' 	=>	Erfolgsmeldung
 * 	                        'info' 		=>	Infomeldung
 * @return string 	gestylte Meldung
 */
function alert($string,$type=NULL){
	$return='<div class="alert';
	if($type){
		$return.=' alert-'.$type;
	}
	$return.='">'.$string.'</div>';
	echo $return;
}
/**
 * Erzeugt ein Tabellenfeld
 * @param  string $string Inhalt des Tabellenfeldes
 * @return string         Tabellenfeld mit Inhalt
 */
function td($string){
	echo "<td>$string</td>";
}
/**
 * Wandelt eine Zahl von der Komma- in die Punktschreibweise
 * @param  [type] $number Zahl in Kommaschreibweise
 * @return [type]         Zahl in Punktschreibweise
 */
function c2d($number){
	return str_replace(',','.',$number);
}
/**
 * Wandelt eine Zahl von der Punkt- in die Kommaschreibweise
 * @param  [type] $number Zahl in Punktschreibweise
 * @return [type]         Zahl in Kommaschreibweise
 */
function d2c($number){
	return str_replace('.',',',$number);
}
/**
 * Extrahiert die id und die HerstellerID aus einer Kombinierten ID
 *  Beispiel:
 *  		kombinierte ID  : cor42
 *  		ID 				: 42
 *  		herstellerID 	: cor
 * @param  [type] $s kombinierte ID
 * @return array    Gibt ein Array zurück, wobei der erste Wert die ID angibt und der zweite die herstellerID
 */
function extractArtikelId($s){
	/* Position der ersten Zahl ermitteln */
	$chars = str_split($s);
	$position = false;
	for ($i=0; $i < count($chars); $i++) {
		if($position !== false){break;}
		if(is_numeric($chars[$i])){$position = $i;}
	}

	/* ID und HerstellerID extrahieren */
	$id           = substr($s,$position);
	$herstellerid = substr($s,0,$position);
	return array($id,$herstellerid);
}

/**
 * Prüft ob das Feld $field in der Datenbank-Tabelle $table existiert
 * @param  string $field Name des zu suchenden Datenbankfeldes
 * @param  string $table Name der Datenbanktabelle, in der gesucht werden soll
 * @return boolean      true = Feld vorhanden, false = Feld nicht vorhanden
 */
function fieldExists($field, $table) {
  global $db;
  $db->query("SHOW COLUMNS FROM {$table} LIKE '{$field}'");
  return $db->affected() === 1;
}