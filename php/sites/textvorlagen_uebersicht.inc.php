<?php
$db->query("
	SELECT
		id,
		titel,
		text
	FROM
		textvorlagen");
$keys = [
	'id' => '#',
	'titel' => 'Titel',
	'text' => 'Text',
];
$res=array();
$maxCharacters = 160;
while($row = $db->fetchRow()){
	$temp = [];
	foreach($keys as $key => $prettyKey){
		$rowValue = $row[$key];
		if($key === 'text' && strlen($rowValue) > $maxCharacters)
		{
			$rowValue = substr($rowValue, 0, $maxCharacters) . ' ...';
		}
		$temp[$key] = $rowValue;
	}
	$res[] = $temp;
}
$smarty->assign('res',$res);
$smarty->assign('keys',$keys);
?>