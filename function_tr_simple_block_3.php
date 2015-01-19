<?php

function tr_simple_block_3(&$simbl){
	if(isset($simbl[0]['w'])){
		//explanation/dependent is 1 word
		$simbl[0]['tr']=tr_a_word_3($simbl[0]['w']);
	}else{
		//explanation/dependent is complex
		tr_simple_block_3($simbl[0]);
	}
	if(isset($simbl[1]['w'])){
		//main/explained is 1 word
		$simbl[1]['tr']=tr_a_word_3($simbl[1]['w']);
	}else{
		//main/explained is complex
		tr_simple_block_3($simbl[1]);
	}
}



function tr_a_word_3($w){
	global $nounlikes, $verbs, $suf_prep;
	if(isset($nounlikes[$w])){
		return $nounlikes[$w]['tt'];
	}
	else
	if(isset($verbs[$w])){
		return $verbs[$w]['tt'];
	}
	else
	if(isset($suf_prep[$w])){
		return $suf_prep[$w]['tt'];
	}
	//else
	return $w;
}



















