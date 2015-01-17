<?php


function order_a_sentence_3($inparr){
	if(is_last_dot_3($inparr)){
		$outparr=sep_last_dot_or_suf_3($inparr);
		$outparr[0]=order_a_sent_without_last_dot($outparr[0]);
	}else{
		//no last dot
		$outparr=order_a_sent_without_last_dot($inparr);
	}
	return $outparr;
}

function order_a_sent_without_last_dot($inparr){
	//need to check for introduction
	if(is_prep_3($inparr[0])){
		$pos=first_comma_pos_3($inparr);
		if($pos!==null){
			$outparr=sep_first_several_3($inparr,$pos+1);
			$outparr[1]=o_s_no_lastdot_intro_3($outparr[1]);
			$outparr[0]=o_intro_3($outparr[0]);
			$outparr[0][0]=o_c_prep_bl_3($outparr[0][0]);
		}else{
			//example
			//in a book.
			$outparr=o_c_prep_bl_3($inparr);
		}
	}else{
		$outparr=o_s_no_lastdot_intro_3($inparr);
	}
	return $outparr;
}

function o_intro_3($inparr){
	//last should be comma
	//$comma=array_splice($inparr,-1);
	array_splice($inparr,-1);
	$outparr[]=fix_one_element_in_array($inparr);
	// if(count($outparr[0])==1){
		// $outparr[0]=$outparr[0][0];
	// }
	//$outparr[]=$comma[0];
	$outparr[]=array('w'=>'intro_comma');
	return $outparr;
}

function o_s_no_lastdot_intro_3($inparr){
	$tmp=top_verb_suf_pos_3($inparr);
	if($tmp!==null){
		$pos=first_log_with_comm_pos_3($inparr);
		//2 verbs joined with comma and logical operator are checked and second one's top suffix also should be separated
		//assume for now it is only one and does not belong to depenent clause
		//... n , n+1 and n+2 be n+3 s n+4 ...
		if( $pos!==null && $pos>$tmp && is_verb_suf($inparr[$pos+3])){
			array_splice($inparr,$pos+3,1);
			//assume it is same suffix as of verb at left side of logical
			//if the 2 suffixes are present simple one and past simple other, they should not be separated here
		}
		$outparr=sep_top_verb_suf_3($inparr,$tmp);
		$tmp=$tmp-1;
		//0 A 1 be 2 s
		//tmp=2 -> get 2-1 elements from 0
		$outparr[0]=sep_top_subj_3($outparr[0],$tmp);
		$outparr[0][0]=order_a_complex_noun_3($outparr[0][0]);
		$outparr[0][1]=order_a_complex_verb_3($outparr[0][1]);
	}else{
		//top verb suffix is not found
		$outparr=order_a_complex_noun_3($inparr);
	}
	return $outparr;
}

function first_log_with_comm_pos_3($inparr){
	for($i=1;$i<count($inparr);$i++){
		if(is_logical_3($inparr[$i])&&$inparr[$i-1]['w']==','){
			return $i-1;
		}
	}
}

function first_comma_pos_3($inparr){
	foreach($inparr as $pos=>$elem){
		if($elem['w']==','){
			return $pos;
		}
	}
}

function order_a_complex_noun_3($inparr){
	if(is_simple_or_ordered_3($inparr)){
		return $inparr;
	}
	//need to check for quotes
	if(
		$inparr[0]['w']=='"'
		&&$inparr[count($inparr)-1]['w']=='"'
	){
		$outparr=sep_quotes_3($inparr);
		$outparr=order_a_complex_noun_3($outparr);
		return $outparr;
	}
	//else
	//- separate at ", and" first
	//example: different signal ing voltage s , timing s , and other factor s
	$pos=last_logical_with_comma_pos_3($inparr);
	if($pos!==null){
		//separate comma part out
		$outparr=sep_comma_or_logical_bl_3($inparr,$pos);
		//get comma out
		$outparr[0]=o_logic_bl_norecurs_3($outparr[0]);
		//get logical out
		$outparr[0][0]=o_logic_bl_norecurs_3($outparr[0][0]);
		//return $outparr;
		$outparr[0][0][0]=order_a_complex_noun_3($outparr[0][0][0]);
		//main part
		$outparr[1]=order_a_complex_noun_3($outparr[1]);
	}else{
		//no ", and"
		//wrong place for last parentheses: example:
		//neither forward nor backward compatible with any earlier type of random access memory (RAM)
		if(is_first_article_3($inparr)){
			$outparr=sep_first_word_3($inparr);
			$outparr[1]=order_comp_n_without_article_3($outparr[1]);
		}else{
			$outparr=order_comp_n_without_article_3($inparr);
		}
	}
	return $outparr;
}

