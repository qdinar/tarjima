<?php

function s_tr_to_str_3($nstd){
	global $nstd_to_str_2_firstwordisready;
	$nstd_to_str_2_firstwordisready=false;
	return nstd_to_str_3($nstd);
}


function nstd_to_str_3($nstd){
	$result='';
	if(isset($nstd[0]['tr'])){
		//explanation/dependent is 1 word
		// $result.='-';
		// $result.=$nstd[0]['tr'];
		$result.=word_tr_to_str_3($nstd[0]);
	}elseif(isset($nstd[0])){
		//explanation/dependent is complex
		$result.=nstd_to_str_3($nstd[0]);
	}
	if(isset($nstd[1]['w'])){
		//main/explained is 1 word
		// $result.='-';
		// $result.=$nstd[1]['tr'];
		$result.=word_tr_to_str_3($nstd[1]);
	}elseif(isset($nstd[1])){
		//main/explained is complex
		$result.=nstd_to_str_3($nstd[1]);
	}
	return $result;
}

function word_tr_to_str_3($elem){
	global $nstd_to_str_2_firstwordisready;
	if($elem['remove']==true){
		return '';
	}
	global $show_invis_chars;
	if(true==$nstd_to_str_2_firstwordisready){
		if($show_invis_chars){
			$result.='·';
		}else{
			$result.=' ';
		}
	}
	$result.=$elem['tr'];
	$nstd_to_str_2_firstwordisready=true;
	return $result;
}































