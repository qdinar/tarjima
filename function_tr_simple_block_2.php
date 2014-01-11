<?php


function tr_simple_block_2($simbl){

	global $words,$dic,$recursionlevel,$mwdic;
	$recursionlevel++;
	$s2=array();
	foreach($mwdic as $mw){
		if(is_mw_eq($simbl,$mw['en'])){
			//echo'*';
			assign_mw_tr($s2,$mw['tt']);
			//echo'*';
			//print_r( $s2 );
			return $s2;
		}
	}
	if(!isset($simbl[0]['w'])){
		$s2[0]=tr_simple_block_2($simbl[0]);
	}else{
		$s2[0]=$simbl[0];
		$s2[0]['w']=$words[$simbl[0]['w']];
	}
	//add ны
	if(
		//((isset($simbl[1][1]['w'])&&$simbl[1][1]['w']=='read')||(isset($simbl[1]['w'])&&$simbl[1]['w']=='read'))
		(isset($simbl[1][1]['w'])&&isset($dic[$simbl[1][1]['w']])&&$dic[$simbl[1][1]['w']]['type']=='verb'
			||
			isset($simbl[1]['w'])&&isset($dic[$simbl[1]['w']])&&$dic[$simbl[1]['w']]['type']=='verb')
		&&
		(
		isset($simbl[0][0]['w'])&&$simbl[0][0]['w']=='the'
		||
		isset($simbl[0]['w'])&&isset($simbl[0]['thisisabbreviation'])
		||
		isset($simbl[0]['thisisheader'])
		)
	){
		if(isset($simbl[0]['w'])&&$simbl[0]['thisisabbreviation']==true&&substr($simbl[0]['w'],-1)=='3'){
			$suffiksno='не';
		}else{
			$suffiksno='ны';
		}
		//i had here problem, could not move 'thisisheader' properly, code at bottom also changed it
		//i have tried and deleted many codes here...
		//i am sorry i have tried this at 0-1 of clock at night and could not solve
		//i think it is unproductive to work at night. but i have checked/tested that one more time...
		//now i will just add 'ны' and then i am going to make new functions... - i have made a function but have deleted it. problem is solved
		$s2[0]=array($s2[0],array('w'=>$suffiksno));
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
	//
	//
	//
	//
	if(!isset($simbl[1]['w'])){
		$s2[1]=tr_simple_block_2($simbl[1]);
	}else{
		if($simbl[1]['w']=='ed-pp'){
			//$s2[1]['w']='лгән';
			$s2[1]=$simbl[1];
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
			if($ofplace1=='know'||$ofplace2=='know'){
				$s2[1]['w']='енгән';
			}elseif($ofplace1=='mention'||$ofplace2=='mention'){
				$s2[1]['w']='ынган';
			}elseif($ofplace1=='build'||$ofplace2=='build'){
				$s2[1]['w']='лгән';
			}else{
				$s2[1]['w']='лган';
			}
		}elseif($simbl[1]['w']=='have'){
			if(!isset($simbl[0]['w'])&&$simbl[0][1]['w']=='ed-pp'){
				//$s2[1]['w']='ды';
				//$s2[0]['w']=$s2[0][0];//$s2[0][1]['w']=translation of ed-pp becomes removed

				//commented out previous way while reordering ды, and write this
				//$s2[1]['w']='have';

				//i should translate this if this is imperative
				//i can translate this {п куй}
				//commented out previous code again
				$s2[1]=$simbl[1];
				$s2[1]['w']='куй';
				$s2[0][1]['w']='п';
			}
		}elseif($simbl[1]['w']=='s'||$simbl[1]['w']=='ed'||$simbl[1]['w']=='pr-si'){
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
				$s2[1]['w']='ды';//past tense morphem is in place of s
				//'ды' is reordered . subject is like adverbs etc, also in tatar...
			}elseif(isset($simbl[0][0]['w'])&&($simbl[0][0]['w']=='that'||$simbl[0][0]['w']=='whom')){
				//if($simbl[0][0]['w']=='whom'){
				//	echo'*';
				//}
				if(($simbl[0][1][1]['w']=='be'||$simbl[0][1][1]['w']=='have')&&$simbl[0][1][0][1]['w']=='ed-pp'){
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
				}elseif(($simbl[0]/*whom..met*/[1]/*we...met*/[1]/*havemet*/[1]/*have*/['w']=='have'||$simbl[0][1][1][1]['w']=='be')&&$simbl[0][1][1][0]/*met*/[1]/*ed-pp*/['w']=='ed-pp'){
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
					$s2[1]['w']='кан';//this works, but probably is buggy with other sentences
				//}else{
					//$s2[1]['w']=$words[$simbl[1]['w']];
				}elseif($dic[$simbl[0][1][1]['w']]['type']=='verb'){
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
					$s2[1]['w']='ган';
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
			}elseif(isset($simbl[0][1][1]['w'])&&$simbl[0][1][1]['w']=='be'){
				//remove "була" - copula "is" translation
				$subj=$s2[0][0];
				$obj=$s2[0][1][0];
				$s2[0]=$subj;
				$s2[1]=$obj;
				unset($subj,$obj);
			}elseif(isset($simbl[0][1]['w'])&&$simbl[0][1]['w']=='be'){
				//ddrsdram {is ... and has been in ...}
				//remove "була" - copula "is" translation
				$s2=$s2[0][0];
			}elseif(
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
			}
		}elseif($simbl[1]['w']=='type'){
			if(isset($simbl[0]['w'])&&$simbl[0]['w']=='three'){
				$num=$s2[0];
				$s2[0]=array($num,array('w'=>'енче'));
			}
		}elseif($simbl[1]['w']==',,'){
			if(isset($simbl[0][0]['w'])&&$simbl[0][0]['w']=='the'){
				$s2=$s2[0];
			}
		//}else{
			//$s2[1]['w']=$words[$simbl[1]['w']];
		}
		if(!isset($s2[1])){//if it is array, it is set. if it is word and is not set , set here
			$s2[1]=$simbl[1];
			$s2[1]['w']=$words[$simbl[1]['w']];
		}
	}
	if(isset($simbl[1]['w'])){
		if($simbl[1]['w']=='s'||$simbl[1]['w']=='ed'||$simbl[1]['w']=='pr-si'){
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
			if($simbl[1]['w']=='pr-si'||$simbl[1]['w']=='s'){
				//i was going to fix йөреа to йөри but have found important order bug
				//that is fixed. continue
				if(isset($simbl[0][1][1]['w'])&&$simbl[0][1][1]['w']=='walk'){
					$s2[1]['w']='й';
				}
				
			}
			if(isset($s2[1]['w'])&&$s2[1]['w']=='ды'){
				if(isset($s2[0][1][1][1]['w'])&&$s2[0][1][1][1]['w']=='йөре'){
					$s2[1]['w']='де';
				}
				
			}
		}elseif($s2[1]['w']=='тан'&&isset($s2[0][1]['w'])&&(mb_substr($s2[0][1]['w'],-1)=='а'||mb_substr($s2[0][1]['w'],-1)=='я')){
			$s2[1]['w']='дан';
		}elseif(isset($simbl[1]['thisisabbreviation'])&&substr($simbl[0]['w'],-1)=='s'){
			$s2[1]=array($s2[1],array('w'=>'е'));
		}
		elseif($simbl[1]['w']=='.'){//top of sentence
			//echo'*';
			if(isset($simbl[0][1][1]['w'])&&isset($dic[$simbl[0][1][1]['w']])&&$dic[$simbl[0][1][1]['w']]['type']=='verb'){
				$s2[0][0]=array($s2[0][0],$s2[0][1]);
				$s2[0][1]=array('w'=>'гыз');
			}
		}
	}
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
	foreach($simbl as $key=>$word){
		if(!isset($s2[$key])){
			$s2[$key]=$simbl[$key];
		}
	}
	//var_dump($s2);echo'*';
	$recursionlevel--;
	return $s2;

}

















?>