function sep_quotes_3($inparr){
	//see also sep_parentheses_3
	//1st and last elements should be "
	//works but i should make it other way
	// $outparr[]=fix_one_element_in_array(array_slice($inparr,1,count($inparr)-2));
	// $outparr[]=array('w'=>'""');
	$outparr=fix_one_element_in_array(array_slice($inparr,1,count($inparr)-2));
	$outparr['inquotes']=true;
	return $outparr;
}

function sep_parentheses_3($inparr){
	//1st and last elements should be ( and )
	$outparr[]=array_slice($inparr,1,count($inparr)-2);
	if(count($outparr[0])==1){
		$outparr[0]=$outparr[0][0];
	}
	$outparr[]=array('w'=>'()');
	return $outparr;
}

function sep_last_parenth_bl_3($inparr,$pos){
	return sep_dep_clause_3($inparr,$pos);
}

function op_parenth_for_3($inparr,$cl_p_pos){
	$deep=1;
	for($i=$cl_p_pos-1;$i>=0;$i--){
		if($inparr[$i]['w']==')'){
			$deep++;
		}elseif($inparr[$i]['w']=='('){
			$deep--;
		}
		if($deep==0){
			return $i;
		}
	}
}

function last_parenth_3($inparr){
	if($inparr[count($inparr)-1]['w']==')'){
		return true;
	}
}

function last_logical_with_comma_pos_3($inparr){
	for($i=count($inparr)-1;$i>=1;$i--){
		if(is_logical_3($inparr[$i])&&$inparr[$i-1]['w']==','){
			return $i-1;
		}
	}
}

function is_logical_3($elem){
	if(
		$elem['w']=='and'
		||$elem['w']=='or'
		||$elem['w']=='nor'
	){
		return true;
	}
}

function is_simple_or_ordered_3($inparr){
	return (
		isset($inparr['w'])
		//||count($inparr)==2&&isset($inparr[0]['w'])&&isset($inparr[1]['w'])
		||
		isset($inparr[0])
		&&isset($inparr[1])
		&&!isset($inparr[2])
		||count($inparr)==0
	);
}

function order_comp_n_without_article_3($inparr){
	if(is_simple_or_ordered_3($inparr)){
		//this is simple noun
		return $inparr;
	}
	$pos=get_first_conj_pos_3($inparr);
	if($pos!==null){
		$outparr=sep_dep_clause_3($inparr,$pos);
		$outparr[0]=order_dep_cl_3($outparr[0]);
		$outparr[1]=order_a_complex_noun_3($outparr[1]);
	}else{
		//no conjunction
		$outparr=o_c_n_noart_nodepcl_3($inparr);
		//$outparr=$inparr;
	}
	return $outparr;
}

