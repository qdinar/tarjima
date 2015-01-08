<?php


function &tr_simple_block_2(&$simbl){

	global $words,$dic,$recursionlevel,$mwdic,$nounlikes,$verbs;
	$recursionlevel++;
	//echo'in level '.$recursionlevel.'<pre>';print_r($simbl);echo'</pre>';echo'*';
	//if($recursionlevel==11){echo'in level '.$recursionlevel.'<pre>';print_r($simbl);echo'</pre>';echo'*';}
	$s2=array();
	foreach($mwdic as $mw){
		if(is_mw_eq($simbl,$mw['en'],$getinner)){
			// if($getinner===NULL){
				// echo'NULL:<pre>';
				// print_r($simbl);
				// print_r($mw['en']);
				// echo'</pre>';
			// }
			//echo'OK';exit;
			//echo'*';
			// echo'<pre>';
			// print_r($mw);
			// echo'</pre>';
			//if(isset($getinner)&&isset($getinner[0])){
			//if(!isset($getinner['w'])&&isset($getinner[0])){
			if($getinner!==NULL){
				if(!isset($getinner['w'])){
					$getinner=&tr_simple_block_2($getinner);
				}else{
					//NULL
					// if(!isset($getinner['w'])&&!isset($getinner[0])){
						// echo'<pre>';
						// var_dump($getinner);
						// echo'</pre>';
						// exit;
					// }
					//$getinner=tr_simple_block_2($getinner);
					//translate_single_modifier_part($simbl,$s2);
					if(isset($getinner['tr'])){
						$getinner['w']=$getinner['tr'];
					}
					else
					if(isset($words[$getinner['w']])){
						$getinner['w']=$words[$getinner['w']];
					}elseif(isset($nounlikes[$getinner['w']])){
						$getinner['w']=$nounlikes[$getinner['w']]['tt'];
					}elseif(isset($verbs[$getinner['w']])){
						$getinner['w']=$verbs[$getinner['w']]['tt'];
					}
				}
			}
			assign_mw_tr($s2,$mw['tt'],$getinner,$simbl);
			unset($getinner);
			//echo'*';
			//print_r( $s2 );
			return $s2;
		}
	}
	//$mainw=get_main_word($simbl[0]);
	// $mainw=&get_main_word_ref($simbl[0]);
	// if($mainw['w']=='high'){
		// $mainw['ok']='ok';//echo 'OK';exit;
	// }
	// if(isset($simbl[0]['w'])&&$simbl[0]['w']=='high'){//if('high'==$simbl[0]['w']){
		// $simbl[0]['ok']='ok';
	// }
	//echo'send to mw from <pre>';var_dump($simbl);echo'</pre>';
	//if($simbl[0]===null)echo'!!!';
	$mainw=&get_main_word_ref($simbl[0]);
	if($mainw['w']=='high'){
		$mainw['tr']='югары';//echo 'OK';exit;
	}
	//---------------------------------------------------------------------translate 1 of 2
	if(!isset($simbl[0]['w'])){
		$s2[0]=&tr_simple_block_2($simbl[0]);
	}else{
		//translate_single_modifier_part($simbl,$s2);
		$s2[0]=$simbl[0];
		if(isset($simbl[0]['tr'])){
			$s2[0]['w']=$simbl[0]['tr'];
		}
		else
		if(isset($words[$simbl[0]['w']])){
			$s2[0]['w']=$words[$simbl[0]['w']];
		}elseif(isset($nounlikes[$simbl[0]['w']])){
			$s2[0]['w']=$nounlikes[$simbl[0]['w']]['tt'];
		}elseif(isset($verbs[$simbl[0]['w']])){
			$s2[0]['w']=$verbs[$simbl[0]['w']]['tt'];
		}
	}
	// if(isset($simbl[0]['ok'])){
		// $s2[0]['ok']='ok';
	// }
	//add ны
	apply_fixes_after_0($simbl,$s2);
	//
	//
	//
	//
	//---------------------------------------------------------------------translate 2 of 2
	if(!isset($simbl[1]['w'])){
		$s2[1]=&tr_simple_block_2($simbl[1]);
	}else{
		translate_single_main_part($simbl,$s2);
	}
	apply_fixes_after_1($simbl,$s2);
	/*
	//these work incorrectly when order is changed
	if(isset($simbl[0]['firstiscapital'])){
		$s2[0]['firstiscapital']=$simbl[0]['firstiscapital'];
	}
	if(isset($simbl[1]['firstiscapital'])){
		$s2[1]['firstiscapital']=$simbl[1]['firstiscapital'];
	}
	//these work incorrectly when order is changed
	foreach($simbl[0] as $key=>$word){
		if(!isset($s2[0][$key])){
			$s2[0][$key]=$simbl[0][$key];
		}
	}
	foreach($simbl[1] as $key=>$word){
		if(!isset($s2[1][$key])){
			$s2[1][$key]=$simbl[1][$key];
		}
	}*/
	
	//---------------------------------------------------------------------translate >2
	//translate following words , with index>1
	for($i=2;$i<count($simbl);$i++){
		if(isset($simbl[$i]['w'])){
			$s2[]=$simbl[$i];
			$latest_i=count($s2)-1;
			if(isset($simbl[$i]['tr'])){
				$s2[$latest_i]['w']=$simbl[$i]['tr'];
			}
			else
			if(isset($words[$simbl[$i]['w']])){
				$s2[$latest_i]['w']=$words[$simbl[$i]['w']];
			}elseif(isset($nounlikes[$simbl[$i]['w']])){
				$s2[$latest_i]['w']=$nounlikes[$simbl[$i]['w']]['tt'];
			}elseif(isset($verbs[$simbl[$i]['w']])){
				$s2[$latest_i]['w']=$verbs[$simbl[$i]['w']]['tt'];
			}elseif($simbl[$i]['w']=='s-pl'){
				if($s2[$latest_i-1]['w']!='ы'){
					$s2[$latest_i]['w']='лар';
				}else{
					$s2[$latest_i-1]['w']='лар';
					$s2[$latest_i]['w']='ы';
				}
			}
			if(
				$simbl[$i-1]['w']=='of'
				||
				$simbl[$i-1]['w']=='DRAM'
				&&$simbl[$i]['w']=='array'
				||
				$simbl[$i-1]['w']=='memory'
				&&$simbl[$i]['w']=='chip'
				||
				$simbl[$i-1]['w']=='DRAM'
				&&$simbl[$i]['w']=='interface'
				||
				$simbl[$i-1]['w']=='interface'
				&&$simbl[$i]['w']=='specification'
				||
				$simbl[$i-2]['w']=='ing'
				&&$simbl[$i]['w']=='s-pl'
				||
				$simbl[$i-1]['w']=='DDR3'
				&&$simbl[$i]['w']=='SDRAM'
				||
				$simbl[$i-1]['w']=='data'
				&&$simbl[$i]['w']=='rate'
			){
				$s2[]=array('w'=>'ы');
			}
		}
	}
	
	foreach($simbl as $key=>$word){
		if(!isset($s2[$key])){
			$s2[$key]=$simbl[$key];
		}
	}
	//echo'out level '.$recursionlevel.'<pre>';print_r($simbl);echo'</pre>';echo'*';
	$recursionlevel--;
	return $s2;

}

