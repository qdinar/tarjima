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
		$outparr[0][0]=order_a_complex_noun_3($outparr[0][0]);
		$outparr[0][1]=order_a_complex_verb_3($outparr[0][1]);
	}else{
		//top verb suffix is not found
		$outparr=order_a_complex_noun_3($inparr);
	}
	return $outparr;
}

function order_a_complex_noun_3($inparr){
	if(isset($inparr['w'])){
		//this is simple noun
		return $inparr;
	}
	if(is_first_article_3($inparr)){
		$outparr=sep_first_article_3($inparr);
		$outparr[1]=order_comp_n_without_article_3($outparr[1]);
	}else{
		$outparr=order_comp_n_without_article_3($inparr);
	}
	return $outparr;
}

function order_comp_n_without_article_3($inparr){
	if(isset($inparr['w'])){
		//this is simple noun
		return $inparr;
	}
	$pos=get_first_conj_pos_3($inparr);
	if($pos!==null){
		$outparr=sep_dep_clause_3($inparr,$pos);
		$outparr[0]=order_dep_cl_3($outparr[0]);
	}else{
		//no conjunction
		$outparr=$inparr;
	}
	return($outparr);
}

function order_dep_cl_3($inparr){
	$tmp=verb_suf_pos_3($inparr);
	if($tmp!==null){
		$outparr=sep_verb_suf_3($inparr,$tmp);
		$tmp=$tmp-1;
		$outparr[0]=sep_subj_3($outparr[0],$tmp);
		$outparr[0][0]=order_a_complex_noun_3($outparr[0][0]);
		$outparr[0][1]=order_a_complex_verb_3($outparr[0][1]);
	}else{
		//verb suffix is not found
		//this is strange case
		$outparr=order_a_complex_noun_3($inparr);
	}
	return $outparr;
}

function sep_subj_3($inparr,$quantity){
	return sep_top_subj_3($inparr,$quantity);
}

function verb_suf_pos_3($inparr){
	foreach($inparr as $pos=>$elem){
		if(is_verb_suf($elem)){
			return $pos;
		}
	}
}

function sep_verb_suf_3($inparr,$pos){
	return sep_top_verb_suf_3($inparr,$pos);
}

function get_first_conj_pos_3($inparr){
	foreach($inparr as $pos=>$elem){
		if(is_conj($elem)){
			return $pos;
		}
	}
}

function sep_dep_clause_3($inparr,$pos){
	//separate dependent clause out of parent phrase
	$dep_cl=array_splice($inparr,$pos);
	$outparr[]=$dep_cl;
	$outparr[]=$inparr;
	return $outparr;
}

function is_first_article_3($inparr){
	if(is_article_3($inparr[0])){
		return true;
	}
}

function is_article_3($elem){
	if($elem['w']=='the'||$elem['w']=='a'||$elem['w']=='an'){
		return true;
	}
}

function sep_first_article_3($inparr){
	$article=array_splice($inparr,0,1);
	$outparr[]=$article[0];
	$outparr[]=$inparr;
	return $outparr;
}

function order_a_complex_verb_3($inparr){
	if(isset($inparr['w'])){
		//this is simple verb
		return $inparr;
	}
	$pos=last_prep_pos_3($inparr);
	if($pos!==null){
		$outparr=sep_last_prep_bl_3($inparr,$pos);
		//0 prep bl 1 verb
		if(!is_comma_or_logical_3($inparr[$pos-1])){
			//normal prepositional block
			//$outparr[0]=
			//$outparr[1]=
		}else{
			//comma before preposition and several prepositional blocks can be set into a block
			$comma_or_logical=array_splice($outparr[1],-1);
			$comma_or_logical=$comma_or_logical[0];
			$outparr[0]=array($outparr[0],$comma_or_logical);
			//0 ( 0 prep bl 1 , ) 1 verb
			//find second prep block from end
			$pos2=last_prep_pos_3($outparr[1]);
			if($pos2!==null){
				$outparr[1]=sep_last_prep_bl_3($outparr[1],$pos2);
				//0 ( 0 prep bl 1 , ) 1 (0 2nd prep bl 1 verb)
				$outparr[0]=array($outparr[0],$outparr[1][0]);
				//0 ( 0( 0 prep bl 1 , ) 1 2nd prep bl) 1 (0 2nd prep bl 1 verb)
				$outparr[1]=$outparr[1][1];
				//0 ( 0( 0 prep bl 1 , ) 1 2nd prep bl) 1 verb
				$outparr[0][1]=o_c_prep_bl_3($outparr[0][1]);
				$outparr[0][0][0]=o_c_prep_bl_3($outparr[0][0][0]);
			}else{
				//this is strange
				//example: go and to school
			}
		}
	}else{
		//no preposition block
		//find adverb
		$pos=first_adv_pos_3($inparr);
		if($pos!==null){
			//adverb is found
			//example: take quickly a book
			$outparr=sep_an_adv_3($inparr,$pos);
			//no adverb here:
			//example: take a book
			$outparr[1]=o_c_verb_without_prep_and_adv($outparr[1]);
		}else{
			//no adverb
			//example: take a book
			$outparr=o_c_verb_without_prep_and_adv($inparr);
		}
	}
	return $outparr;
}