function o_c_n_noart_nodepcl_3($inparr){
	if(is_simple_or_ordered_3($inparr)){
		return $inparr;
	}
	//need to check for logicals and commas
	//example:
	//higher-speed successor to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips
	$pos=last_comm_or_logic_pos_3($inparr);
	if($pos!==null){
		//need to check to separate or not
		//example:
		//different signal ing voltage s , timing s
		//example:
		//neither forward nor backward compatible with any ...
		//example:
		//higher-speed successor to DDR and DDR2
		//need to check for explanation block with 2 commas around
		//example:
		//DDR3 SDRAM, an abbreviation for double data rate type three synchronous dynamic random access memory,
		if($pos==count($inparr)-1 && $inparr[$pos]['w']==','){
			$inparr=del_last_el_3($inparr);
			$begin_comma_pos=last_comm_pos_3($inparr);
			$outparr=sep_comma_or_logical_bl_3($inparr,$begin_comma_pos+1);
			$outparr[1]=del_last_el_3($outparr[1]);
			$outparr[0]=array($outparr[0],array('w'=>',,'));
			$outparr[0][0]=order_a_complex_noun_3($outparr[0][0]);
		}elseif($inparr[count($inparr)-1]['w']==$inparr[$pos-1]['w']){
			//$outparr=$inparr;
			//i will try to join "voltage s , timing s" into one element in its place
			//check for "voltage s , timing s" or "different signal ing voltage s , timing s"
			if($pos>2){
				//"different signal ing voltage s , timing s"
				$outparr=join_last_block_3($inparr,$pos-2);
				$outparr[count($outparr)-1]=o_c_n_noart_nodepcl_3($outparr[count($outparr)-1]);
				//$outparr=o_n_no_ar_dc_pr_comlog_parenth_3($outparr);
				$outparr=o_c_n_noart_nodepcl_3($outparr);
			}elseif($pos==2){
				//"voltage s , timing s"
				$outparr=sep_comma_or_logical_bl_3($inparr,$pos);
				//get comma or logical out
				$outparr[0]=o_logic_bl_norecurs_3($outparr[0]);
				//main part
				$outparr[1]=order_a_complex_noun_3($outparr[1]);
				//inner part
				$outparr[0][0]=order_a_complex_noun_3($outparr[0][0]);
			}else{
				//it is strange:
				//"s , timing s"
			}
			//$outparr=o_n_no_ar_dc_pr_comlog_parenth_3($inparr);
		}elseif($inparr[$pos]['w']=='nor'&&$inparr[$pos-2]['w']=='neither'){
			$outparr=o_n_bl_no_ar_dc_comm_log_3($inparr);
		}elseif(
			$inparr[$pos+1]['w']=='DDR2'
			&&$inparr[$pos-1]['w']=='DDR'
			&&count($inparr)>3
		){
			$outparr=join_last_block_3($inparr,$pos-1);
			$outparr[count($outparr)-1]=o_c_n_noart_nodepcl_3($outparr[count($outparr)-1]);
			$outparr=o_c_n_noart_nodepcl_3($outparr);
		}else{
			$outparr=sep_comma_or_logical_bl_3($inparr,$pos);
			//get comma or logical out
			$outparr[0]=o_logic_bl_norecurs_3($outparr[0]);
			//main part
			$outparr[1]=order_a_complex_noun_3($outparr[1]);
			//inner part
			$outparr[0][0]=order_a_complex_noun_3($outparr[0][0]);
		}
	}else{
		//no ","
		//$outparr=$inparr;
		//$outparr=o_n_no_ar_dc_pr_comlog_parenth_3($inparr);
		$outparr=o_n_bl_no_ar_dc_comm_log_3($inparr);
	}
	return $outparr;
}

function del_last_el_3($inparr){
	unset($inparr[count($inparr)-1]);
	return $inparr;
}

function last_comm_pos_3($inparr){
	for($i=count($inparr)-1;$i>=0;$i--){
		if($inparr[$i]['w']==','){
			return $i;
		}
	}
}

function o_n_bl_no_ar_dc_comm_log_3($inparr){
	$pos=c_n_1st_prep_3($inparr);
	if($pos!==null){
		$outparr=sep_n_prep_bl_3($inparr,$pos);
		$outparr[0]=o_c_prep_bl_3($outparr[0]);
		$outparr[1]=o_c_n_no_ar_dc_comlog_pr_3($outparr[1]);
	}else{
		//no preposition
		$outparr=o_c_n_no_ar_dc_comlog_pr_3($inparr);
	}
	return $outparr;
}

