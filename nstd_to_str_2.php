<?php
function nstd_to_str_2($nstd){
	global $prev_w, $prev_w_sc;
	//if($prev_w=='керү'){echo'***';show_tree_3($nstd);}
	// echo '*',$prev_w;
	// show_tree_3($nstd);
	// $result='';
	//global $firstletterofsentenceiscapital;
	global $nstd_to_str_2_firstwordisready;
	if(isset($nstd['dash'])){
		$result.=' — ';
	}
	if(isset($nstd['thisisheader'])||isset($nstd['inquotes'])){
		$result.='"';
	}
	if(isset($nstd['w'])){
		$result.=$nstd['w'];
		$prev_w=$nstd['w'];
		$prev_w_sc=$nstd['w'];
		goto ret_res;
	}
	if(isset($nstd[0][1]['w'])){
		/*
		if($nstd[0][1]['w']=='()'){
			//swap
			$parentheses=$nstd[0];
			$nstd[0]=$nstd[1];
			$nstd[1]=$parentheses;
			//a
			//()
			//
			//()
			//a
		}elseif($nstd[0][1]['w']=='һәм'||$nstd[0][1]['w']=='һәм түгел'||$nstd[0][1]['w']==','){
			$and=$nstd[0];//and's block
			$nstd[0]=$nstd[1];//main block
			$nstd[1]=$and;//swap
			//0Band
			//1A
			//
			//0A
			//1Band
			$and=$nstd[1][1];
			//swapping the word 'and' with dependent block
			$nstd[1][1]=$nstd[1][0];
			$nstd[1][0]=$and;
			//0A
			//1B
			//1and
			//
			//0A
			//1and
			//1B
			if(
				isset($nstd[1][1]['dash'])//possible dash of external and block , that is positioned later
				//&&isset($nstd[0][0][1]['w'])//possible 'and' of inner and block
				//&&$nstd[0][0][1]['w']=='and'
				//&&isset($nstd[0][0][0]['dash'])
				&&isset($nstd[0]['dash'])
			){
				//echo'*';
				unset($nstd[1][1]['dash']);//remove later dash
			}
		//}elseif($nstd[0][1]['w']==',,'){
		}
		*/
		if(
			$nstd[0][1]['w']=='һәм'
			||$nstd[0][1]['w']=='һәм түгел'
			||$nstd[0][1]['w']==','
			||$nstd[0][1]['w']=='()'
		){
			$result.=nstd_to_str_2($nstd[1]);
			$prev_w=$nstd[1]['w'];
			$prev_w_sc=$nstd[1]['w'];
			if($nstd[0][1]['w']=='()'){
				$result.=' (';
			}elseif($nstd[0][1]['w']==','){
				$result.=', ';
			}else{
				$result.=' '.$nstd[0][1]['w'];
				$prev_w=$nstd[0][1]['w'];
				$prev_w_sc=$nstd[0][1]['w'];
			}
			if($nstd[0][1]['w']!='()'){
				$result.=' ';
			}
			if($nstd[0][0][1]['w']=='һәм'){
				// , һәм
				$result.=nstd_to_str_2($nstd[0][0][1]);
				$result.=' ';
				$result.=nstd_to_str_2($nstd[0][0][0]);
			}else{
				// һәм
				$result.=nstd_to_str_2($nstd[0][0]);
			}
			if($nstd[0][1]['w']=='()'){
				$result.=') ';
			}
			goto ret_res;
		}
	}
	//echo '*',$prev_w;
	if(!isset($nstd[0]['w'])&&$nstd[0]!=''){
		$result.=nstd_to_str_2($nstd[0]);
	}else{
		$word=$nstd[0]['w'];
		if(isset($nstd[0]['firstiscapital'])&&$nstd[0]['firstiscapital']==true||!$nstd_to_str_2_firstwordisready){
			$word=mb_strtoupper(mb_substr($word,0,1)).mb_substr($word,1);
		}
		if(isset($nstd[1]['w'])&&$nstd[1]['w']=='ы'&&$word=='тизлек'){
			$word='тизлег';
		}
		$result.=$word;
		$prev_w=$word;
		$prev_w_sc=$nstd[0]['w'];
		$nstd_to_str_2_firstwordisready=true;
	}
	/*
	if(isset($nstd[1][1]['w'])){
		if($nstd[1][1]['w']=='()'){
			$nstd[1]=$nstd[1][0];
			$parentheses=true;
		}
	}
	*/
//==============================================================================2 of 2
	if(!isset($nstd[1]['w'])){
		if(!isset($nstd[1][0]['w'])||$nstd[1][0]['w']!=','){
			$result.=' ';
		}
		/*
		if(isset($parentheses)){
			$result.='(';
		}
		*/
		$result.=nstd_to_str_2($nstd[1]);
	}else{
		//echo '*',$prev_w;
		$word=$nstd[1]['w'];
		if(isset($nstd[1]['firstiscapital'])&&$nstd[1]['firstiscapital']==true||!$nstd_to_str_2_firstwordisready){
			$word=mb_strtoupper(mb_substr($word,0,1)).mb_substr($word,1);
		}
		//if($word=='ле'){echo'OK';exit;}
		$main=get_tr_last_word($nstd[0]);
		// echo'***';
		// show_tree_3($nstd[0]);
		// show_tree_3($main);
		if(
			$word!='.'&&$word!=','&&
			$word!='не'&&$word!='ы'&&$word!='гыз'
			&&$word!='тан'&&$word!='нче'&&$word!='ле'
			&&$word!='п'&&$word!='кә'&&$word!='у'
			&&$word!='рәк'&&$word!='лар'&&$word!='а'
			&&$word!='ның'&&$word!='дәге'&&$word!='ган'
			&&$word!='учы'
			&&$word!='intro_comma'
		){
			//кушымчалардан башкаларын айырып язасы
			$result.=' ';
		}
		elseif(
			isset($main['thisisabbreviation'])
			||$nstd[0][0][1]['w']=='()'
			||$nstd[0][1][0][1]['w']=='()'
			||preg_match('/\d/',mb_substr($main['w'],-1),$tmp)
			||$nstd[0]['thisisheader']==true
		){
			$result.='-';
		}
		elseif($word=='п'||$word=='нче'){
			//$main=get_main_word($nstd[0]);
			//$main=$prev_w;
			if(last_conson($prev_w)){
				if(is_soft($prev_w)){
					$result.='е';
				}else{
					$result.='ы';
				}
			}
		//}elseif($word=='кә'){
		// }elseif($word==' ле'){
			// $result.='***';
		}elseif($word=='ы'){
			//$main=get_tr_last_word($nstd[0]);
			//$main=$prev_w;
			if(!last_conson($prev_w)){
				$result.='с';
			}
		}else{
		}
		/*
		if(isset($parentheses)){
			$result.='(';
		}
		*/
		// if($word=='кә'){
			// $main=get_main_word($nstd[0]);
			// if($main['w']=='DDR2'){
				// $word='гә';
			// }
		// }
		//$lastword=get_tr_last_word($nstd[0]);
		//$lastword=$prev_w;
		if($word=='ы'){
			if(is_soft($prev_w)){
				$word='е';
			}
		}
		elseif($word=='ле'){
			//if($prev_w_sc=='ы'){
			//бер югары үткәрүчәнлек ("икекатлы мәгълүмат тизлеге") -ле
			if($main['w']=='ы'){
				$result.='н';
			}
			if(!is_soft($prev_w)){
				$word='лы';
			}
		}
		elseif($word=='у'){
			if(is_soft($prev_w)){
				$word='ү';
			}
		}
		elseif($word=='кә'){
			//echo '*',$prev_w;
			if($prev_w_sc=='ы'){
				$word='на';
			}else{
				$isbreath=is_breath($prev_w);
				if(!is_soft($prev_w)){
					if($isbreath){
						$word='ка';
					}else{
						$word='га';
					}
				}else{
					if($isbreath){
						$word='кә';
					}else{
						$word='гә';
					}
				}
			}
		}
		elseif($word=='тан'){
			if($prev_w_sc=='ы'){
				$word='ннан';
			}else{
				$isbreath=is_breath($prev_w);
				if(!is_soft($prev_w)){
					if($isbreath){
						$word='тан';
					}else{
						$word='дан';
					}
				}else{
					if($isbreath){
						$word='тән';
					}else{
						$word='дән';
					}
				}
			}
		}
		elseif($word=='рәк'){
			if(!is_soft($prev_w)){
				$word='рак';
			}
		}
		elseif($word=='ның'){
			if(is_soft($prev_w)){
				$word='нең';
			}
		}
		elseif($word=='не'){
			if($prev_w_sc=='ы'){
				$word='н';
			}
			elseif(!is_soft($prev_w)){
				$word='ны';
			}
		}
		elseif($word=='intro_comma'){
			$word=',';
		}		
		elseif($word=='лар'){
			if(is_soft($prev_w)){
				$word='ләр';
			}
		}
		$result.=$word;
		$prev_w=$word;
		$prev_w_sc=$nstd[1]['w'];
		$nstd_to_str_2_firstwordisready=true;
	}
	//--------------------------------nstd i>1
	for($i=2;$i<count($nstd);$i++){
		if(isset($nstd[$i])){
			$word=$nstd[$i]['w'];
			//echo '*',$prev_w;
			if($word=='лар'){
				if(is_soft($prev_w)){
					$word='ләр';
				}
			}elseif($word=='ы'){
				if(!last_conson($prev_w)){
					$result.='с';
				}
				if(is_soft($prev_w)){
					$word='е';
				}
			}elseif($word=='у'){
				if(is_soft($prev_w)){
					$word='ү';
				}
			}elseif($word=='рәк'){
				if(!is_soft($prev_w)){
					$word='рак';
				}
			}elseif($i<count($nstd)-1){
				$result.=' ';
			}
			$result.=$word;
			$prev_w=$word;
			$prev_w_sc=$nstd[$i]['w'];
		}
	}
	//--------------------------------
	ret_res:
	if(isset($nstd['thisisheader'])||isset($nstd['inquotes'])){
		$result.='"';
	}
	/*
	if(isset($parentheses)){
		$result.=')';
		unset($parentheses);
	}
	*/
	//$prev_w=$word;
	return $result;
}