function o_c_prep_bl_3($inparr){
	if(is_prep_3($inparr[0])){
		//example: with ...
		$prep=$inparr[0];
		array_splice($inparr,0,1);
		$outparr[]=$inparr;
		$outparr[]=$prep;
	}elseif(
		$inparr[0]['w']=='due'
		||$inparr[0]['w']=='similar'
	){
		//example: similar to ...
		$prep=$inparr[0];
		array_splice($inparr,0,1);
		$outparr[]=o_c_prep_bl_3($inparr);
		$outparr[]=$prep;
	}
	return $outparr;
}

function o_c_verb_without_prep_and_adv($inparr){
	//order complex verb without prepostion blocks and without adverbs but with object and may be something else strange
	//find object
	//example: take a book
	$pos=first_noun_pos_3($inparr);
	if($pos!==null){
		$outparr=sep_object_3($inparr,$pos);
	}else{
		//no noun
		//this is strange
		//example: go beautiful
		$outparr=$inparr;
	}
	return $outparr;
}

function sep_an_adv_3($inparr,$pos){
	$adv=array_splice($inparr,$pos,1);
	$outparr[]=$adv[0];
	$outparr[]=$inparr;
	return $outparr;
}

function sep_object_3($inparr,$pos){
	$obj=array_splice($inparr,$pos);
	if(count($obj)==1){
		$obj=$obj[0];
	}
	if(count($inparr)==1){
		$inparr=$inparr[0];
	}
	$outparr[]=$obj;
	$outparr[]=$inparr;
	return $outparr;
}

function first_adv_pos_3($inparr){
	foreach($inparr as $pos=>$elem){
		if(is_adv_3($elem)){
			return $pos;
		}
	}
}

function first_noun_pos_3($inparr){
	foreach($inparr as $pos=>$elem){
		if(is_noun_3($elem)||is_article_3($elem)){
			return $pos;
		}
	}
}

function is_adv_3($elem){
	global $nounlikes;
	if($nounlikes[$elem['w']]['type']=='adv'){
		return true;
	}
}

function is_noun_3($elem){
	global $nounlikes;
	if($nounlikes[$elem['w']]['type']=='noun'){
		return true;
	}
}

function sep_last_prep_bl_3($inparr,$pos){
	$prep_bl=array_splice($inparr,$pos);
	if(count($prep_bl)==1){
		$prep_bl=$prep_bl[0];
	}
	if(count($inparr)==1){
		$inparr=$inparr[0];
	}
	$outparr[]=$prep_bl;
	$outparr[]=$inparr;
	return $outparr;
}

function is_comma_or_logical_3($elem){
	if(
		$elem['w']==','
		||$elem['w']=='and'
		||$elem['w']=='or'
	){
		return true;
	}
}

function last_prep_pos_3($inparr){
	for($i=count($inparr)-1;$i>=0;$i--){
		if(is_prep_3($inparr[$i])){
			if(
				$inparr[$i-1]['w']=='due'
				||$inparr[$i-1]['w']=='similar'
			){
				return $i-1;
			}else{
				return $i;
			}
		}
	}
}

function is_prep_3($elem){
	if(
		$elem['w']=='with'
		||$elem['w']=='to'
		||$elem['w']=='from'
	){
		return true;
	}
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
	if(count($subj)==1){
		$subj=$subj[0];
	}
	$outparr[]=$subj;
	$outparr[]=$inparr;
	return $outparr;
}






