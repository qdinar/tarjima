<?php

function tr_simple_block_3(&$simbl){
	//The (actual DRAM arrays that store the data)
	//->
	//(The actual DRAM arrays) (that store the data)
	if(
		$simbl[0]['w']=='the'
		&&$simbl[1][0][0][0]['w']=='that'
	){
		//copy "the"
		$simbl[1][1]=array($simbl[0],$simbl[1][1]);
		//delete original "the"
		$simbl=$simbl[1];
	}
	if($simbl[0][0]['w']=='the'){
		if($simbl[1]['w']==',,'){
			$simbl[0][0]['donotremove']=true;
		}
	}
	if($simbl[0]['w']=='the'&&$simbl[0]['donotremove']!=true){
		$simbl[0]['remove']=true;
	}
	if(isset($simbl[0]['w'])){
		//explanation/dependent is 1 word
		tr_a_word_3($simbl[0]);
	}else{
		//explanation/dependent is complex
		tr_simple_block_3($simbl[0]);
	}
	if(isset($simbl[1]['w'])){
		//main/explained is 1 word
		tr_a_word_3($simbl[1]);
	}else{
		//main/explained is complex
		tr_simple_block_3($simbl[1]);
	}
}



function tr_a_word_3(&$elem){
	global $nounlikes, $verbs, $suf_prep;
	$w=$elem['w'];
	if(isset($nounlikes[$w])){
		$elem['tr']=$nounlikes[$w]['tt'];
	}
	else
	if(isset($verbs[$w])){
		$elem['tr']=$verbs[$w]['tt'];
	}
	else
	if(isset($suf_prep[$w])){
		$elem['tr']=$suf_prep[$w]['tt'];
	}
	else
	{
		$elem['tr']=$w;
	}
	if($w==',,'){
		$elem['remove']=true;
	}
}



















