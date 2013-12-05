<?php

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', '1');

/*
$words=array('good'=>'әйбәт','school'=>'мәктәп');

function tr_simple_block($simbl){

global $words;
$s2=array();
foreach($simbl as $word ){$s2[$word]=$words[$word];}

return $s2;

}


$test=array('good','school');
echo implode ( tr_simple_block($test)  );
*/


$words=array('good'=>'әйбәт','school'=>'мәктәп');

function tr_simple_block($simbl){

	global $words;
	$s2=array();
	if(is_array($simbl[0])){
		$s2[0]=tr_simple_block($simbl[0]);
	}else{
		if($simbl[0]=='the'){
			$s2[0]='теге';
		}else{
			$s2[0]=$words[$simbl[0]];
		}
	}
	//add ны
	if(
		((is_array($simbl[1])&&$simbl[1][1]=='read')||$simbl[1]=='read')
		&&
		(is_array($simbl[0])&&$simbl[0][0]=='the')
	){
		$s2[0]=array($s2[0],'ны');
	}
	//
	if(is_array($simbl[1])){
		$s2[1]=tr_simple_block($simbl[1]);
	}else{
		if($simbl[1]=='ed-pp'){
			//$s2[1]='лгән';
			if((is_array($simbl[0])&&$simbl[0][1]=='know')||$simbl[0]=='know'){
				$s2[1]='енгән';
			}else{
				$s2[1]='лган';
			}
		}elseif($simbl[1]=='have'){
			if(is_array($simbl[0])&&$simbl[0][1]=='ed-pp'){
				//$s2[1]='ды';
				//$s2[0]=$s2[0][0];//$s2[0][1]=translation of ed-pp becomes removed

				//commented out previous way while reordering ды, and write this
				//$s2[1]='have';

				//i should translate this if this is imperative
				//i can translate this {п куй}
				//commented out previous code again
				$s2[1]='куй';
				$s2[0][1]='п';
			}
		}elseif($simbl[1]=='s'){
			//return $s2[0];//$s2[1] should be translation of s but it becomes removed
			//i see 'ды' should be reordered...
			if($simbl[0][1][1]=='have'&&$simbl[0][1][0][1]=='ed-pp'){
				$s2[0]/*hehave..*/[1]/*haveread..*/=$s2[0][1][0]/*read(pp)...*/[0]/*read...*/;
				//have read ed-pp is translated like read
				$s2[1]='ды';//past tense morphem is in place of s
				//'ды' is reordered . subject is like adverbs etc, also in tatar...
			}
		}else{
			$s2[1]=$words[$simbl[1]];
		}
	}
	//var_dump($s2);echo'*';
	return $s2;

}

$test=array('good','school');
echo implode ( tr_simple_block($test)  );

$words['know']='бел';
$words['last']='соңгы';

echo'<br/>';

$test=array('last','know');
echo implode ( tr_simple_block($test)  );

/*
function tr_past_p($pastp){
	global $words;
	if(is_array($pastp)){
		$s2[0]=tr_simple_block($pastp);
	}else{
		$s2[0]=$words[$pastp];
	}
	$s2[1]='енгән';
	return $s2;
}

$test='know';
echo implode ( tr_past_p($test)  );

echo'<br/>';

*/

function nstd_to_str($nstd){
	$result='';
	if(is_array($nstd[0])){
		$result.=nstd_to_str($nstd[0]);
	}else{
		$result.=$nstd[0];
	}
	if(is_array($nstd[1])){
		$result.=nstd_to_str($nstd[1]);
	}else{
		$result.=$nstd[1];
	}
	return $result;
}
/*
$test=array('last','know');
$result=tr_past_p($test);
echo nstd_to_str($result);

echo'<br/>';

$words['bug']='баг';
$test=array($result,'bug');
echo nstd_to_str($test);
*/

echo'<br/>';

//$words['ed-pp']='енгән';

$test=array($test,'ed-pp');
$result=tr_simple_block($test);
echo nstd_to_str($result);

echo'<br/>';

$words['bug']='баг';
$test=array($test,'bug');
$result=tr_simple_block($test);
echo nstd_to_str($result);

echo'<br/>';

$words['read']='укы';
$test2=array($test,'read');
$result=tr_simple_block($test2);
echo nstd_to_str($result);

echo'<br/>';
$test=array('the',$test);
$result=tr_simple_block($test);
echo nstd_to_str($result);

echo'<br/>';
$test3=array($test,'read');
$result=tr_simple_block($test3);
echo nstd_to_str($result);

echo'<br/>';
$test2=array('read','ed-pp');
$result=tr_simple_block($test2);
echo nstd_to_str($result);

echo'<br/>';
$test2=array($test2,'have');
$result=tr_simple_block($test2);
echo nstd_to_str($result);

echo'<br/>';
$test3=array($test3,'ed-pp');
$test3=array($test3,'have');
$result=tr_simple_block($test3);
echo nstd_to_str($result);

echo'<br/>';
$words['he']='ул';
$test3=array('he',$test3);
$test3=array($test3,'s');
//echo'<pre>';
//print_r($test3);
//echo'</pre>';
$result=tr_simple_block($test3);
echo nstd_to_str($result);
//i have commited this with comment "subject is like others..."

