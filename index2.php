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
		if($simbl[0]=='the'){
			$s2[0]='теге';
		}else{
			$s2[0]=$words[$simbl[0]];
		}
	}
	//add ны
	if(
		((is_array($simbl[1])&&$simbl[1][1]=='read')||$simbl[1]=='read')
		&&
		(is_array($simbl[0])&&$simbl[0][0]=='the')
	){
		$s2[0]=array($s2[0],'ны');
	}
	//
	if(is_array($simbl[1])){
		$s2[1]=tr_simple_block($simbl[1]);
	}else{
		if($simbl[1]=='ed'){
			//$s2[1]='лгән';
			if((is_array($simbl[0])&&$simbl[0][1]=='know')||$simbl[0]=='know'){
				$s2[1]='енгән';
			}else{
				$s2[1]='лган';
			}
		}elseif($simbl[1]=='have'){
			if(is_array($simbl[0])&&$simbl[0][1]=='ed'){
				$s2[1]='ды';
				$s2[0]=$s2[0][0];
			}
		}else{
			$s2[1]=$words[$simbl[1]];
		}
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

/*
function tr_past_p($pastp){
	global $words;
	if(is_array($pastp)){
		$s2[0]=tr_simple_block($pastp);
	}else{
		$s2[0]=$words[$pastp];
	}
	$s2[1]='енгән';
	return $s2;
}

$test='know';
echo implode ( tr_past_p($test)  );

echo'<br/>';

*/

function nstd_to_str($nstd){
	$result='';
	if(is_array($nstd[0])){
		$result.=nstd_to_str($nstd[0]);
	}else{
		$result.=$nstd[0];
	}
	if(is_array($nstd[1])){
		$result.=nstd_to_str($nstd[1]);
	}else{
		$result.=$nstd[1];
	}
	return $result;
}
/*
$test=array('last','know');
$result=tr_past_p($test);
echo nstd_to_str($result);

echo'<br/>';

$words['bug']='баг';
$test=array($result,'bug');
echo nstd_to_str($test);
*/

echo'<br/>';

//$words['ed']='енгән';

$test=array($test,'ed');
$result=tr_simple_block($test);
echo nstd_to_str($result);

echo'<br/>';

$words['bug']='баг';
$test=array($test,'bug');
$result=tr_simple_block($test);
echo nstd_to_str($result);

echo'<br/>';

$words['read']='укы';
$test2=array($test,'read');
$result=tr_simple_block($test2);
echo nstd_to_str($result);

echo'<br/>';
$test=array('the',$test);
$result=tr_simple_block($test);
echo nstd_to_str($result);

echo'<br/>';
$test3=array($test,'read');
$result=tr_simple_block($test3);
echo nstd_to_str($result);

echo'<br/>';
$test2=array('read','ed');
$result=tr_simple_block($test2);
echo nstd_to_str($result);

echo'<br/>';
$test2=array($test2,'have');
$result=tr_simple_block($test2);
echo nstd_to_str($result);

echo'<br/>';
$test3=array($test3,'ed');
$test3=array($test3,'have');
$result=tr_simple_block($test3);
echo nstd_to_str($result);






























?>