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
			}else{
				$engtext2[]=$word;
			}
		}elseif(mb_substr($word,-2,2)=='ed'){
			$engtext2[]=mb_substr($word,0,mb_strlen($word)-2);
			if($engtext2[count($engtext2)-3]=='be'||$engtext2[count($engtext2)-3]=='have'){
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
						if($inparr[$i]=='whom'||$inparr[$i]=='that'){
							//there is another dep. clause
							$maybeadepcl=false;
							break;
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
			if($inparr[$key-1]=='have'||$inparr[$key-1]=='be'){
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
		}elseif($word=='have'||$word=='be'){
			/*if($inparr[$key+1]=='s'){
				array_splice($inparr,$key+1,1);//remove s
				if(count($inparr)>1){
					$inparr=order($inparr);
				}
				$outparr[]=$inparr;
				$outparr[]='s';
				return $outparr;
			}else*/if($key==0){//have is 1st
				if($inparr[2]=='ed-pp'){
					array_splice($inparr,0,1);//remove have
					if(count($inparr)>1){
						$inparr=order($inparr);
					}
					$outparr[]=$inparr;
					$outparr[]=$word;
					return $outparr;
				}
			}else{
				/*if($key==1){//have in 2nd place
					$removedword=array_splice($inparr,0,1);//remove he
					if(count($inparr)>1){
						$inparr=order($inparr);
					}
					$outparr[]=$removedword[0];
					$outparr[]=$inparr;
					return $outparr;
				}*/
			}
		}elseif($word=='ed-pp'&&$key==1&&count($inparr)>2){
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
			$inparrtry=$inparr;
			array_splice($inparrtry,0,1);//remove the
			if(count($inparrtry)>1){
				$inparrtry=order($inparrtry);
			}
			if($inparrtry[1]=='s'||$inparrtry[1]=='pr-si'||$inparrtry[1]=='ed'){
				unset($inparrtry);
				continue;
			}
			$outparr[]='the';
			$outparr[]=$inparrtry;
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
				//do not process, go out
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


echo'<br/><pre>';
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

echo'<br/><pre>';
$engtext2=order($engtext2);
print_r($engtext2);
//just tried order and i see totally incorrect,
//this, but with ed-pp-s etc: {the [(teacher whom we have met have read the bug that was mentioned) s]}
//now i am going to edit the order()
echo'</pre>';















?>