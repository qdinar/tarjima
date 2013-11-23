<?php

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', '1');

/*
$words=array('good'=>'әйбәт','school'=>'мәктәп');

function tr_simple_block($simbl){

global $words;
$s2=array();
foreach($simbl as $word ){$s2[$word]=$words[$word];}

return $s2;

}


$test=array('good','school');
echo implode ( tr_simple_block($test)  );
*/


$words=array('good'=>'әйбәт','school'=>'мәктәп');

function tr_simple_block($simbl){

	global $words;
	$s2=array();
	if(is_array($simbl[0])){
		$s2[0]=tr_simple_block($simbl[0]);
	}else{
		$s2[0]=$words[$simbl[0]];
	}
	if(is_array($simbl[1])){
		$s2[1]=tr_simple_block($simbl[1]);
	}else{
		$s2[1]=$words[$simbl[1]];
	}

	return $s2;

}

$test=array('good','school');
echo implode ( tr_simple_block($test)  );

$words['know']='бел';
$words['last']='соңгы';

echo'<br/>';

$test=array('last','know');
echo implode ( tr_simple_block($test)  );

echo'<br/>';

function tr_past_p($pastp){
	global $words;
	if(is_array($pastp)){
		$s2[0]=tr_simple_block($pastp);
	}else{
		$s2[0]=$words[$pastp];
	}
	$s2[1]='гән';
	return $s2;
}

$test='know';
echo implode ( tr_past_p($test)  );





?>