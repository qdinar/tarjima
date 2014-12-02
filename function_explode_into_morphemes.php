<?php
//explode string/sentence into grammatical morphemes
function explode_into_morphemes($engtext){
	$engtext=preg_split('/(?=[^a-zA-Z0-9])|(?<=[^a-zA-Z0-9])/',$engtext);
	foreach($engtext as $key=>$word){
		if(
			$word==' '||//may be i will need fix this for " - "
			$word==''//empty string appears at end , i do not know why.
		){
			unset($engtext[$key]);
		}
	}
	global $dic,$firstletterofsentenceiscapital,$nounlikes,$verbs;
	//global $thereisdotatendofsentence;
	$engtext2=array();
	$i=0;
	//$thereiscomma=false;
	//$thereisdot=false;
	$firstiscapital=false;
	$firstletterofsentenceiscapital=true;
	$thisisabbreviation=false;
	$thenextwordisused=false;
	foreach($engtext as $key=>$word){
		if($thenextwordisused){
			$thenextwordisused=false;
			continue;
		}
		//echo($key);//0246781012...
	//for($key=0;$key<count($engtext);$key++){
		//if(!isset($engtext[$key])){continue;}else{$word=$engtext[$key];}
		/*if(substr($word,-1)==','){
			$thereiscomma=true;
			$word=substr($word,0,strlen($word)-1);
		}elseif(substr($word,-1)=='.'){
			$thereisdot=true;
			$word=substr($word,0,strlen($word)-1);
			//if($key=count($engtext)-1){
				//$thereisdotatendofsentence=true;
			//}
		}
		if(substr($word,0,1)=='('){
		}*/
		if(ctype_upper(substr($word,0,1))){
			if($key>0){
				$firstiscapital=true;
			}
			if(ctype_upper(substr($word,1,1))){
				$thisisabbreviation=true;
				$firstiscapital=false;
			}
		}

		for($wi=0,$lowercaseisfound=false,$upperafterlower=false;$wi<strlen($word);$wi++){
			if(ctype_lower(substr($word,$wi,1))){
				$lowercaseisfound=true;
			}
			if($lowercaseisfound&&ctype_upper(substr($word,$wi,1))){
				//there is upper case after lower case
				$upperafterlower=true;
				break;
			}
		}
		
		if(!$thisisabbreviation&&($firstiscapital==true||$key==0)){
			$word=strtolower($word);
		}
		if(mb_substr($word,-1)=='s'){
			$remaining=mb_substr($word,0,mb_strlen($word)-1);
			if($remaining=='ha'){
				$engtext2[]='have';
				$engtext2[]='s';
			}elseif($remaining=='wa'){
				$engtext2[]='be';
				$engtext2[]='ed';
			}elseif($remaining=='i'){
				$engtext2[]='be';
				$engtext2[]='s';
			}else{
				//$tryverb=mb_substr($word,0,mb_strlen($word)-1);
				$tryverb=$remaining;
				if(isset($dic[$tryverb])&&$dic[$tryverb]['type']=='verb'){
					$engtext2[]=$tryverb;
					$engtext2[]='s';
				}
				elseif(isset($nounlikes[$tryverb])){
					$engtext2[]=$tryverb;
					$engtext2[]='s-pl';
				}
				else{
					$engtext2[]=$word;
				}
			}
		}
		elseif(mb_substr($word,-1)=='n'){
			if(mb_substr($word,0,mb_strlen($word)-1)=='know'){
				$engtext2[]='know';
				$engtext2[]='ed-pp';
			}elseif(mb_substr($word,0,mb_strlen($word)-1)=='bee'){
				$engtext2[]='be';
				$engtext2[]='ed-pp';
			}else{
				$engtext2[]=$word;
			}
		}
		elseif(mb_substr($word,-1)=='t'){
			if(mb_substr($word,0,mb_strlen($word)-1)=='me'){
				$engtext2[]='meet';
				$engtext2[]='ed-pp';
			}elseif(mb_substr($word,0,mb_strlen($word)-1)=='buil'){
				$engtext2[]='build';
				$engtext2[]='ed-pp';
			}elseif(mb_substr($word,0,mb_strlen($word)-1)=='bough'){
				$engtext2[]='buy';
				//$engtext2[]='ed-pp';//or ed ?
				//may be i will replace ed to ed-ps. no. i have tried it , but i have seen then the next elseif block , i will try that way
				if($engtext2[count($engtext2)-3]=='be'||$engtext2[count($engtext2)-3]=='have'){
					$engtext2[]='ed-pp';
				}else{
					$engtext2[]='ed';
				}
			}else{
				$engtext2[]=$word;
			}
		}
		elseif(mb_substr($word,-2,2)=='ed'&&$word!='speed'){
			$engtext2[]=mb_substr($word,0,mb_strlen($word)-2);
			if(isset($engtext2[count($engtext2)-3])&&($engtext2[count($engtext2)-3]=='be'||$engtext2[count($engtext2)-3]=='have')){
				$engtext2[]='ed-pp';
			}else{
				$engtext2[]='ed';
			}
		}
		elseif(mb_substr($word,-2)=='er'){//er in teacher is also almost grammatical...
			$word1=mb_substr($word,0,mb_strlen($word)-2);
			if(isset($dic[$word1])&&$dic[$word1]['type']=='verb'){
				$engtext2[]=$word1;
				$engtext2[]='er';
			}else{
				if(mb_substr($word1,-1)=='i'){
					$word1=mb_substr($word1,0,mb_strlen($word1)-1).'y';
				}
				if(isset($nounlikes[$word1])){
					//$engtext2[]=$word1;
					//$engtext2[]='er-comp';
					$engtext2[]=array($word1,'er-comp');
				}else{
					$engtext2[]=$word;
				}
			}
		}
		elseif(mb_substr($word,-2)=='rd'||mb_substr($word,-2)=='nd'||mb_substr($word,-2)=='th'){
				//$engtext2[]=mb_substr($word,0,mb_strlen($word)-2);
				//$engtext2[]=mb_substr($word,-2);
				$trynumber=mb_substr($word,0,mb_strlen($word)-2);
				if(preg_match('/^[0-9]+$/',$trynumber)==1){
					$ordinalnumber=array(
						array('w'=>$trynumber),
						array('w'=>mb_substr($word,-2)),
						'ordnum'=>true
					);
					$engtext2[]=$ordinalnumber;
					$i=count($engtext2);
					continue;
				}else{
					$engtext2[]=$word;
				}
		}
		elseif(mb_substr($word,-1)=='d'){
			if(mb_substr($word,0,mb_strlen($word)-1)=='rea'){
				$engtext2[]='read';
				$engtext2[]='ed-pp';
			}else{
				$engtext2[]=$word;
			}
		}
		elseif(mb_substr($word,-3)=='ing'){
			$tryverb=mb_substr($word,0,mb_strlen($word)-3);
			//if(isset($dic[$tryverb])&&$dic[$tryverb]['type']=='verb'){
			if(isset($dic[$tryverb])&&$dic[$tryverb]['type']=='verb'||isset($verbs[$tryverb])){
				//if($word=='signaling'){echo $tryverb;exit;}
				$engtext2[]=$tryverb;
				$engtext2[]='ing';
			}else{
				$tryverb.='e';//compute
				if(isset($dic[$tryverb])&&$dic[$tryverb]['type']=='verb'){
					$engtext2[]=$tryverb;
					$engtext2[]='ing';
				}else{
					$engtext2[]=$word;
				}
			}
		}
		elseif(isset($dic[$word])&&$dic[$word]['type']=='verb'){
			$engtext2[]=$word;
			//$engtext2[]='pr-si';//present simple <-comment this out, i think it should be in order function
		}
		elseif($upperafterlower){
			$separated=preg_split('/(?=[A-Z0-9])(?<=[a-z])/',$word);
			$separated=explode_into_morphemes($separated);
			$engtext2[]=$separated;
			//$i=count($engtext2);
			//continue;
		}
		elseif($word=='-'){//for "high er-comp - speed"
			$engtext2[$i-1]=array($engtext2[$i-1],array('w'=>$engtext[$key+1]));
			$thenextwordisused=true;
		}
		else{
			$engtext2[]=$word;
		}
		
		/*
		if($thereiscomma){
			$engtext2[]=',';
			$thereiscomma=false;
		}
		if($thereisdot){
			$engtext2[]='.';
			$thereisdot=false;
		}
		*/
		/*
		for($j=$i;$j<count($engtext2);$j++){
			if(!is_array($engtext2[$j])){
				$engtext2[$j]=array('w'=>$engtext2[$j]);
			}
		}
		*/
		$engtext2=set_w_keys($engtext2,$i);
		if($firstiscapital){
			$firstiscapital=false;
			$engtext2[$i]['firstiscapital']=true;
		}
		if($thisisabbreviation){
			$thisisabbreviation=false;
			$engtext2[$i]['thisisabbreviation']=true;
		}
		$i=count($engtext2);
	}
	return $engtext2;
}




function set_w_keys($engtext2,$i){
	for($j=$i;$j<count($engtext2);$j++){
		if(!is_array($engtext2[$j])){
			$engtext2[$j]=array('w'=>$engtext2[$j]);
		}
		else{
			$engtext2[$j]=set_w_keys($engtext2[$j],0);
		}
	}
	return $engtext2;
}