function translate_single_modifier_part(&$simbl,&$s2){
	global $nounlikes,$verbs,$words;
	$s2[0]=$simbl[0];
	if(isset($simbl[0]['tr'])){
		$s2[0]['w']=$simbl[0]['tr'];
	}
	else
	if(isset($words[$simbl[0]['w']])){
		$s2[0]['w']=$words[$simbl[0]['w']];
	}elseif(isset($nounlikes[$simbl[0]['w']])){
		$s2[0]['w']=$nounlikes[$simbl[0]['w']]['tt'];
	}elseif(isset($verbs[$simbl[0]['w']])){
		$s2[0]['w']=$verbs[$simbl[0]['w']]['tt'];
	}
}


function apply_fixes_after_0(&$simbl,&$s2){
	//global $words,$dic,$recursionlevel,$mwdic,$nounlikes;
	global $nounlikes,$verbs,$dic;
	//add не
	if(
		//((isset($simbl[1][1]['w'])&&$simbl[1][1]['w']=='read')||(isset($simbl[1]['w'])&&$simbl[1]['w']=='read'))
		(
			$dic[$simbl[1][1]['w']]['type']=='verb'
			//&&$simbl[1][1]['w']!='be'
			||$dic[$simbl[1]['w']]['type']=='verb'
			//&&$simbl[1]['w']!='be'
			||isset($verbs[$simbl[1]['w']])
		)
		&&
		(
		isset($simbl[0][0]['w'])&&$simbl[0][0]['w']=='the'
		||
		isset($simbl[0]['w'])&&isset($simbl[0]['thisisabbreviation'])
		||
		isset($simbl[0]['thisisheader'])
		)
	){
		// if(isset($simbl[0]['w'])&&$simbl[0]['thisisabbreviation']==true&&substr($simbl[0]['w'],-1)=='3'){
			// $suffiksno='не';
		// }else{
			// $suffiksno='ны';
		// }
		//i had here problem, could not move 'thisisheader' properly, code at bottom also changed it
		//i have tried and deleted many codes here...
		//i am sorry i have tried this at 0-1 of clock at night and could not solve
		//i think it is unproductive to work at night. but i have checked/tested that one more time...
		//now i will just add 'ны' and then i am going to make new functions... - i have made a function but have deleted it. problem is solved
		// $s2[0]=array($s2[0],array('w'=>$suffiksno));
		//echo'OK';
		if(
			$simbl[1][1]['w']=='be'
			||$simbl[1]['w']=='be'
		){
			//$s2[0]=array($s2[0],array('w'=>'ы'));
		}else{
			//$s2[0]=array(array($s2[0],array('w'=>'ы')),array('w'=>'не'));
			$s2[0]=array($s2[0],array('w'=>'не'));
		}
	}
	//
	if(isset($simbl[0][1]['w'])&&$simbl[0][1]['w']=='from'){
		if($simbl[1]['w']=='go'){
			//$s2[1]['w']='кайт';
		//}elseif($simbl[1][1]['w']=='go'){
		//	$s2[1][1]['w']='кайт';
			$s2[0][1]['w']='тарафыннан';//instead of тан in dic
		}
	}
	if(isset($simbl[0]['w'])&&$simbl[0]['w']=='last'&&$simbl[1]['w']=='year'){
		//echo'*';
		//var_dump($s2);
		$s2[0]['w']='узган';//instead of соңгы in dic
		//does not work, i do not know why. found. == instead of =.
		//var_dump($s2);
	}
	// if(isset($simbl[0][1]['w'])&&$simbl[0][1]['w']=='er-comp'&&$simbl[0][0]['w']&&$simbl[0][0]['w']=='high'){
		// if($simbl[1]['w']=='speed'){
			// $s2[0][0]['w']='югары';//instead of биек in dic
		// }
	// }
	//need to make also "very high" etc// done, see line 30
	//get_main_word is not enough fot that
	//if(get_main_word($simbl[0])=='high'){
	// if(isset($simbl[0]['w'])&&$simbl[0]['w']=='high'){
		// if($simbl[1]['w']=='speed'){
			// $s2[0]['w']='югары';//instead of биек in dic
		// }
	// }
	/*
	if(!isset($simbl[0]['w'])){
		$sb0mainword=get_main_word($simbl[0]);
		//echo'*';print_r($sb0mainword);
		//echo'*';print_r($simbl[0]);
		//if(isset($simbl[1]['w'])){
			//$sb1mainword=$simbl[1];
		//}else{
			$sb1mainword=get_main_word($simbl[1]);
		//}
		//if($sb1mainword['w']=='interface'){
			//echo'*';
			//echo'<pre>';
			//print_r($simbl[0]);
			//echo'</pre>';
			//print_r($sb0mainword);
		//}
		if(
			isset($nounlikes[$sb1mainword['w']])&&$nounlikes[$sb1mainword['w']]['type']=='noun'
			&&
			isset($nounlikes[$sb0mainword['w']])&&$nounlikes[$sb0mainword['w']]['type']=='noun'
		){
			//echo'*!'.$sb0mainword['w'].'-'.$sb1mainword['w'];
			// $lastword=get_main_word($s2[0]);
			// if(is_soft($lastword['w'])){
				// $limorphem='ле';
			// }else{
				// $limorphem='лы';
			// }
			$mwadj=$s2[0];
			//$s2[0]=array($mwadj,array('w'=>' '.$limorphem));
			// $s2[0]=array($mwadj,array('w'=>$limorphem));
			if($sb0mainword['w']=='type'){
				$s2[0]=array($mwadj,array('w'=>'дәге'));
			//}else{
				//$s2[0]=array($mwadj,array('w'=>'ле'));
			}
		}
	}
	*/
	if(isset($simbl[0][1]['w'])&&$simbl[0][1]['w']=='with'){
		//if(isset($simbl[0][0]['w'])){
			//$sb0mainword=$simbl[0][0];
		//}else{
			//if($simbl[0][0]===null)echo'!!!';
			$sb0mainword=get_main_word($simbl[0][0]);
		//}
		//if(isset($simbl[1]['w'])){
			//$sb1mainword=$simbl[1];
		//}else{
			//if($simbl[1]===null)echo'!!!';
			$sb1mainword=get_main_word($simbl[1]);
		//}
		//echo'ok';
		if(
			isset($nounlikes[$sb1mainword['w']])&&$nounlikes[$sb1mainword['w']]['type']=='noun'
			&&
			isset($nounlikes[$sb0mainword['w']])&&$nounlikes[$sb0mainword['w']]['type']=='noun'
		){
			//$s2[0][1]['w']=' лы';
			//$s2[0][1]['w']='лы';
			$s2[0][1]['w']='ле';
		}
	}
	
}