function o_c_n_no_ar_dc_comlog_pr_3($inparr){
	//need to separate plural s suffix at end
	//i am going to do that in o_n_no_ar_dc_pr_comlog_parenth_3
	//need to check for parentheses block
	//example:
	//DDR4 synchronous dynamic random access memory (SDRAM) chips
	//DDR4 { synchronous dynamic random access memory (SDRAM) } chips
	if(true==find_first_parenth_3($inparr,$opening,$closing)){
		//echo'OK';
		if($closing-$opening>1){
			$inner=array_slice($inparr,$opening+1,$closing-$opening-1);
			if(count($inner)==1){
				$inner=$inner[0];
				if($inner['thisisabbreviation']){
					//echo'OK';
					//echo $opening;
					//echo $inner['w'];
					//show_tree_3($inparr);
					//try to check for first letters
					//and find beginning of parentheses block
					$letters=str_split($inner['w'],1);
					//assume all are capital letters
					if($opening>count($letters)){
						//echo'OK';
						//the hello world (HW) ...
						//hello world program (HW) ...
						//try count of words before ( as letters in abbrevation
						$try_equal=true;
						for($i=$opening-count($letters),$j=0;$i<$opening;$i++,$j++){
							if(substr($inparr[$i]['w'],0,1)!=strtolower($letters[$j])){
								$try_equal=false;
								break;
							}
						}
						if($try_equal){
							//echo'OK';
							//example:
							//the hello world (HW) ...
							$outparr=join_parenth_bl_and_o_from_to_3($inparr,$opening-count($letters),$closing);
							//$outparr[$opening-count($letters)]=o_parenthesed_3($outparr[$opening-count($letters)]);
							$outparr[$opening-count($letters)]['mainw']=$inner['w'];
						}else{
							//examples:
							//hello world program (HW) ...
							//assume all words before ( belong to explaned
							$outparr=join_parenth_bl_and_o_from_to_3($inparr,0,$closing);
							//$outparr[0]=o_parenthesed_3($outparr[0]);
						}
					}else{
						//example:
						//hello world (HWP) ...
						//assume all words before ( belong to explaned
						$outparr=join_parenth_bl_and_o_from_to_3($inparr,0,$closing);
						//$outparr[0]=o_parenthesed_3($outparr[0]);
					}
				}else{
					//1 word in (), not abbr
					//hello world program (test) ...
					$outparr=join_parenth_bl_and_o_from_to_3($inparr,0,$closing);
					//$outparr[0]=o_parenthesed_3($outparr[0]);
				}
			}else{
				//many words in ()
				//hello world program (test program) ...
				$outparr=join_parenth_bl_and_o_from_to_3($inparr,0,$closing);
				//$outparr[0]=o_parenthesed_3($outparr[0]);
			}
		}else{
			//strange: ... () ...
		}
	}else{
		//parentheses block not found
		$outparr=$inparr;
	}
	/*
	if(last_parenth_3($inparr)){
		// / *
		$pos=op_parenth_for_3($inparr,count($inparr)-1);
		if($pos!==null){
			$outparr=sep_last_parenth_bl_3($inparr,$pos);
			$outparr[0]=sep_parentheses_3($outparr[0]);
			$outparr[1]=o_n_no_ar_dc_pr_comlog_parenth_3($outparr[1]);
		}else{
			//opening parentheses is not found
		}
		// * /
		$outparr=o_parenthesed_3($inparr);
	}else{
		//no ) at end
		if(is_there_a_neither_nor_bl($inparr)){
			find_neither_bl_first_last_3($inparr,$first,$last);
			$outparr=join_neither_bl_3($inparr,$first,$last);
		}else{
			//$outparr=$inparr;
			$outparr=o_n_no_ar_dc_pr_comlog_parenth_3($inparr);
		}
	}
	*/
	$outparr=o_n_no_ar_dc_pr_comlog_parenth_3($outparr);
	return $outparr;
}

function o_parenthesed_3($inparr){
	$pos=op_parenth_for_3($inparr,count($inparr)-1);
	if($pos!==null){
		$outparr=sep_last_parenth_bl_3($inparr,$pos);
		$outparr[0]=sep_parentheses_3($outparr[0]);
		$outparr[1]=o_n_no_ar_dc_pr_comlog_parenth_3($outparr[1]);
		$outparr[0][0]=order_a_complex_noun_3($outparr[0][0]);
	}else{
		//opening parentheses is not found
	}
	return $outparr;
}

function join_parenth_bl_and_o_from_to_3($inparr,$begin,$end){
	if($begin==0&&$end==count($inparr)-1){
		return o_parenthesed_3($inparr);
	}
	$joined=array_splice($inparr,$begin,$end-$begin+1,'');
	$inparr[$begin]=o_parenthesed_3($joined);
	return $inparr;
}

function find_first_parenth_3($inparr,&$opening,&$closing){
	foreach($inparr as $pos=>$elem){
		if($elem['w']=='('){
			$opening=$pos;
			$deep++;
		}
		if( $elem['w']==')' && $deep>0 ){
			$deep--;
			if($deep==0){
				$closing=$pos;
				return true;
			}
		}
	}
}

