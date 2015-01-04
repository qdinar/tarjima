<?php


function order_a_sentence_3($inparr){
	if(is_last_dot_3($inparr)){
		$outparr=sep_last_dot_3($inparr);
		$outparr[0]=order_a_sent_without_last_dot($outparr[0]);
	}else{
		//no last dot
		$outparr=order_a_sent_without_last_dot($inparr);
	}
	return $outparr;
}

function order_a_sent_without_last_dot($inparr){
	$tmp=top_verb_suf_pos_3($inparr);
	if($tmp!==null){
		$outparr=sep_top_verb_suf_3($inparr,$tmp);
		$tmp=$tmp-1;
		$outparr[0]=sep_top_subj_3($outparr[0],$tmp);
		$outparr[0][0]=order_a_complex_noun($outparr[0][0]);
		$outparr[0][1]=order_a_complex_verb($outparr[0][1]);
	}else{
		//top verb suffix is not found
		$outparr=order_a_complex_noun($inparr);
	}
	return $outparr;
}

function order_a_complex_noun($inparr){
	return $outparr;
}

function order_a_complex_verb($inparr){
	return $outparr;
}


function is_last_dot_3($inparr){
	if(
		$inparr[count($inparr)-1]['w']=='.'
	){
		return true;
	}
}

function sep_last_dot_3($inparr){
	$dot=array_splice($inparr,-1);
	$outparr[]=$inparr;
	$outparr[]=$dot[0];
	return $outparr;
}

function is_conj($elem){
	if($elem['w']=='that'){
		return true;
	}
}

function is_verb_suf($elem){
	if(
		$elem['w']=='v-0'//present plural (they go, we go, you go) or 1st or 2nd person singular (i go, thou go)
		||$elem['w']=='v-s'//3d person present singular (he goes)
		||$elem['w']=='v-re'//strictly present plural (they are, you are, we are)
	){
		return true;
	}
}


function top_verb_suf_pos_3($inparr){
	$conj=0;//conjunction
	$top_suf=0;//top verb suffix
	foreach($inparr as $pos=>$elem){
		if(is_conj($elem)){
			$conj++;
		}elseif(is_verb_suf($elem)){
			$top_suf++;
		}
		if($top_suf-$conj==1){
			return $pos;
		}
	}
}

function sep_top_verb_suf_3($inparr,$pos){
	$suf=array_splice($inparr,$pos,1);
	$outparr[]=$inparr;
	$outparr[]=$suf[0];
	return $outparr;
}

function sep_top_subj_3($inparr,$quant){
	$subj=array_splice($inparr,0,$quant);
	$outparr[]=$subj;
	$outparr[]=$inparr;
	return $outparr;
}