function translate_single_main_part(&$simbl,&$s2){
	global $dic,$nounlikes,$words,$verbs;
	$s2[1]=$simbl[1];
	//if(!isset($s2[1])){//if it is array, it is set. if it is word and is not set , set here
		//$s2[1]['w']=$words[$simbl[1]['w']];
		if(isset($words[$simbl[1]['w']])){
			$s2[1]['w']=$words[$simbl[1]['w']];
		}elseif(isset($nounlikes[$simbl[1]['w']])){
			$s2[1]['w']=$nounlikes[$simbl[1]['w']]['tt'];
		}elseif(isset($verbs[$simbl[1]['w']])){
			$s2[1]['w']=$verbs[$simbl[1]['w']]['tt'];
		}else{
			$s2[1]=$simbl[1];
		}
	//}
	if(isset($simbl[1]['tr'])){
		$s2[1]['w']=$simbl[1]['tr'];
	}
	else
	if($simbl[1]['w']=='ed-pp'){
		//$s2[1]['w']='лгән';
		//this place is translation, not just fixing of ready translation, for that i copy here all properties
		if(isset($simbl[0]['w'])){
			$ofplace2=$simbl[0]['w'];
		}else{
			$ofplace2=false;
		}
		if(isset($simbl[0][1]['w'])){
			$ofplace1=$simbl[0][1]['w'];
		}else{
			$ofplace1=false;
		}
		// if($ofplace1=='know'||$ofplace2=='know'){
			// $s2[1]['w']='енгән';
		// }elseif($ofplace1=='mention'||$ofplace2=='mention'){
			// $s2[1]['w']='ынган';
		// }elseif($ofplace1=='build'||$ofplace2=='build'){
			// $s2[1]['w']='лгән';
		// }else{
			// $s2[1]['w']='лган';
		// }
		$s2[1]['w']='лгән';
	}elseif($simbl[1]['w']=='have'){
		if(!isset($simbl[0]['w'])&&$simbl[0][1]['w']=='ed-pp'){
			//$s2[1]['w']='ды';
			//$s2[0]['w']=$s2[0][0];//$s2[0][1]['w']=translation of ed-pp becomes removed

			//commented out previous way while reordering ды, and write this
			//$s2[1]['w']='have';

			//i should translate this if this is imperative
			//i can translate this {п куй}
			//commented out previous code again
			/*
			$s2[1]=$simbl[1];
			$s2[1]['w']='куй';
			$s2[0][1]['w']='п';
			*/
			/*
			if(
				$simbl[0][1]['w']=='ed-pp'
				&&$simbl[0][0][1][1]['w']=='be'
			){
				//ddrsdram {is ... and has been in ...}
				//remove "була" - copula "is" translation
				$part1=$s2[0][0][0];//since 2007
				$part2=$s2[0][0][1][0];//in use
				$s2[0]=$part1;
				$s2[1]=$part2;
				$s2['dash']=true;
			}
			*/
		}
	}elseif(
		$simbl[1]['w']=='s'||$simbl[1]['w']=='ed'||$simbl[1]['w']=='pr-si'
		||$simbl[1]['w']=='v-0'||$simbl[1]['w']=='v-s'||$simbl[1]['w']=='v-re'
	){
		//if($simbl[0][0]['w']=='whom'){
		//	echo'*';
		//}
		//echo'*<pre>';
		//print_r($simbl);
		//echo'</pre>';
		//return $s2[0];//$s2[1] should be translation of s but it becomes removed
		//i see 'ды' should be reordered...
		if(isset($simbl[0][1][1]['w'])&&$simbl[0][1][1]['w']=='have'&&$simbl[0][1][0][1]['w']=='ed-pp'){
			$s2[0]/*hehave..*/[1]/*haveread..*/=$s2[0][1][0]/*read(pp)...*/[0]/*read...*/;
			//have read ed-pp is translated like read
			$s2[1]=$simbl[1];
			//$s2[1]['w']='ды';//past tense morphem is in place of s
			$s2[1]['w']='де';
			//'ды' is reordered . subject is like adverbs etc, also in tatar...
		}elseif(
			$simbl[0][0]['w']=='that'||$simbl[0][0]['w']=='whom'
		){
			//if($simbl[0][0]['w']=='whom'){
			//	echo'*';
			//}
			if(
				(
					$simbl[0][1][1]['w']=='be'
					||$simbl[0][1][1]['w']=='have'
				)
				&&$simbl[0][1][0][1]['w']=='ed-pp'
			){
			//if (($simbl[0]/*whom..met*/[1]/*we..met*/[1]/*havemet*/=='be'||$simbl[0][1][1]['w']=='have')&&$simbl[0][1][0]/*we*/[1]['w']=='ed-pp'){
				//echo'*';
				$verb=$s2[0][1][0][0];
				$verbending=$s2[0][1][0][1];
				$s2[0]=$verb;
				$s2[1]=$verbending;
				//кайсыискәалынганбулды->искәалынган
				/*Array
				(
					[0] => Array
						(
							[0] => that
							[1] => Array
								(
									[0] => Array
										(
											[0] => mention
											[1] => ed-pp
										)

									[1] => be
								)

						)

					[1] => ed
				)*/
			//}elseif($simbl[0][0]['w']=='whom'){
				//echo'*';
			}elseif(
				(
					$simbl[0]/*whom..met*/[1]/*we...met*/[1]/*havemet*/[1]/*have*/['w']=='have'
					||$simbl[0][1][1][1]['w']=='be'
				)
				&&$simbl[0][1][1][0]/*met*/[1]/*ed-pp*/['w']=='ed-pp'
			){
				/*Array
				(
					[0] => Array
						(
							[0] => whom
							[1] => Array
								(
									[0] => we
									[1] => Array
										(
											[0] => Array
												(
													[0] => meet
													[1] => ed-pp
												)

											[1] => have
										)

								)

						)

					[1] => pr-si
				)
				*/
				//echo'*';
				$subject=$s2[0][1][0];
				$verb=$s2[0][1][1][0][0];
				$s2[0][0]=$subject;
				$s2[0][1]=$verb;
				$s2[1]=$simbl[1];
				//$s2[1]['w']='кан';//this works, but probably is buggy with other sentences
				$s2[1]['w']='ган';
			//}else{
				//$s2[1]['w']=$words[$simbl[1]['w']];
			}elseif(
				$dic[$simbl[0][1][1]['w']]['type']=='verb'
				||isset($verbs[$simbl[0][1][1]['w']])
			){
				// кайсы бер велосипед сатыпал ды -> велосипед сатыпал ган
				/*Array
				(
					[0] => Array
						(
							[0] => that
							[1] => Array
								(
									[0] => Array
										(
											[0] => a
											[1] => bycicle
										)

									[1] => buy
								)

						)

					[1] => ed
				)*/
				$s2[0]=$s2[0][1];
				$s2[1]=$simbl[1];
				//$s2[1]['w']='ган';
				$s2[1]['w']='учы';
				if($simbl[0][1][0][0]['w']=='a'){
					$s2[0][0]=$s2[0][0][1];
					/*Array
					(
						[0] => Array
							(
								[0] => Array
									(
										[0] => бер
										[1] => велосипед
									)

								[1] => сатыпал
							)

						[1] => ган
					)*/
				}
			}
		//}else{
			//$s2[1]['w']=$words[$simbl[1]['w']];
			//echo'*';
			//var_dump($simbl);
		//}
		//elseif(isset($simbl[0][1][1]['w'])&&$simbl[0][1][1]['w']=='be'){
		//elseif($simbl[0][1][1][1]['w']=='be'){//after "s" has become shared for is and has
			/*
			//remove "була" - copula "is" translation
			$subj=$s2[0][0];//0:ddrbe...//00:ddr
			//$obj=$s2[0][1][0];//0:ddrbe...//01:be...//010:...
			$obj=$s2[0][1][1][0];//after "s" has become shared for is and has
			//^//0:ddrbe...andhave...//01:be...andhave...//011:be...//0110:...
			$andb=$s2[0][1][0];//after "s" has become shared for is and has
			$s2[0]=$subj;
			//$s2[1]=$obj;
			$s2[1]=array($andb,$obj);
			//unset($subj,$obj);
			unset($subj,$obj,$andb);//after "s" has become shared for is and has
			$s2[1]['dash']=true;
			*/
		//}
		//elseif($simbl[0][1][1][1][1]['w']=='be'){
			//0subjbe...1prsi
			//00subj01be...
			//011be...010dueto...
			//0111be...0110with...
			//01111be01110...
			// $s2[0][1][1][1]=$s2[0][1][1][1][0];
			// $s2=$s2[0];
			// $s2[1]['dash']=true;
		//}elseif(isset($simbl[0][1]['w'])&&$simbl[0][1]['w']=='be'){
			//ddrsdram {is ... and has been in ...}
			//remove "була" - copula "is" translation
			// $s2=$s2[0][0];
			// $s2['dash']=true;
		}else{
			//$lastw=get_tr_last_word($simbl[0]);
			// if($simbl[0]){
			// }
			//echo'***';show_tree_3($simbl[0]);
			$lastwupper=&get_main_word_upper_ref($s2[0]);
			//echo'OK:';show_tree_3($lastwupper);
			if($lastwupper[1]['w']=='бул'){
				$lastwupper=$lastwupper[0];
				$lastwupper['dash']=true;
			}
			$s2=$s2[0];
			//echo'OK2:';show_tree_3($lastwupper);
		}
		/*
		elseif(
			isset($simbl[0][1]['w'])&&$simbl[0][1]['w']=='have'
			&&isset($simbl[0][0][1]['w'])&&$simbl[0][0][1]['w']=='ed-pp'
			&&isset($simbl[0][0][0][1][1]['w'])&&$simbl[0][0][0][1][1]['w']=='be'
		){
			//ddrsdram {is ... and has been in ...}
			//remove "була" - copula "is" translation
			$part1=$s2[0][0][0][0];//since 2007
			$part2=$s2[0][0][0][1][0];//in use
			$s2[0]=$part1;
			$s2[1]=$part2;
			$s2['dash']=true;
		}
		*/
	}elseif($simbl[1]['w']=='type'){
		if(isset($simbl[0]['w'])&&$simbl[0]['w']=='three'){
			$num=$s2[0];
			//$s2[0]=array($num,array('w'=>'енче'));
			$s2[0]=array($num,array('w'=>'нче'));
			//$s2[1]['w']='төрдәге';//instead of төр
		}
	}elseif($simbl[1]['w']==',,'){
		if($simbl[0][0]['w']=='the'){
			$s2=$s2[0];
		}
	//}else{
		//$s2[1]['w']=$words[$simbl[1]['w']];
	}elseif($simbl[1]['w']=='s-pl'){
		$s2[1]['w']='лар';
	// }elseif($simbl[1]['w']=='v-re'){
		// $s2[1]['w']='алар';
	// }elseif($simbl[1]['w']=='v-0'){
		// $s2[1]['w']='й';
	// }elseif($simbl[1]['w']=='v-s'){
		// $s2[1]['w']='й';
	}elseif($simbl[1]['w']=='nor'){
		$s2[1]['w']='һәм түгел';
	}
	//if($recursionlevel==13){echo'level '.$recursionlevel.'<pre>';var_dump($simbl);echo'</pre>';echo'*';}
	//should not test simbl0 and simbl1 here because this place do not work if they are complex

	// foreach($simbl[1] as $key=>$word){
		// if(!isset($s2[1][$key])){
			// $s2[1][$key]=$simbl[1][$key];
		// }
	// }
}