/*
function o_c_n_no_ar_dc_pr_3($inparr){
	//order complex noun without article nor dependent clause nor prepositions
	//but there may be logical words, commas
	if(last_parenth_3($inparr)){
		$pos=op_parenth_for_3($inparr,count($inparr)-1);
		if($pos!==null){
			$outparr=sep_last_parenth_bl_3($inparr,$pos);
			$outparr[0]=sep_parentheses_3($outparr[0]);
		}else{
			//opening parentheses is not found
		}
	}else{
		//no ) at end
		if(is_there_a_neither_nor_bl($inparr)){
			find_neither_bl_first_last_3($inparr,$first,$last);
			$outparr=join_neither_bl_3($inparr,$first,$last);
		}else{
			//$outparr=$inparr;
			$pos=last_comm_or_logic_pos_3($inparr);
			if($pos!==null){
				//need to check to separate or not
				//example:
				//different signal ing voltage s , timing s
				if($inparr[count($inparr)-1]['w']!=$inparr[$pos-1]['w']){
					$outparr=sep_comma_or_logical_bl_3($inparr,$pos);
					//get comma or logical out
					$outparr[0]=o_logic_bl_norecurs_3($outparr[0]);
					//main part
					$outparr[1]=order_a_complex_noun_3($outparr[1]);
				}else{
					//$outparr=$inparr;
					//i will try to join "voltage s , timing s" into one element in its place
					//check for "voltage s , timing s" or "different signal ing voltage s , timing s"
					if($pos>2){
						//"different signal ing voltage s , timing s"
						$outparr=join_last_block_3($inparr,$pos-2);
						$outparr[count($outparr)-1]=o_c_n_no_ar_dc_pr_3($outparr[count($outparr)-1]);
						$outparr=o_n_no_ar_dc_pr_comlog_parenth_3($outparr);
					}elseif($pos==2){
						//"voltage s , timing s"
						$outparr=sep_comma_or_logical_bl_3($inparr,$pos);
						//get comma or logical out
						$outparr[0]=o_logic_bl_norecurs_3($outparr[0]);
						//main part
						$outparr[1]=order_a_complex_noun_3($outparr[1]);
					}else{
						//it is strange:
						//"s , timing s"
					}
					//$outparr=o_n_no_ar_dc_pr_comlog_parenth_3($inparr);
				}
			}else{
				//no ","
				//$outparr=$inparr;
				$outparr=o_n_no_ar_dc_pr_comlog_parenth_3($inparr);
			}
		}
	}
	return $outparr;
}
*/

function join_last_block_3($inparr,$pos){
	//different signal ing voltage s , timing s
	$joined=array_splice($inparr,$pos);
	$inparr[]=$joined;
	return $inparr;
}

function o_n_no_ar_dc_pr_comlog_parenth_3($inparr){
	if(is_simple_or_ordered_3($inparr)){
		return $inparr;
	}
	//need to separate plural s suffix at end, example:
	//DDR4 synchronous dynamic random access memory (SDRAM) chips
	if($inparr[count($inparr)-1]['w']=='s-pl'){
		$outparr=sep_last_dot_or_suf_3($inparr);
		$outparr[0]=o_n_no_ar_dc_pr_comlog_par_lastpl_3($outparr[0]);
	}else{
		$outparr=o_n_no_ar_dc_pr_comlog_par_lastpl_3($inparr);
	}
	return $outparr;
}

