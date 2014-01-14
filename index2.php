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

	global $words,$dic;
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
	if(isset($simbl[0][1])&&$simbl[0][1]=='from'){
		if($simbl[1]=='go'){
			//$s2[1]='кайт';
		//}elseif($simbl[1][1]=='go'){
		//	$s2[1][1]='кайт';
			$s2[0][1]='тарафыннан';//instead of тан in dic
		}
	}
	if($simbl[0]=='last'&&$simbl[1]=='year'){
		//echo'*';
		//var_dump($s2);
		$s2[0]='узган';//instead of соңгы in dic
		//does not work, i do not know why. found. == instead of =.
		//var_dump($s2);
	}
	//
	if(is_array($simbl[1])){
		$s2[1]=tr_simple_block($simbl[1]);
	}else{
		if($simbl[1]=='ed-pp'){
			//$s2[1]='лгән';
			if((is_array($simbl[0])&&$simbl[0][1]=='know')||$simbl[0]=='know'){
				$s2[1]='енгән';
			}elseif((is_array($simbl[0])&&$simbl[0][1]=='mention')||$simbl[0]=='mention'){
				$s2[1]='ынган';
			}elseif((is_array($simbl[0])&&$simbl[0][1]=='build')||$simbl[0]=='build'){
				$s2[1]='лгән';
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
		}elseif($simbl[1]=='s'||$simbl[1]=='ed'||$simbl[1]=='pr-si'){
			//if($simbl[0][0]=='whom'){
			//	echo'*';
			//}
			//echo'*<pre>';
			//print_r($simbl);
			//echo'</pre>';
			//return $s2[0];//$s2[1] should be translation of s but it becomes removed
			//i see 'ды' should be reordered...
			if($simbl[0][1][1]=='have'&&$simbl[0][1][0][1]=='ed-pp'){
				$s2[0]/*hehave..*/[1]/*haveread..*/=$s2[0][1][0]/*read(pp)...*/[0]/*read...*/;
				//have read ed-pp is translated like read
				$s2[1]='ды';//past tense morphem is in place of s
				//'ды' is reordered . subject is like adverbs etc, also in tatar...
			}elseif($simbl[0][0]=='that'||$simbl[0][0]=='whom'){
				//if($simbl[0][0]=='whom'){
				//	echo'*';
				//}
				if(($simbl[0][1][1]=='be'||$simbl[0][1][1]=='have')&&$simbl[0][1][0][1]=='ed-pp'){
				//if (($simbl[0]/*whom..met*/[1]/*we..met*/[1]/*havemet*/=='be'||$simbl[0][1][1]=='have')&&$simbl[0][1][0]/*we*/[1]=='ed-pp'){
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
				//}elseif($simbl[0][0]=='whom'){
					//echo'*';
				}elseif(($simbl[0]/*whom..met*/[1]/*we...met*/[1]/*havemet*/[1]/*have*/=='have'||$simbl[0][1][1][1]=='be')&&$simbl[0][1][1][0]/*met*/[1]/*ed-pp*/=='ed-pp'){
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
					$s2[1]='кан';//this works, but probably is buggy with other sentences
				//}else{
					//$s2[1]=$words[$simbl[1]];
				}elseif($dic[$simbl[0][1][1]]['type']=='verb'){
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
					$s2[1]='ган';
					if($simbl[0][1][0][0]=='a'){
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
				//$s2[1]=$words[$simbl[1]];
				//echo'*';
				//var_dump($simbl);
			}
		//}else{
			//$s2[1]=$words[$simbl[1]];
		}
	}
	if(!isset($s2[1])){
		$s2[1]=$words[$simbl[1]];
	}
	if($simbl[1]=='s'||$simbl[1]=='ed'||$simbl[1]=='pr-si'){
		if($simbl[0][0]=='i'){
			$new_s2=array();
			$new_s2[]=$s2;
			$new_s2[]='м';
			$s2=$new_s2;
			unset($new_s2);
		}elseif($simbl[0][0]=='we'){
			$new_s2=array();
			$new_s2[]=$s2;
			$new_s2[]='быз';
			$s2=$new_s2;
			unset($new_s2);
		}
		if($simbl[1]=='pr-si'||$simbl[1]=='s'){
			//i was going to fix йөреа to йөри but have found important order bug
			//that is fixed. continue
			if($simbl[0][1][1]=='walk'){
				$s2[1]='й';
			}
			
		}
		if($s2[1]=='ды'){
			if($s2[0][1][1][1]=='йөре'){
				$s2[1]='де';
			}
			
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

//explode words into grammatical morphemes
function explode_words_into_morphemes($engtext){
	global $dic;
	$engtext2=array();
	foreach($engtext as $word){
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
	}
	return $engtext2;
}

echo'<br/>';
$engtext2=explode_words_into_morphemes($engtext);
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
		if($word=='s'||$word=='pr-si'||$word=='ed'){
			for($i=0,$dependentcl=0,$whoes=0,$ises=0;$i<$key;$i++){
				if($inparr[$i]=='whom'||$inparr[$i]=='that'){
					$dependentcl++;
					$whoes++;
				}elseif($inparr[$i]=='s'||$inparr[$i]=='pr-si'||$inparr[$i]=='ed'){
					$dependentcl--;
					$ises++;
				}
			}
			//checking whether this is of dependent clause
			//if($dependentcl!=0){
			//	continue;
			//}
			//comment the above block out, this does not process "who block"s like "whom we have met".
			//but i see contrary: it goes out if there are "who"s != "is"es... no, it is correct, because last "is" not counted
			/*if($dependentcl!=0){
				for($i=$key;$i<count($inparr);$i++){
					if($inparr[$i]=='whom'||$inparr[$i]=='that'){
						$dependentcl++;
					}elseif($inparr[$i]=='s'||$inparr[$i]=='pr-si'||$inparr[$i]=='ed'){
						$dependentcl--;
					}
				}
				//i should count that there is 1 "who" and 1 "is", but if they are equal , also should work, so just reused the previous "for" block code
				//equal count of who-s and is-s will not work if the whole sentence is like noun, for that i will make proper algorithm. add $whoes and $ises for counting. will comment out this block.
				if($dependentcl!=0){
					continue;
				}
				//now i see "whom we have met" is converted into "[(whom we) (have met)] pr-si". (that is incorrect). and see a "bug": "met" is "[(meet) ed-pp]" (redundant array).
				//have fixed the second bug.
			}*/
			if($dependentcl!=0){
				//ie whoes should be = to ises before this word like "is". only in that case process this further. this is for processing main/top verb ending. else ie if whoes are more than ises, this one belongs to one of them, and is not the main/top verb ending.
				//but, in case there are only 1 who and 1 is , this is dependent clause ready to be ordered
				//so i should count whoes and ises to end of array
				//if they are already more than 1, no need to count them further... but in "who" clause , the "who" should be before "is"...
				if($whoes==1 && $ises==0){
					//if $whoes=1 and $ises=0 , may be it is dependent clause.
					//i have made commit, but there are comments and corresponding code would be in next commit. so that was wrong commit. also i synced it. then i wanted to delete it. i rolled back in gui, github for windows, synced, but it does not work. then i searcehd and asked in irc. i have found an incorrect way. i created new commit with name "test" and reverted it back in command line. and the revert commit has appeared in github site, and the wrong commit is not deleted. then i have asked and read again and have managed to delete them: i have rolled back last 2 commits in gui, then "git push origin master -f". hm, deleted commits are still viewable by their addresses.
					//this is dependent clause if no "who" is here further
					$maybeadepcl=true;
					for($i=$key+1;$i<count($inparr);$i++){
						if($inparr[$i]=='whom'||$inparr[$i]=='that'
						||$inparr[$i]=='s'||$inparr[$i]=='pr-si'||$inparr[$i]=='ed'){
							//there is another dep. clause
							$maybeadepcl=false;
							break;
							//with 'the boy ...' example i see this check is not enough
							//the boy that buy ed a bycicle walk s through park
							//this is not dependent clause
							//total 1 that, 2 ises... so just check for another "is". done, it is good.
						}
					}
					if(!$maybeadepcl){
						continue;
					}
				}else{
					continue;
				}
			}
			//i think, is it [(whom {we {(meet ed-pp) have]}) pr-si] or [(we {[(whom meet) ed-pp] have}) pr-si]
			//i think, as [red (big book)] and [big (red book)] are possible, i will make this the 1st way. no...
			//maybe, it should be even {[(we {[meet ed-pp] have}) pr-si] whom} . no, i do not think so...
			//i am going to use the second way, it seems more correct to me (ie with (whom meet) )... no...
			//and another way is {[we (whom {[meet ed-pp] have})] pr-si}
			//and another way is {we [(whom {[meet ed-pp] have}) pr-si]}
			//i will make it 1st way... i should make only 1 commit from these .. but i have made 2 already. i will left them, and will try to not hurry with commiting further...
			//btw, i have made {[he ({[(the bug) read] ed-pp} have)] s},
			//but should not it be {[({he [(the bug) read]} ed-pp) have]s} ? - i think no
			if($inparr[$key-1]=='have'||$inparr[$key-1]=='be'||isset($dic[$inparr[$key-1]])&&$dic[$inparr[$key-1]]['type']=='verb'){
				//var_dump($inparr);
				if($inparr[0]=='whom'||$inparr[0]=='that'){
					$whoword=array_splice($inparr,0,1);
					$whoword=$whoword[0];
					$key--;
				}
				$subject=array_splice($inparr,0,$key-1);//all before has or is
				//inparr is without subject now
				array_splice($inparr,1,1);//remove s
				if(count($subject)>1){//i have seen there should be >2 and will replace in other places <- this was mistake
					$subject=order($subject);
				}elseif(count($subject)==1){
					//i have seen that he is in array and fix
					$subject=$subject[0];
				}elseif(count($subject)==0){
					unset($subject);
				}
				if(count($inparr)>1){
					$inparr=order($inparr);
				}
				$outparr[]=array();//outparr0
				if(isset($whoword)){
					$outparr[0][]=$whoword;
					if(isset($subject)){
						$outparr[0][]=array();
						$outparr[0][1][]=$subject;
						$outparr[0][1][]=$inparr;
					}else{
						$outparr[0][]=$inparr;
					}
				}else{
					$outparr[0][]=$subject;
					$outparr[0][]=$inparr;
				}
				$outparr[]=$word;//outparr1//s or ed or pr-si
				return $outparr;
			}
			//i have written this, but process goes into the 2nd "have"... i will just comment that out... for now... the s block of the have block... made, and the previous example have been broken... i see he is removed already... i will comment out the he block in have block also. done. previous example works, and i see "the" is already removed in new example. i think hard to fix, try to comment out the the block. done, the previous example is incorrect now. it has incorrect order {[the last known]bug}. then i have commented out block of last noun.
			//if i just try to order with the, and stop it after checking its top/main word is "s", that will not work if i will check correctly ie not only for "s", but also for present and past simple. no, it will work, but incorrectly. the first "have" is going to be processed first, but it is not main, it is only of dependent clause. i will try to make correctly now, not after trying to order "the". done. ordering "the" is done.
			//else{
			//}
		}elseif($word=='have'||$word=='be'){
			/*if($inparr[$key+1]=='s'){
				array_splice($inparr,$key+1,1);//remove s
				if(count($inparr)>1){
					$inparr=order($inparr);
				}
				$outparr[]=$inparr;
				$outparr[]='s';
				return $outparr;
			}else*/
			if($key==0){//have is 1st
				if($inparr[2]=='ed-pp'){//have(0) do(1) ed(2)
					array_splice($inparr,0,1);//remove have
					if(count($inparr)>1){
						$inparr=order($inparr);
					}
					$outparr[]=$inparr;
					$outparr[]=$word;
					return $outparr;
				}
			}/*else{
				if($key==1){//have in 2nd place
					$removedword=array_splice($inparr,0,1);//remove he
					if(count($inparr)>1){
						$inparr=order($inparr);
					}
					$outparr[]=$removedword[0];
					$outparr[]=$inparr;
					return $outparr;
				}
			}*/
		}elseif($word=='ed-pp'&&$key==1&&count($inparr)>2){
			//seems 'built last year' should come here but it does not.
			//probably it goes to the "verb & key=0" in external recursion
			//that is fixed
			array_splice($inparr,1,1);//remove ed-pp
			if(count($inparr)>1){
				$inparr=order($inparr);
			}
			$outparr[]=$inparr;
			$outparr[]='ed-pp';
			return $outparr;
		}elseif(isset($dic[$word])&&$dic[$word]['type']=='verb'&&$key==0&&($inparr[1]=='the'||$inparr[1]=='a')){
			array_splice($inparr,0,1);//remove verb
			//this will work incorrectly with "read the bug through tracker"
			if(count($inparr)>1){
				$inparr=order($inparr);
			}
			$outparr[]=$inparr;
			$outparr[]=$word;
			return $outparr;
			//i should make dictionary with (several) properties (instead of word-per-word translations) (i need it now because i need check whether morphem is verb)
		}elseif(isset($dic[$word])&&$dic[$word]['type']=='verb'&&$key==0&&$inparr[1]!='ed-pp'&&$inparr[1]!='er'){//this is not 'have' nor 'be'
			//i am going to make "go to school every day"
			//are there any dependent clauses? is not it something like "go to school {that was closed on monday}"?
			//if it is , is not it "go {to school that was closed} {on monday}"?
			//how to choose?
			foreach($inparr as $thatskey=>$isitthat){
				if($isitthat=='that'||$isitthat=='whom'){
					$thereisathat=true;
					break;
				}
			}
			if(!isset($thereisathat)){
				for($i=count($inparr)-1;$i>=0;$i--){
					if($inparr[$i]=='every'||$inparr[$i]=='to'||$inparr[$i]=='from'||$inparr[$i]=='through'||$inparr[$i]=='last'){//preposition or adverb
						$verb=array_splice($inparr,0,$i);
						//'built last year' is probably broken here, it should not come here
						//i have added &&$inparr[1]!='ed-pp' in if condition (16 lines upper) and it is fixed
						if(count($verb)>1){
							$verb=order($verb);
							//"i go to school every day" is almost ordered now, need to (write code to) order "to school" and remove excessive array from "(go)"
						}else{
							$verb=$verb[0];
						}
						if($inparr[0]=='to'||$inparr[0]=='from'||$inparr[0]=='through'){//preposition
							$prep=array_splice($inparr,0,1);
							$prep=$prep[0];
							if(count($inparr)>1){//inparr is now "school" or "good school"
								$inparr=order($inparr);
							}else{
								$inparr=$inparr[0];
							}
							$tmpa=array();
							$tmpa[]=$inparr;
							$tmpa[]=$prep;
							$inparr=$tmpa;
							unset($tmpa);
							//$inparr[0]=$inparr;
							//$inparr[1]=$prep;
							//this (above and commented out) code does not work, gives strange result (sthool go) instead of ((school to) go)
							//"i go to school every day" is "ordered" completely
						}
						$outparr[]=$inparr;
						$outparr[]=$verb;
						return $outparr;
					}
				}
			}else{
				//echo'*';
				//walk through park that is built last year
				//what if it is 'walk through park that is built last year by hands'?
				//{[walk through park that is built last year] [by hands]}
				//or {walk [through (park {that [is ({built last year} {by hands})]})]}?
				//i am going to do with the first way
				//probably i need to choose every case differently... "last year" also can be ordered differently. "last year" should be set the second way. i even needed not to add "by hands" to make an example.
				//{{walk through park that is built} last year}
				//or {walk (through {park [that is {built last year}]})}
				//i will suppose that there is only one 'that' and all after it is related to it, and i am going to join blocks with prepositions or adverbs before it except the one that is most near to "that" to verb first, then add preposition block with 'that'...
				for($i=$thatskey;$i>=0;$i--){
					if($inparr[$i]=='through'){//preposition
						break;
					}
				}
				$thatsblock=array_splice($inparr,$i);
				//inparr is "verb block" now
				if(count($inparr)>1){
					$inparr=order($inparr);
				}else{
					$inparr=$inparr[0];
				}
				if(count($thatsblock)>1){
					$thatsblock=order($thatsblock);
				}else{
					$thatsblock=$thatsblock[0];
				}
				$outparr[]=$thatsblock;
				$outparr[]=$inparr;
				//return 0; // i see teacher whom ... has come here and have been broken. and it is (the 0) - it is not correct. i have added &&$inparr[1]!='er' in condition and it does not come here and is fixed.
				return $outparr;
				//get: {[(that is ...)(through park)] walk} - not correct... but that is not fail of this code.
			}
		}elseif(($word=='the'||$word=='through')&&$key==0){
			$inparrtry=$inparr;
			array_splice($inparrtry,0,1);//remove the
			if(count($inparrtry)>1){
				$inparrtry=order($inparrtry);
			}
			if($inparrtry[1]=='s'||$inparrtry[1]=='pr-si'||$inparrtry[1]=='ed'){
				//why is this? the | know n - process , the | know s - go out - that is impossible.. may be to go out from incorrect ordering. i have tried to search in github site for "unset($inparrtry);" but it shows just index2.php , not commit. then i have looked at script output for "the"s and i see error messages at top of page... the teacher... example is broken
				//i have remembered this. this makes a try ordering.
				//the {teacher ...}
				//it was ordered as {[... have] s}
				//so the teacher... was going out of there. but now it does not.
				//that is fixed, by adding !=er at condition
				unset($inparrtry);
				continue;
			}
			if($word=='the'){
				$outparr[]=$word;
				$outparr[]=$inparrtry;
			}else{
				$outparr[]=$inparrtry;
				$outparr[]=$word;
			}
			//'they walk through ...' is ordered properly now... going to fix 'the teacher ...'. done
			return $outparr;
			//he had read the last known bug
			//i see "last know ed bug", it can be {subject verb object}, but it is not "knowed", it is "known", for that i will replace ed to ed-pp (past participle). no. i replace it to en. no. en is used itself in texts, change back.
		}elseif(($word=='whom'||$word=='that')&&$key>0){
			//this code separates : teacher | whom ...
			$inparrtry=$inparr;
			$main=array_splice($inparrtry,0,$key);
			//main will be all before "whom" ie "teacher" in last example
			//inparrtry will be whom and all after it
			$depcl=0;//dependent clause count
			foreach($inparrtry as $wordtry){
				if($wordtry=='s'||$wordtry=='pr-si'||$wordtry=='ed'){
					$depcl--;
				}elseif($wordtry=='whom'||$wordtry=='that'){
					$depcl++;
				}
			}
			//is es and who es are counted inside {who and all after it}
			if($depcl==-1){
				//if there is one s without who pair, this is not just a who block
				//do not process, go out to next word
				unset($inparrtry);
				continue;
			}else{
				//depcl probably = 0 , this is who block
				unset($inparr);
			}
			//i have ordered (separated and set in hierarchy) dependent clauses of the example
			//"if(count($inparrtry)>2){" - this was correct when it was "1"! because 2 words can be in incorrect order! will replace back!
			//ini_set('memory_limit','512M');
			if(count($inparrtry)>1){
				$inparrtry=order($inparrtry);
			}
			if(count($main)>1){
				$main=order($main);
			}elseif(count($main)==1){
				$main=$main[0];
			}
			//and i get "Fatal error:  Allowed memory size of 134217728 bytes exhausted (tried to allocate 65488 bytes) in C:\xampp\htdocs\tarjima\index2.php on line 413" after writing the 2 "if" blocks above. try to unset a copy of array. has not helped. will edit php.ini... actually i add ini_set... tried 256M and it says: "Allowed memory size of 268435456 bytes exhausted (tried to allocate 24 bytes)". tried 512M and i see: "Allowed memory size of 536870912 bytes exhausted (tried to allocate 65488 bytes)". i think it goes into infinit recursion... adding the "&&$key>0" in "elseif" above have fixed this.
			$outparr[]=$inparrtry;
			$outparr[]=$main;
			return $outparr;
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


//echo'<br/>';
echo'<pre>';
$dic=array('bug'=>array('type'=>'noun'),'read'=>array('type'=>'verb'));
$engtext2=order($engtext2);
print_r($engtext2);
echo'</pre>';

echo'<br/>';
echo nstd_to_str($engtext2);


echo'<br/>';
$result=tr_simple_block($engtext2);
//echo'<pre>';
//print_r($result);
echo nstd_to_str($result);

echo'<br/>';
$engtext='the teacher whom we have met has read the bug that was mentioned';
$engtext=explode(' ', $engtext);
$dic['mention']=array('type'=>'verb');//i think about making two arrays - verbs and noun-likes
$dic['have']=array('type'=>'verb');
$dic['teach']=array('type'=>'verb');
$engtext2=explode_words_into_morphemes($engtext);
print_r($engtext2);

//echo'<br/>';
echo'<pre>';
$engtext2=order($engtext2);
print_r($engtext2);
//just tried order and i see totally incorrect,
//this, but with ed-pp-s etc: {the [(teacher whom we have met have read the bug that was mentioned) s]}
//now i am going to edit the order()
echo'</pre>';

echo'<br/>';
$words['whom']='кемне';
$words['we']='без';
$words['pr-si']='а';
$words['teach']='укыт';
$words['er']='учы';
$words['that']='кайсы';
$words['mention']='искәал';
$words['be']='бул';
$words['ed']='ды';
$words['meet']='очрат';
$result=tr_simple_block($engtext2);
//echo'<pre>';
//print_r($result);
//var_dump($result);
//echo'</pre>';
echo nstd_to_str($result);
//it is now: тегекемнебезочратпкуйаукытучытегекайсыискәаллганбулдыбагныукыды
//should be: (теге)без очраткан укытучы (теге)искә алынган багны укыды
//лган is fixed now
//going to fix кайсыискәалынганбулды, to искәалынган
//that means thatwasmentioned->mentioned
//done. i think it should have been кайсыискәалынганиде or кайсыискәалынганбулган but that is not important now
//going to change кемнебезочратпкуйа to безочраткан
//ie whomwehavemet->wemet
//done, example is translated, теге-s (translation of the) are left
//result: тегебезочратканукытучытегеискәалынганбагныукыды
//now i will try examples from index.php
//i go to school every day
//this file is index2.php and index.php is older translator and its technology is other...

echo'<br/>';
$engtext='i go to school every day';
$engtext=explode(' ', $engtext);
$dic['go']['type']='verb';
$engtext2=explode_words_into_morphemes($engtext);
print_r($engtext2);

echo'<pre>';
$engtext2=order($engtext2);
print_r($engtext2);
//i see no ordering (is made). pr-si is used by order(), so i should add it in explode_words_into_morphemes()... for this situation... should only add in $dic
//$dic['go']['type']='verb';//has not changed anything <-this was incorrect place
//pr-si (is) added, but (there is) still no order
//fixed, pr-si is ordered and "i" is ordered
echo'</pre>';

//i go to school is ordered, try to translate it
$words['i']='мин';
$words['every']='һәр';
$words['day']='көн';
$words['to']='кә';
$words['go']='бар';
$result=tr_simple_block($engtext2);
echo'<pre>';
print_r($result);
//i see pr-si is disappeared
echo'</pre>';
//echo'<br/>';
echo nstd_to_str($result);
//минһәркөнмәктәпкәбара
//мин һәр көн мәктәп кә бар а
//should be мин һәр көн мәктәп кә бар а м or мин көн саен мәктәп кә бар а м
//i could not code (nor write comments) yesterday (12 dec 2013) because made other things
//i think (and thought yesterday):
//how to order the m?
//then i thought: it is copula ie like english "is" because it is like suffix used like copula when sentence is of type a is b: it is m, but it also can be min , i think it is just shortened version , and, the min is used as:
// мин кеше мен - i am man, ул кеше дер - he is man, без кеше без - we are man
// same with verbs in present simple:
// мин бар а мын - i go, ул бар а дыр - he goes, без бар а быз - we go.
// so it should be {мин [({һәр көн мәктәп кә бар} а) м]}
// мин кеше мен should be {мин [кеше мен]}
// but, it also can be:
// {[(мин бар) а] мын}
// {[мин кеше] мен}
// and , also , i made english translations as:
// {[he go] es}
// but they can be:
// {he [go es}}
// i am not sure which is correct...
// i have searched in google images for "grammar dependency" and i have found a program named nltk written in python. it is aldo hosted in github. but i do not know whether it can give sentence as nested arrays (probably can give that in some form, because i have seen that it can give that as image), and as i see from their book, they do not separate morphems and do not make binary tree as i do (but they set some things as branches , more than 2, in one level: {fine fat trout}, {saw [a man] [in the park]}). also i am not sure whether they set dependent clauses in (parent) tree.
//but that program has lots of things already done, as i understand...
//i think i will also use 3 branches at one level in cases like "book, table, pen"
// continue about my example. so, in this case i am going to use other order with that. may be i should use same structure for them all.
//so i should either make {[(мин бар) а] мын}
//or edit previous orders to make them as {he [go es}} and make latest example as {мин [(бар а) мын]}.
//i am going to order as i did ie {[(мин бар) а] мын}. done.
//минһәркөнмәктәпкәбарам

//i have installed yesterday visual studio express 2013 to try help with university c++ (home?) task to a person...
//and i am sitting in win8
//(i could not update to win 8.1 several days ago)
//but i sat several years in linux
//and i am making this program in npp and xampp
//and use github for windows (also have used terminal)
//i have moved to win8 because it is installed in new laptop, and it was bought in hurry from little choice of 2 laptops
//i started this program in my old computer, with debian 6, gnome, gedit (, apache, php)

//i think i cannot save my codes in secret
//for that i opensource them
//though i could save them in some level of secret, if god wants
//and software ideas are not patented in russia and patenting requires money in usa, and my this code in php itself is almost useless, almost not reusable, gpl license would only guard code, but not ideas ({ideas are guarded from patenting} by publishing), so i have not set license like gpl for this
// (and even code cannot be well guarded with gpl, because it is open and can be copied, though , even closed source programs can be disassembled etc )

//i would like to use something like gnu affero lesser gpl v3 with requirement of attribution to authors... but i like not only attribution to authors of copied or copied and used/modified/processed codes, but also of used ideas that have been got from here ... no, that may be too many, widely known ideas' authors are not shown on every usage, just in some articles... that is ok... so just common good faith rules should be used about ideas...
//or i would like to just say: everybody use your good faith rules and be afraid of punishment in hereafter ...
//these do not mean that i am sure that there are super-duper new ideas
//i think my linguistic ideas are simple and may be known to many people, just they do not publish it ...

//just a next example from index php. no new grammar, only lexems
echo'<br/>';
$engtext='we go from park every morning';
$engtext=explode(' ', $engtext);
$engtext2=explode_words_into_morphemes($engtext);
print_r($engtext2);
echo'<pre>';
$engtext2=order($engtext2);
print_r($engtext2);
echo'</pre>';
$words['morning']='иртә';
$words['from']='тан';
$words['park']='парк';
$result=tr_simple_block($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
echo nstd_to_str($result);
//result:безһәриртәпарктанбарабыз
//better might be: безһәриртәпарктан кайтабыз which means we return / go back / come back ... but it depends on meaning, context
//and, "безһәриртәпарктанбарабыз" also and usually means "we go through park ..."
//for that, i am going to fix this. done. безһәриртәпарктанкайтабыз
//i commited. but this is not correct. english text does not have "return" meaning
//better to translate без ... чыгабыз or ... без ... чыгып барабыз or без ... тарафыннан барабыз
//i am going to make the last translation. done

//just a next example from index php. i thought also no new grammar, only lexems... but no, "that" clause should be ordered correctly
echo'<br/>';
$engtext='they walk through park that is built last year';
$engtext=explode(' ', $engtext);
$dic['walk']['type']='verb';
$engtext2=explode_words_into_morphemes($engtext);
print_r($engtext2);
echo'<pre>';
$dic['build']['type']='verb';
$engtext2=order($engtext2);
print_r($engtext2);
echo'</pre>';
$words['build']='төзе';
$words['they']='алар';
$words['walk']='йөре';
$words['through']='аша';
$words['year']='ел';
$result=tr_simple_block($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
echo nstd_to_str($result);
//after some editions i have аларсоңгыелтөзелганпаркашабара
//need to fix: соңгы->узган (last). done
//and may be йөри instead of бара (walk). changed $words['walk'], but need to fix ending, йөреа -> йөри (i would and sometimes do spell it йөрей)
//and may be ясалган instead of төзелгән (built)
//need to fix ган->гән (if төзелгән). done
//i was going to fix йөреа to йөри but have found important order bug:
// {[they ({that is built last year} {walk through park})] pr-si}
//should be:
// {[they ( {through park that is built last year} walk )] pr-si}
//after coding get: {[(that is ...)(through park)] walk} - inner ordering should be fixed
//seems because of code that is for "bug that was mentioned"
//this just should not come into it. fixed. but i have seen that other examples are broken...
//'they walk through ...' is ordered properly now... going to fix 'the teacher ...'. done.
//"йөреа" is fixed. example is ready. аларузганелтөзелгәнпаркашайөрей


//next example from index.php
echo'<br/>';
$engtext='the boy that bought a bicycle walks through park';
$engtext=explode(' ', $engtext);
$dic['buy']['type']='verb';
$engtext2=explode_words_into_morphemes($engtext);
print_r($engtext2);
echo'<pre>';
//$dic['build']['type']='verb';
$engtext2=order($engtext2);
print_r($engtext2);
echo'</pre>';
$words['buy']='сатыпал';
$words['boy']='малай';
$words['s']='а';
$words['a']='бер';
$words['bicycle']='велосипед';
$result=tr_simple_block($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
echo nstd_to_str($result);
//тегекайсыбервелосипедсатыпалдымалайпаркашайөрей
//should be:
//теге велосипед сатыпал ган малай парк аша йөре й
//ie need to fix: кайсы бер велосипед сатыпал ды -> велосипед сатыпал ган
//done. тегевелосипедсатыпалганмалайпаркашайөрей

//i am 29 years old, my name is dinar qurbanov
//i have been thinking about machine translation and language structure nearly since 1997
//today is 2013-december-15
//some my dependency analysis graphs/images/diagrams (i do not know english well and lazy to ask in chat...) of {nearly 1999 } - {spring of 2001}
//http://qdb.narod.ru/tattyazmagif/qaradaft03.gif
//http://qdb.narod.ru/tattyazmagif/qaradaft57.gif
//http://qdb.narod.ru/tattyazmagif/qaradaft58.gif
//http://qdb.narod.ru/tattyazmagif/qaradaft60.gif

//i have known out about apertium project near 1-2 years ago, and have seen 2-3 weeks ago that they do not make long-distance reordering of words in english to qazaq translation. as i understand, they cannot do that with apertium. i have asked about this mikel forcada, one of apertium developers..., in #apertium irc chat, which is in freenode.net
//several days ago i have found nltk.org project and see nobody is going to make machine translation with it.
//i am astonished/amazed/surprized
//proofs: NLTK Project Ideas http://wu.ourproject.org/moin/projects/nltk/ProjectIdeas - nothing
//searched for "translation" in dev and users mailing lists - almost nothing
//https://groups.google.com/forum/#!searchin/nltk-users/translation/nltk-users/hhwtaJVhPR8/5tYNNHLuRs0J
//"... I am new to NLTK and looking forward to develop a translation service from english to kannada ..."
//answer: "... NLTK will only provide about 1% of the support you need for building such a system ..."
//i do not agree with that. i have applied to (join) the list/forum, to say about great translation possibilities
//my membership is still pending at 17 dec, may be they have thought that i am spammer. i had written in application message "i want and should to say something about machine translation".
//their latest commit in github is of 3 days ago, this makes me hopeful
//indeed. "subscription request is approved". i am sorry for i have hurriedly written about that here...
//i have posted in the forum: https://groups.google.com/forum/#!topic/nltk-users/lcyWvCQBUmc "i think it is easy to make high quality translator with nltk".
//i have installed nltk today in 28 dec 2013 and i tried to make dependency with nltk and see that examples i had seen in http://nltk.org/book/ch08.html and http://nltk.org/ do not do that for whole english (ie almost any sentence), but are little examples.
//i think: why these apertium and nltk ? , seems it is easy to code grammar in php or other language like it - python, c++. and apertium sets unneeded grammatical properties, as i have seen in qazaq-tatar ... i do not set suffixes as grammatical propeties of words, but use (handle) them as words , because whether they are written joined or separated is not important... and they are easily separated in tatar - they are not like in some languages like russian or latin ... and in english also they are few and easily separated.
//i have searched for "nltk english grammar" in google and have come to http://stackoverflow.com/questions/6115677/english-grammar-for-parsing-in-nltk then to https://github.com/emilmont/pyStatParser but have not tried it yet

//also want to say about that in apertium mailing lists, that there is great possibility with nltk
//and i think, even may be apertium should be "dropped", especially for language pairs where much and long distance word reordering between them in translation. but i do not know apertium well, but as i know, it does not use dependency structure
//i see apertium has "cascaded interchunk" possibility...

//thank to god for that i am making this super-duper translator ... it seems very good technology for me , because it seems it can be better than google translate and apertium , if become made completely at some degree... i will check other translators with languages similar to tatar and will show results , if god wants ...

//next example from index.php
echo'<br/>';
$engtext='last year';
$engtext=explode(' ', $engtext);
//$dic['buy']['type']='verb';
$engtext2=explode_words_into_morphemes($engtext);
print_r($engtext2);
echo'<pre>';
//$dic['build']['type']='verb';
$engtext2=order($engtext2);
print_r($engtext2);
echo'</pre>';
//$words['buy']='сатыпал';
$result=tr_simple_block($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
echo nstd_to_str($result);
//just works...


//next example from index.php
echo'<br/>';
$engtext='they walk through park';
$engtext=explode(' ', $engtext);
//$dic['buy']['type']='verb';
$engtext2=explode_words_into_morphemes($engtext);
print_r($engtext2);
echo'<pre>';
//$dic['build']['type']='verb';
$engtext2=order($engtext2);
print_r($engtext2);
echo'</pre>';
//$words['buy']='сатыпал';
$result=tr_simple_block($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
echo nstd_to_str($result);
//just works...


//next example from index.php
echo'<br/>';
$engtext='they walked through park last year';
$engtext=explode(' ', $engtext);
//$dic['buy']['type']='verb';
$engtext2=explode_words_into_morphemes($engtext);
print_r($engtext2);
echo'<pre>';
//$dic['build']['type']='verb';
$engtext2=order($engtext2);
print_r($engtext2);
echo'</pre>';
//$words['buy']='сатыпал';
$result=tr_simple_block($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
echo nstd_to_str($result);
//fixed some errors and it works

//all examples from index.php are translated


include'index2-2.php';






















?>