function apply_fixes_after_1(&$simbl,&$s2){
	//global $words,$dic,$recursionlevel,$mwdic,$nounlikes;
	global $dic,$nounlikes,$verbs;
	//unset($mainw);
	//if($simbl[1]===null)echo'!!!';
	$mainw=get_main_word($simbl[1]);
	//echo'ok';
	//if($recursionlevel==8){echo 'OK<pre>' ; var_dump($mainw); echo'</pre>'; }
	if($mainw['w']=='successor'||$mainw['w']=='predecessor'){
		//global $jjj;$jjj++;if($jjj==1){echo 'OK';echo '<pre>';print_r($simbl);exit;}
		//$mainw['tr']='югары';//echo 'OK';exit;
		if(isset($simbl[0][1]['w'])&&$simbl[0][1]['w']=='to'){
			//echo'level '.$recursionlevel.'<pre>';var_dump($simbl);echo'</pre>';echo'*';
			$s2[0][0]=$s2[0];
			$s2[0][1]=array('w'=>'карата');
		}
	}
	if(isset($simbl[1]['w'])){
		if(
			$simbl[1]['w']=='s'||$simbl[1]['w']=='ed'||$simbl[1]['w']=='pr-si'
			||$simbl[1]['w']=='v-0'||$simbl[1]['w']=='v-s'||$simbl[1]['w']=='v-re'
		){
			if(isset($simbl[0][0]['w'])&&$simbl[0][0]['w']=='i'){
				$new_s2=array();
				$new_s2[0]=$s2;
				$new_s2[1]['w']='м';
				$s2=$new_s2;
				unset($new_s2);
			}elseif(isset($simbl[0][0]['w'])&&$simbl[0][0]['w']=='we'){
				$new_s2=array();
				$new_s2[0]=$s2;
				$new_s2[1]['w']='быз';
				$s2=$new_s2;
				unset($new_s2);
			}
			if(
				$simbl[1]['w']=='pr-si'||$simbl[1]['w']=='s'
				||$simbl[1]['w']=='v-0'||$simbl[1]['w']=='v-s'||$simbl[1]['w']=='v-re'
			){
				//i was going to fix йөреа to йөри but have found important order bug
				//that is fixed. continue
				if(isset($simbl[0][1][1]['w'])&&$simbl[0][1][1]['w']=='walk'){
					$s2[1]['w']='й';
				}
				
			}
		}
		else
		if(
			isset($simbl[1]['thisisabbreviation'])
			&&substr($simbl[0]['w'],-1)=='s'
		){
			$s2[1]=array($s2[1],array('w'=>'ы'));
		}else
		if($simbl[1]['w']=='.'){//top of sentence
			//echo'*';
			if(isset($simbl[0][1][1]['w'])&&isset($dic[$simbl[0][1][1]['w']])&&$dic[$simbl[0][1][1]['w']]['type']=='verb'){
				$s2[0][0]=array($s2[0][0],$s2[0][1]);
				$s2[0][1]=array('w'=>'гыз');
			}
		}
	}
	//if($simbl[1]===null)echo'!!!';
	$sb0mainword=get_main_word($simbl[0]);
	$sb1mainword=get_main_word($simbl[1]);
	if(
		(
			$sb0mainword['w']=='access'
			||$sb0mainword['w']=='rate'
		)
		&&$sb1mainword['w']=='memory'
		||
		$sb0mainword['w']=='bandwidth'
		&&$sb1mainword['w']=='interface'
		||
		$sb0mainword['w']=='speed'
		&&$sb1mainword['w']=='successor'
	){
		$s2[0]=array($s2[0],array('w'=>'ле'));
	}
	elseif(
		$sb0mainword['w']=='type'
		&&$nounlikes[$sb1mainword['w']]['type']=='noun'
	){
		$s2[0]=array($s2[0],array('w'=>'дәге'));
	}
	elseif(
		$sb0mainword['w']=='of'
		||
		$sb0mainword['w']=='memory'
		&&$sb1mainword['w']=='chip'
		||
		$sb0mainword['w']=='DRAM'
		&&$sb1mainword['w']=='interface'
		||
		$sb0mainword['w']=='interface'
		&&$sb1mainword['w']=='specification'
		||
		$sb0mainword['w']=='ing'
		&&$sb1mainword['w']=='s-pl'
		||
		$sb0mainword['w']=='DDR3'
		&&$sb1mainword['w']=='SDRAM'
		||
		$sb0mainword['w']=='data'
		&&$sb1mainword['w']=='rate'
	){
		if(
			$s2[1][0][1]['w']==','
		){
			$s2[1][1]=array($s2[1][1],array('w'=>'ы'));
			$s2[1][0][0]=array($s2[1][0][0],array('w'=>'ы'));
		}else{
			$s2[1]=array($s2[1],array('w'=>'ы'));
		}
	}
	//&&!isset($sb0mainword['thisisabbreviation'])
	// $nounlikes[$sb0mainword['w']]['type']=='noun'
	if(isset($simbl[0]['w'])){
		/*
		if(
			isset($nounlikes[$simbl[0]['w']])&&$nounlikes[$simbl[0]['w']]['type']=='noun'&&!isset($simbl[0]['thisisabbreviation'])
		){
			//echo'*!'.$sb0mainword['w'].'-'.$sb1mainword['w'];
			//$mwadj=$s2[0];
			//$s2[0]=array($mwadj,array('w'=>'s'));
			//if(isset($s2[1]['w'])){
				//$lastword=$s2[1]['w'];
			//}else{
				$lastword=get_main_word($simbl[1]);
			//}
			if(isset($nounlikes[$lastword['w']])&&$nounlikes[$lastword['w']]['type']=='noun'){
				// if(is_soft($lastword['w'])){
					// $imorphem='е';
				// }else{
					// $imorphem='ы';
				// }
				// $s2[1]=array($s2[1],array('w'=>$imorphem));
				$s2[1]=array($s2[1],array('w'=>'ы'));
			}
		}
		*/
	//}elseif(!isset($s2[0]['w'])){
		//$trynin=get_main_word($s2[0]);
		//if($trynin['w']=='ның'){
		//if(isset($simbl[0]['w'])&&$simbl[0]['w']=='the'&&used_in_the_paragraph($mainw)){
		//just remove 'the'
		if($simbl[0]['w']=='the'&&!used_in_current_paragraph($mainw)){
			$s2=$s2[1];
		}
	}
	else
	//if(!isset($simbl[0]['w']))
	{
		//$main=get_main_word($simbl[0]);
		//if($main['w']=='of'){
		if(
			$simbl[0][1]['w']=='of'
			//||isset($simbl[0][1]['w'])&&$simbl[0][1]['w']=='ing'
		){
			//$lastword=get_main_word($s2[0][0]);
			//if(is_soft($lastword['w'])){
				//$s2[0][1]['w']='нең';
			//}//i move this upper
			//echo'*';
			//echo'<pre>';
			//print_r($s2[0]);
			//print_r($s2[1]);
			//print_r($s2);
			//print_r($simbl[1]);
			//echo'</pre>';
			//if($simbl[1]===null)echo'!!!';
			$lastword=get_main_word($simbl[1]);
			// if(is_soft($lastword['w'])){
				// $imorphem='е';
			// }else{
				// $imorphem='ы';
			// }
			// $s2[1]=array($s2[1],array('w'=>' '.$imorphem));
			/*
			if(
				isset($s2[1][0][1]['w'])
				&&$s2[1][0][1]['w']==','
			){
				$s2[1][1]=array($s2[1][1],array('w'=>'ы'));
				$s2[1][0][0]=array($s2[1][0][0],array('w'=>'ы'));
			}
			else
			if(
				$lastword['w']=='s'
				||
				$nounlikes[$lastword['w']]['type']=='noun'
			){
			//}else{
				$s2[1]=array($s2[1],array('w'=>'ы'));
			}
			*/
			//this works 2 times - adds it 2 times, somehow
			//simbl1 is 'a modern type' in 1st time and 's' in 2nd time
			//i think i have understood it. first time it is regular translation, second time it is external level translation, the inner level is set to current level again. so i will check this by english word "of" - and it works.
		}
		if($simbl[0][0]['w']=='neither'){//isset($simbl[0][0]['w'])&&
			//going to make
			// 0 {
				// 0 арткатаба да
				// 1 алгатаба да
			// }
			// 1 яраучы түгел
			//from
			// 0 {
				// 0 neither
				// 1 {
					// 0 {
						// 0 backward
						// 1 nor
					// }
					// 1 forward
				// }
			// }
			// 1 compatible
			$firstpart=$s2[0][1][1];//алга таба - forward
			$secondpart=$s2[0][1][0][0];//артка таба - backward
			$lastpart=$s2[1];
			$new_s2[0][0][0]=$secondpart;
			$new_s2[0][0][1]['w']='да';
			$new_s2[0][1][0]=$firstpart;
			$new_s2[0][1][1]['w']='да';
			$new_s2[1][0]=$lastpart;
			$new_s2[1][1]['w']='түгел';
			$s2=$new_s2;
			//works
		}
	}
	//apply_fixes_after_1_by_s2($s2);
	//if($s2[0]===null)echo'!!!';
	$lastw=get_main_word($s2[0]);
	if($s2[1]['w']=='лар'&&$lastw['w']=='ы'){
		$s2=$s2[0];
		$lastwupper=&get_main_word_upper_ref($s2);
		//echo'OK:<pre>';print_r($lastwupper);echo'</pre>';
		$lastwupper[0]=array($lastwupper[0],array('w'=>'лар'));
	}
	if($s2[1]['w']=='а'){
		if($s2[0][1][0][1]['w']=='һәм'){
			/*
			$s2[0][1][0][0]=array($s2[0][1][0][0],array('w'=>'а'));
			$s2[0][1][1]=array($s2[0][1][1],array('w'=>'а'));
			$s2=$s2[0];
			*/
			$lastw=get_tr_last_word($s2[0][1][0][0]);
			if($lastw['w']=='бул'||$lastw['w']=='тот'){
				$s2[0][1][0][0]=$s2[0][1][0][0][0];
			}
			$lastw=get_tr_last_word($s2[0]);
			if($lastw['w']=='бул'||$lastw['w']=='тот'){
				$s2[0][1][1]=$s2[0][1][1][0];
			}
		}else{
			$lastw=get_tr_last_word($s2[0]);
			if($lastw['w']=='бул'||$lastw['w']=='тот'){
				$s2[0][1]=$s2[0][1][0];
			}
		}
		$s2=$s2[0];
		$s2[1]['dash']=true;
	}
	if($s2[1]['w']=='лгән'){
		$lastw=get_tr_last_word($s2[0]);
		if($lastw['w']=='бул'){
			$s2[1]['w']='ган';
		}
	}
}