function o_n_no_ar_dc_pr_comlog_par_lastpl_3($inparr){
	if(is_simple_or_ordered_3($inparr)){
		return $inparr;
	}
	//need to check for:
	//double data rate type three synchronous dynamic random access memory
	//double (data rate) (type three) synchronous dynamic (random access) memory
	if(
		//early er type s
		$inparr[1]['w']=='er-comp'
		//signal ing voltage s , timing s
		||$inparr[1]['w']=='ing'
		// ||(
			// $inparr[0]['w']=='type'
			// &&$inparr[1]['w']=='three'
		// )
	){
		//need to check:
		// high er - speed successor
		if($inparr[2]['w']=='-'){
			$outparr=sep_first_several_3($inparr,4);
			array_splice($outparr[0],2,1);
			$outparr[0]=o_n_no_ar_dc_pr_comlog_parenth_3($outparr[0]);
			$outparr[0]['hyphen']=true;
		}else{
			//need to join 1st 2 elements
			$outparr=sep_first_several_3($inparr,2);
			$outparr[1]=o_n_no_ar_dc_pr_comlog_parenth_3($outparr[1]);
		}
	}
	//voltage s , timing s
	elseif($inparr[1]['w']=='s-pl'){
		//need to check for comma
		//i think i will join "voltage s , timing s" into one element before it comes here
		$outparr=$inparr;
	}elseif(is_there_a_neither_nor_bl($inparr)){
		find_neither_bl_first_last_3($inparr,$first,$last);
		$outparr=join_neither_bl_3($inparr,$first,$last);
	}elseif(
		is_there_a_type_num_bl($inparr,$type_pos,$end_pos)
		&&$end_pos!=count($inparr)-1
	){
		$outparr=sep_first_several_3($inparr,$end_pos+1);
		$outparr[0]=sep_dep_clause_3($outparr[0],$type_pos);
		//$outparr[0]: 0 type three 1 d d r
		$outparr[0][0]=o_logic_bl_norecurs_3($outparr[0][0]);
		//$outparr[0][0]: 0 three 1 type
		$outparr[0][1]=o_n_no_ar_dc_pr_comlog_parenth_3($outparr[0][1]);
		$outparr[1]=o_n_no_ar_dc_pr_comlog_parenth_3($outparr[1]);
	}else{
		//need to check for multiwords
		//example: random access memory
		//example: DRAM interface specification
		//assume there are only 2 word multiwords
		if(first_mw_3($inparr)){
			$outparr=sep_first_several_3($inparr,2);
			$outparr[1]=o_n_no_ar_dc_pr_comlog_parenth_3($outparr[1]);
		}else{
			//first 2 words are not of a multiword
			//just separate first word
			$outparr=sep_first_word_3($inparr);
			$outparr[1]=o_n_no_ar_dc_pr_comlog_parenth_3($outparr[1]);
		}
		//do not order
		//$outparr=$inparr;
	}
	return $outparr;
}

function is_there_a_type_num_bl($inparr,&$type_pos,&$end_pos){
	foreach($inparr as $pos=>$elem){
		if(
			$elem['w']=='type'
			&&is_num_3($inparr[$pos+1])
		){
			$type_pos=$pos;
			$end_pos=$pos+1;
			return true;
		}
	}
}

function is_num_3($elem){
	if(
		$elem['w']=='three'
	){
		return true;
	}
}

function first_mw_3($inparr){
	global $multiwords;
	foreach($multiwords as $dic_item){
		if(
			(
				$inparr[0]['w']==$dic_item[0]
				||$inparr[0]['mainw']==$dic_item[0]
			)
			&&(
				$inparr[1]['w']==$dic_item[1]
				||$inparr[1]['mainw']==$dic_item[1]
			)
		){
			return true;
		}
	}
}

function sep_first_several_3($inparr,$quantity){
	$outparr[]=array_splice($inparr,0,$quantity);
	if(count($outparr[0])==1){
		$outparr[0]=$outparr[0][0];
	}
	$outparr[]=$inparr;
	if(count($outparr[1])==1){
		$outparr[1]=$outparr[1][0];
	}
	return $outparr;
}


function find_neither_bl_first_last_3($inparr,&$first,&$last){
	foreach($inparr as $pos=>$elem){
		if($elem['w']=='neither'){
			$first=$pos;
			break;
		}
	}
	foreach($inparr as $pos=>$elem){
		if($elem['w']=='backward'){
			$last=$pos;
			break;
		}
	}
}

function is_there_a_neither_nor_bl($inparr){
	foreach($inparr as $pos=>$elem){
		if($elem['w']=='neither'){$i++;}
		if($elem['w']=='nor'){$i++;}
	}
	if($i==2){
		return true;
	}
}

function join_neither_bl_3($inparr,$first,$last){
	$neither_bl=array_slice($inparr,$first,$last-$first+1);
	$neither_bl=sep_first_word_3($neither_bl);
	$nor_pos=last_comm_or_logic_pos_3($neither_bl[1]);
	//nor_post should not be null
	$neither_bl[1]=sep_comma_or_logical_bl_3($neither_bl[1],$nor_pos);
	$neither_bl[1][0]=o_logic_bl_norecurs_3($neither_bl[1][0]);
	array_splice($inparr,$first,$last-$first+1,array($neither_bl));
	return $inparr;
}

function o_logic_bl_norecurs_3($inparr){
	//first element should be comma or logical
	$outparr[]=array_slice($inparr,1);
	if(count($outparr[0])==1){
		$outparr[0]=$outparr[0][0];
	}
	$outparr[]=$inparr[0];
	return $outparr;
}

