<?php
//explode words into grammatical morphemes
function explode_words_into_morphemes_2($engtext){
	global $dic,$firstletterofsentenceiscapital;
	$engtext2=array();
	$i=0;$thereiscomma=false;$firstiscapital=false;$firstletterofsentenceiscapital=true;
	foreach($engtext as $key=>$word){
		if(substr($word,-1)==','){
			$thereiscomma=true;
			$word=substr($word,0,strlen($word)-1);
		}
		if(ctype_upper(substr($word,0,1))){
			if($key>0){
				$firstiscapital=true;
			}
			$word=strtolower($word);
		}
		if(mb_substr($word,-1,1)=='s'){
			if(mb_substr($word,0,mb_strlen($word)-1)=='ha'){
				$engtext2[]='have';
				$engtext2[]='s';
			}elseif(mb_substr($word,0,mb_strlen($word)-1)=='wa'){
				$engtext2[]='be';
				$engtext2[]='ed';
			}elseif(mb_substr($word,0,mb_strlen($word)-1)=='i'){
				$engtext2[]='be';
				$engtext2[]='pr-si';
			}else{
				$engtext2[]=mb_substr($word,0,mb_strlen($word)-1);
				$engtext2[]='s';
			}
		}elseif(mb_substr($word,-1,1)=='n'){
			if(mb_substr($word,0,mb_strlen($word)-1)=='know'){
				$engtext2[]='know';
				$engtext2[]='ed-pp';
			}
		}elseif(mb_substr($word,-1,1)=='t'){
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
		}elseif(mb_substr($word,-2,2)=='ed'){
			$engtext2[]=mb_substr($word,0,mb_strlen($word)-2);
			if(isset($engtext2[count($engtext2)-3])&&($engtext2[count($engtext2)-3]=='be'||$engtext2[count($engtext2)-3]=='have')){
				$engtext2[]='ed-pp';
			}else{
				$engtext2[]='ed';
			}
		}elseif(mb_substr($word,-2,2)=='er'){//er in teacher is also almost grammatical...
			$word1=mb_substr($word,0,mb_strlen($word)-2);
			if(isset($dic[$word1])&&$dic[$word1]['type']=='verb'){
				$engtext2[]=$word1;
				$engtext2[]='er';
			}else{
				$engtext2[]=$word;
			}
		}elseif(mb_substr($word,-1,1)=='d'){
			if(mb_substr($word,0,mb_strlen($word)-1)=='rea'){
				$engtext2[]='read';
				$engtext2[]='ed-pp';
			}
		}elseif(isset($dic[$word])&&$dic[$word]['type']=='verb'){
			$engtext2[]=$word;
			$engtext2[]='pr-si';//present simple
		}else{
			$engtext2[]=$word;
		}
		if($thereiscomma){
			$engtext2[]=',';
			$thereiscomma=false;
		}
		for($j=$i;$j<count($engtext2);$j++){
			$engtext2[$j]=array('w'=>$engtext2[$j]);
		}
		if($firstiscapital){
			$firstiscapital=false;
			$engtext2[$i]['firstiscapital']=true;
		}
		$i=count($engtext2);
	}
	return $engtext2;
}
?>