/*
function apply_fixes_after_1_by_s2(&$s2){
	//this is also used in assign_mw_tr, index 2 2 php line 534
	//global $words,$dic,$recursionlevel,$mwdic,$nounlikes;
	//global $dic,$nounlikes;
	//else
	if(isset($s2[1]['w'])){
		if($s2[1]['w']=='ды'){
			if(isset($s2[0][1][1][1]['w'])&&$s2[0][1][1][1]['w']=='йөре'){
				$s2[1]['w']='де';
			}
		}else
		//if($s2[1]['w']=='тан'&&isset($s2[0][1]['w'])&&(mb_substr($s2[0][1]['w'],-1)=='а'||mb_substr($s2[0][1]['w'],-1)=='я')){
		if($s2[1]['w']=='тан'){
			if(isset($s2[0][1]['w'])&&(mb_substr($s2[0][1]['w'],-1)=='а'||mb_substr($s2[0][1]['w'],-1)=='я')){
				$s2[1]['w']='дан';
			}
			//$s2[1]['w']='дан';
			else{
				$lastword=get_main_word($s2[0]);
				if(is_soft($lastword['w'])){
					$s2[1]['w']='дән';
				}
			}
		}else
		if($s2[1]['w']=='ның'){
			$lastword=get_main_word($s2[0]);
			if(is_soft($lastword['w'])){
				$s2[1]['w']='нең';
			}
		}
	//}
	//if(isset($s2[1]['w'])&&$s2[1]['w']=='тан'){
		//echo'*';
		// $lastword=get_main_word($s2[0]);
		// if(is_soft($lastword['w'])){
			// $s2[1]['w']='дән';
		// }
		elseif($s2[1]['w']=='кә'){
			$lastword=get_tr_last_word($s2[0]);
			if($lastword['w']=='DDR2'){
				$s2[1]['w']='гә';
			}elseif($lastword['w']=='ы'){
				$s2[1]['w']='на';
			}
		}
		elseif($s2[1]['w']=='рәк'){
			$lastword=get_tr_last_word($s2[0]);
			if($lastword['w']=='югары'){
				$s2[1]['w']='рак';
			}
		}
	}
}
*/











?>