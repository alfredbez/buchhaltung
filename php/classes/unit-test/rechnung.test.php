<?php
include('../../mysql_config.php');
include('../mysql.inc.php');

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

include('../../../libs/fpdf/fpdf.php');
include('../pdf.inc.php');
include('../rechnung.inc.php');

error_reporting(-1);
$pdf = new rechnung(11906);
$pdf->Output('test.pdf');
?>