function sep_comma_or_logical_bl_3($inparr,$pos){
	return sep_dep_clause_3($inparr,$pos);
}

function last_comm_or_logic_pos_3($inparr){
	for($i=count($inparr)-1;$i>=0;$i--){
		if(is_comma_or_logical_3($inparr[$i])){
			return $i;
		}
	}
}

function sep_n_prep_bl_3($inparr,$pos){
	return sep_dep_clause_3($inparr,$pos);
}

function c_n_1st_prep_3($inparr){
	foreach($inparr as $pos=>$elem){
		if(is_prep_3($elem)){
			//need to check
			//example:
			//higher-speed successor to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips
			return $pos;
		}
	}
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
	if(count($outparr[1])==1){
		$outparr[1]=$outparr[1][0];
	}
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

function sep_first_word_3($inparr){
	$outparr[]=$inparr[0];
	if(isset($inparr['inquotes'])){
		$outparr['inquotes']=$inparr['inquotes'];
		unset($inparr['inquotes']);
	}
	$outparr[]=array_slice($inparr,1);
	if(count($outparr[1])==1){
		$outparr[1]=$outparr[1][0];
	}
	return $outparr;
}

function order_a_complex_verb_3($inparr){
	if(is_simple_or_ordered_3($inparr)){
		//this is simple verb
		return $inparr;
	}
	//if there are 2 verbs , without "top suffixes", joined with comma or logical operator, should be separated.
	//need to check:
	//be neither forward nor backward compatible with any earlier type of random access memory (RAM) due to different signaling voltages, timings, and other factors
	$pos=last_logical_with_comma_pos_3($inparr);
	//assume for now it does not belong to depenent clause
	//... n , n+1 and n+2 be n+3 ...
	if( $pos!==null && is_verb_3($inparr[$pos+2])){
		//almost same 'code' is in order_a_complex_noun_3 function
		//separate comma part out
		$outparr=sep_comma_or_logical_bl_3($inparr,$pos);
		//get comma out
		$outparr[0]=o_logic_bl_norecurs_3($outparr[0]);
		//get logical out
		$outparr[0][0]=o_logic_bl_norecurs_3($outparr[0][0]);
		//return $outparr;
		$outparr[0][0][0]=order_a_complex_verb_3($outparr[0][0][0]);
		//main part
		$outparr[1]=order_a_complex_verb_3($outparr[1]);
		return $outparr;
	}
	//else
	//2 verbs should be separated here, i mean "have been" etc
	if(
		$inparr[0]['w']=='have'
		&&$inparr[1]['w']=='be'
		&&$inparr[2]['w']=='ed-pp'
	){
		$outparr=sep_first_main_3($inparr);
		// 0 be ed in use ... 1 have
		$outparr[0]=sep_top_verb_suf_3($outparr[0],1);
		$outparr[0][0]=order_a_complex_verb_3($outparr[0][0]);
		return $outparr;
	}
	//else
	$pos=verb_last_c_adv_or_prep_pos_3($inparr);
	if($pos!==null){
		$outparr=sep_last_c_adv_or_prep_bl_3($inparr,$pos);
		//0 prep bl 1 verb
		if(!is_comma_or_logical_3($inparr[$pos-1])){
			//normal prepositional block
			// outparr[0] is last prep block
			$outparr[0]=o_c_prep_bl_3($outparr[0]);
			// outparr[1] is complex verb
			$outparr[1]=order_a_complex_verb_3($outparr[1]);
		}else{
			//comma before preposition and several prepositional blocks can be set into a block
			$comma_or_logical=array_splice($outparr[1],-1);
			$comma_or_logical=$comma_or_logical[0];
			$outparr[0]=array($outparr[0],$comma_or_logical);
			//0 ( 0 prep bl 1 , ) 1 verb
			//find second prep block from end
			$pos2=verb_last_c_adv_or_prep_pos_3($outparr[1]);
			if($pos2!==null){
				$outparr[1]=sep_last_c_adv_or_prep_bl_3($outparr[1],$pos2);
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
		//no preposition nor adverb block
		$outparr=o_c_verb_without_prep_and_adv_3($inparr);
		/*
		//find simple adverb
		$pos=first_adv_pos_3($inparr);
		if($pos!==null){
			//adverb is found
			//example: take quickly a book
			$outparr=sep_an_adv_3($inparr,$pos);
			//no adverb here:
			//example: take a book
			$outparr[1]=o_c_verb_without_prep_and_adv_3($outparr[1]);
		}else{
			//no adverb
			//example: take a book
			$outparr=o_c_verb_without_prep_and_adv_3($inparr);
		}
		*/
	}
	return $outparr;
}

function is_verb_3($elem){
	global $verbs;
	//if($elem['w']=='be'){
	if(isset($verbs[$elem['w']])){
		return true;
	}
}

function sep_first_main_3($inparr){
	$main=array_splice($inparr,0,1);
	$outparr[]=fix_one_element_in_array($inparr);
	$outparr[]=$main[0];
	return $outparr;
}

function fix_one_element_in_array($inparr){
	//inparr should be
	//array(array('w'=>'...'))
	//or
	//array(array('w'=>'...'),array('w'=>'...'),)
	if(count($inparr)==1){
		$inparr=$inparr[0];
	}
	return $inparr;
}

function o_c_prep_bl_3($inparr){
	if(is_simple_or_ordered_3($inparr)){
		if(is_prep_3($inparr[1])){
			return $inparr;
		}
	}
	if(is_prep_3($inparr[0])){
		//example: with ...
		// $prep=$inparr[0];
		// array_splice($inparr,0,1);
		// $outparr[]=$inparr;
		// $outparr[]=$prep;
		//$outparr[]=array_slice($inparr,1);
		$inner=array_slice($inparr,1);
		if(count($inner)==1){$inner=$inner[0];}
		$outparr[]=order_a_complex_noun_3($inner);
		$outparr[]=$inparr[0];
	}elseif(
		$inparr[0]['w']=='due'
		||$inparr[0]['w']=='similar'
	){
		//example: similar to ...
		// $prep=$inparr[0];
		// array_splice($inparr,0,1);
		// $outparr[]=o_c_prep_bl_3($inparr);
		// $outparr[]=$prep;
		$outparr[]=o_c_prep_bl_3(array_slice($inparr,1));
		$outparr[]=$inparr[0];
	}
	return $outparr;
}

function o_c_verb_without_prep_and_adv_3($inparr){
	//order complex verb without prepostion blocks and without adverbs but with object and may be something else strange
	//find object
	//example: take a book
	$pos=first_c_nounlike_pos_3($inparr);
	if($pos!==null){
		$outparr=sep_object_3($inparr,$pos);
		$outparr[0]=order_a_complex_noun_3($outparr[0]);
		//$outparr[1]=
	}else{
		//may be this is "be", adjective may be object
		// if(==be){
		// }
		//else
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

function first_c_nounlike_pos_3($inparr){
	foreach($inparr as $pos=>$elem){
		if(is_nounlike_3($elem)||is_article_3($elem)){
			return $pos;
		}
	}
}

function is_nounlike_3($elem){
	global $nounlikes;
	if(isset($nounlikes[$elem['w']])){
		return true;
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

function sep_last_c_adv_or_prep_bl_3($inparr,$pos){
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
		||is_logical_3($elem)
	){
		return true;
	}
}

function verb_last_c_adv_or_prep_pos_3($inparr){
	for($i=count($inparr)-1;$i>=0;$i--){
		if(is_prep_3($inparr[$i])&&$inparr[$i]['w']!='of'){
			if(
				$inparr[$i-1]['w']=='due'
				||$inparr[$i-1]['w']=='similar'
				||$inparr[$i-1]['w']=='compatible'
			){
				if(!is_adv_3($inparr[$i-2])){
					return $i-1;
				}else{
					//... backward compatible with ...
				}
			}else{
				//need to check
				//example:
				//It is the higher-speed successor to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips.
				if(
					$inparr[$i]['w']=='to'
					&&(
						$inparr[$i-1]['w']=='predecessor'
						||$inparr[$i-1]['w']=='successor'
					)
				){
					//default return false
				}else{
					return $i;
				}
			}
		}
	}
}

function is_prep_3($elem){
	if(
		$elem['w']=='with'
		||$elem['w']=='to'
		||$elem['w']=='from'
		||$elem['w']=='of'
		||$elem['w']=='in'
		||$elem['w']=='for'
		||$elem['w']=='since'
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

function sep_last_dot_or_suf_3($inparr){
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
		$elem['w']=='v-0'//present plural (they go, we go, you go) or 1st or 2nd person singular (i go, thou go) or 3d person singular (he will, he may, he can)
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







