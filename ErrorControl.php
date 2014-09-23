<?php
function ErrorControl($var) {

$ErrArray=array('"','\'',';','=','/');
$Pattern="(".$ErrArray['0']."|".$ErrArray['1']."|".$ErrArray['2']."|".$ErrArray['3']."|".$ErrArray['4'].")";
$Match=preg_match($Pattern, $var);

	switch ($Match) {
		case 1:
			$result=TRUE;
			return $result;
			break;
	
		default:
			$result=FALSE;
			return $result;
			break;
	}
	}
?>