//i have written here a blog post in tatar, that grammarians do not use morphems separately, but i use, and i use blocks of morphems: http://qdinar.wp.kukmara-rayon.ru/2013/11/25/grammatika-no-nicik-yasa-w-doros/ , https://vk.com/wall17077748_2708 , and i had said about that we need to write morphems separately...
//and i have written that in english: http://qdb.wp.kukmara-rayon.ru/2013/11/26/right-correct-analysis-of-phrase-structure/

//hethelastknowedbugreadedhave
//{he [(has {[read (the {[(last know)n] bug})]*ed-pp*})*s*]}
//{he [({[(the {[(last know) ed-pp] bug}) read] ed-pp} have) s]}

//echo'<br/>';
//$engtext='he has read the last known bug';

//the teacher whom we have met has read the bug that was mentioned, but not comments

//https://github.com/qdinar/tarjima

$engtext='he has read the last known bug';
$engtext=explode(' ', $engtext);
$engtext2=array();

//explode words into grammatical morphemes
foreach($engtext as $word){
	if(mb_substr($word,-1,1)=='s'){
		if(mb_substr($word,0,mb_strlen($word)-1)=='ha'){
			$engtext2[]='have';
			$engtext2[]='s';
		}
	}elseif(mb_substr($word,-1,1)=='n'){
		if(mb_substr($word,0,mb_strlen($word)-1)=='know'){
			$engtext2[]='know';
			$engtext2[]='ed-pp';
		}
	}elseif(mb_substr($word,-1,1)=='d'){
		if(mb_substr($word,0,mb_strlen($word)-1)=='rea'){
			$engtext2[]='read';
			$engtext2[]='ed-pp';
		}
	}else{
		$engtext2[]=$word;
	}
}


echo'<br/>';
print_r($engtext2);

//make order!
/*$engtext3=array();
foreach($engtext2 as $key=>$word){
	if($word=='have'&&$engtext2[$key+1]=='s'){
		array_splice($engtext2,$key+1,1);
		$engtext3[]=$engtext2;
		$engtext3[]='s';
	}
}

echo'<br/>';
print_r($engtext3);
*/

function order($inparr){
	global $dic;
	$outparr=array();
	foreach($inparr as $key=>$word){
		if($word=='have'){
			if($inparr[$key+1]=='s'){
				array_splice($inparr,$key+1,1);//remove s
				if(count($inparr)>1){
					$inparr=order($inparr);
				}
				$outparr[]=$inparr;
				$outparr[]='s';
				return $outparr;
			}elseif($key==0){//have is 1st
				if($inparr[2]=='ed-pp'){
					array_splice($inparr,0,1);//remove have
					if(count($inparr)>1){
						$inparr=order($inparr);
					}
					$outparr[]=$inparr;
					$outparr[]='have';
					return $outparr;
				}
			}else{
				if($key==1){//have in 2nd place
					$removedword=array_splice($inparr,0,1);//remove he
					if(count($inparr)>1){
						$inparr=order($inparr);
					}
					$outparr[]=$removedword[0];
					$outparr[]=$inparr;
					return $outparr;
				}
			}
		}elseif($word=='ed-pp'&&$key==1){
			array_splice($inparr,1,1);//remove ed-pp
			if(count($inparr)>1){
				$inparr=order($inparr);
			}
			$outparr[]=$inparr;
			$outparr[]='ed-pp';
			return $outparr;
		}elseif(isset($dic[$word])&&$dic[$word]['type']=='verb'&&$key==0&&$inparr[1]=='the'){
			array_splice($inparr,0,1);//remove verb
			if(count($inparr)>1){
				$inparr=order($inparr);
			}
			$outparr[]=$inparr;
			$outparr[]=$word;
			return $outparr;
			//i should make dictionary with (several) properties (instead of word-per-word translations) (i need it now because i need check whether morphem is verb)
		}elseif($word=='the'&&$key==0){
			array_splice($inparr,0,1);//remove the
			if(count($inparr)>1){
				$inparr=order($inparr);
			}
			$outparr[]='the';
			$outparr[]=$inparr;
			return $outparr;
			//he had read the last known bug
			//i see "last know ed bug", it can be {subject verb object}, but it is not "knowed", it is "known", for that i will replace ed to ed-pp (past participle). no. i replace it to en. no. en is used itself in texts, change back.
		}elseif(isset($dic[$word])&&$dic[$word]['type']=='noun'&&$key==count($inparr)-1&&$inparr[$key-1]=='ed-pp'){
			array_splice($inparr,$key,1);//remove noun
			if(count($inparr)>1){
				$inparr=order($inparr);
			}
			$outparr[]=$inparr;
			$outparr[]=$word;
			return $outparr;
			//i see {last know ed-pp}. {(last know) n} or {last (know n)}? it is (usually) the 1st one, but how to select with program? if it is {last (know n)}, it would be {last (known bug)}...
		}elseif($word=='last'&&$key==0&&$inparr[1]=='know'&&$inparr[2]=='ed-pp'&&count($inparr)==3){
			array_splice($inparr,2,1);//remove ed-pp
			$outparr[]=$inparr;
			$outparr[]='ed-pp';
			return $outparr;
		}
	}
	return $inparr;
	
}


//echo'<br/><pre>';
$dic=array('bug'=>array('type'=>'noun'),'read'=>array('type'=>'verb'));
$engtext2=order($engtext2);
//print_r($engtext2);
//echo'</pre>';

echo'<br/>';
echo nstd_to_str($engtext2);


echo'<br/>';
$result=tr_simple_block($engtext2);
//echo'<pre>';
//print_r($result);
echo nstd_to_str($result);




















?>