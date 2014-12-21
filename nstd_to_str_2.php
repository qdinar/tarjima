<?php
function nstd_to_str_2($nstd){
	$result='';
	//global $firstletterofsentenceiscapital;
	global $nstd_to_str_2_firstwordisready;
	if(isset($nstd['dash'])){
		$result.=' — ';
	}
	if(isset($nstd['thisisheader'])||isset($nstd['inquotes'])){
		$result.='"';
	}
	if(isset($nstd[0][1]['w'])){
		if($nstd[0][1]['w']=='()'){
			//swap
			$parentheses=$nstd[0];
			$nstd[0]=$nstd[1];
			$nstd[1]=$parentheses;
		}elseif($nstd[0][1]['w']=='һәм'||$nstd[0][1]['w']=='һәм түгел'||$nstd[0][1]['w']==','){
			$and=$nstd[0];//and's block
			$nstd[0]=$nstd[1];//main block
			$nstd[1]=$and;//swap
			$and=$nstd[1][1];
			//swapping the word 'and' with dependent block
			$nstd[1][1]=$nstd[1][0];
			$nstd[1][0]=$and;
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
	}
	if(!isset($nstd[0]['w'])){
		$result.=nstd_to_str_2($nstd[0]);
	}else{
		$word=$nstd[0]['w'];
		if(isset($nstd[0]['firstiscapital'])&&$nstd[0]['firstiscapital']==true||!$nstd_to_str_2_firstwordisready){
			$word=mb_strtoupper(mb_substr($word,0,1)).mb_substr($word,1);
		}
		if(isset($nstd[1]['w'])&&$nstd[1]['w']=='е'&&$word=='тизлек'){
			$word='тизлег';
		}
		$result.=$word;
		$nstd_to_str_2_firstwordisready=true;
	}
	if(isset($nstd[1][1]['w'])){
		if($nstd[1][1]['w']=='()'){
			$nstd[1]=$nstd[1][0];
			$parentheses=true;
		}
	}
	if(!isset($nstd[1]['w'])){
		$result.=' ';
		if(isset($parentheses)){
			$result.='(';
		}
		$result.=nstd_to_str_2($nstd[1]);
	}else{
		$word=$nstd[1]['w'];
		if(isset($nstd[1]['firstiscapital'])&&$nstd[1]['firstiscapital']==true||!$nstd_to_str_2_firstwordisready){
			$word=mb_strtoupper(mb_substr($word,0,1)).mb_substr($word,1);
		}
		//if($word=='ле'){echo'OK';exit;}
		if(
			$word!='.'&&$word!='не'&&$word!='ны'&&$word!='е'&&$word!='гыз'
			&&$word!='дан'&&$word!='нче'&&$word!='енче'&&$word!='ы'
			&&$word!='лы'&&$word!='ле'
			&&$word!='п'&&$word!='дән'&&$word!='кә'&&$word!='гә'&&$word!='на'
			&&$word!='рак'
			&&$word!='лар'
		){
			//кушымчалардан башкаларын айырып язасы
			$result.=' ';
		}elseif($word=='п'){
			$main=get_main_word($nstd[0]);
			if($main['w']=='ал'){
				$result.='ы';
			}
		}elseif($word=='кә'||$word=='гә'){
			$main=get_tr_last_word($nstd[0]);
			if(isset($main['thisisabbreviation'])){
				$result.='-';
			}
		// }elseif($word==' ле'){
			// $result.='***';
		}
		if(isset($parentheses)){
			$result.='(';
		}
		// if($word=='кә'){
			// $main=get_main_word($nstd[0]);
			// if($main['w']=='DDR2'){
				// $word='гә';
			// }
		// }
		$result.=$word;
		$nstd_to_str_2_firstwordisready=true;
	}
	if(isset($nstd['thisisheader'])||isset($nstd['inquotes'])){
		$result.='"';
	}
	if(isset($parentheses)){
		$result.=')';
		unset($parentheses);
	}
	return $result;
}
