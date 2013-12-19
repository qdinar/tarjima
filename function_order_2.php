<?php

function order_2($inparr){
	global $dic;
	$outparr=array();


	if($inparr[count($inparr)-1]['w']=='.'){
		$dot=array_splice($inparr,-1);
		if(count($inparr)>1){
			$inparr=order_2($inparr);
		}
		$dot=$dot[0];
		$outparr[]=$inparr;
		$outparr[]=$dot;
		return $outparr;
	}

	foreach($inparr as $key=>$word){
		if($word['w']==','){
			if(isset($dic[$inparr[$key+1]['w']])&&$dic[$inparr[$key+1]['w']]['type']=='verb'){
				$verb=array_splice($inparr,$key+1);
				array_splice($inparr,$key);//remove comma
				//inparr is "for ..." now,  of "for ... , see ..."
				if(count($inparr)>1){
					$inparr=order_2($inparr);
				}else{
					$inparr=$inparr[0];
				}
				if(count($verb)>1){
					$verb=order_2($verb);
				}else{
					$verb=$verb[0];
				}
				$outparr[]=$inparr;
				$outparr[]=$verb;
				return $outparr;
			}
		}
	}

	foreach($inparr as $key=>$word){
		if($word['w']=='s'||$word['w']=='pr-si'||$word['w']=='ed'){
			for($i=0,$dependentcl=0,$whoes=0,$ises=0;$i<$key;$i++){
				if($inparr[$i]['w']=='whom'||$inparr[$i]['w']=='that'){
					$dependentcl++;
					$whoes++;
				}elseif($inparr[$i]['w']=='s'||$inparr[$i]['w']=='pr-si'||$inparr[$i]['w']=='ed'){
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
					if($inparr[$i]['w']=='whom'||$inparr[$i]['w']=='that'){
						$dependentcl++;
					}elseif($inparr[$i]['w']=='s'||$inparr[$i]['w']=='pr-si'||$inparr[$i]['w']=='ed'){
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
						if($inparr[$i]['w']=='whom'||$inparr[$i]['w']=='that'
						||$inparr[$i]['w']=='s'||$inparr[$i]['w']=='pr-si'||$inparr[$i]['w']=='ed'){
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
			if($inparr[$key-1]['w']=='have'||$inparr[$key-1]['w']=='be'||isset($dic[$inparr[$key-1]['w']])&&$dic[$inparr[$key-1]['w']]['type']=='verb'){
				//var_dump($inparr);
				if($inparr[0]['w']=='whom'||$inparr[0]['w']=='that'){
					$whoword=array_splice($inparr,0,1);
					$whoword=$whoword[0];
					$key--;
				}
				$subject=array_splice($inparr,0,$key-1);//all before has or is
				//inparr is without subject now
				array_splice($inparr,1,1);//remove s
				if(count($subject)>1){//i have seen there should be >2 and will replace in other places <- this was mistake
					$subject=order_2($subject);
				}elseif(count($subject)==1){
					//i have seen that he is in array and fix
					$subject=$subject[0];
				}elseif(count($subject)==0){
					unset($subject);
				}
				if(count($inparr)>1){
					$inparr=order_2($inparr);
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
		//}elseif($word['w']=='have'||$word['w']=='be'){
			/*if($inparr[$key+1]=='s'){
				array_splice($inparr,$key+1,1);//remove s
				if(count($inparr)>1){
					$inparr=order_2($inparr);
				}
				$outparr[]=$inparr;
				$outparr[]='s';
				return $outparr;
			}else*/
			/*if($key==0){//have is 1st
				if($inparr[2]['w']=='ed-pp'){//have(0) do(1) ed(2)
					array_splice($inparr,0,1);//remove have
					if(count($inparr)>1){
						$inparr=order_2($inparr);
					}
					$outparr[]=$inparr;
					$outparr[]=$word['w'];
					return $outparr;
				}
			}*//*else{
				if($key==1){//have in 2nd place
					$removedword=array_splice($inparr,0,1);//remove he
					if(count($inparr)>1){
						$inparr=order_2($inparr);
					}
					$outparr[]=$removedword[0];
					$outparr[]=$inparr;
					return $outparr;
				}
			}*/
		}elseif($word['w']=='ed-pp'&&$key==1&&count($inparr)>2){
			//seems 'built last year' should come here but it does not.
			//probably it goes to the "verb & key=0" in external recursion
			//that is fixed
			array_splice($inparr,1,1);//remove ed-pp
			if(count($inparr)>1){
				$inparr=order_2($inparr);
			}
			$outparr[]=$inparr;
			$outparr[]='ed-pp';
			return $outparr;
		}elseif(isset($dic[$word['w']])
				&&$dic[$word['w']]['type']=='verb'
				&&$key==0
				&&($inparr[1]['w']=='the'
					||$inparr[1]['w']=='a'
					||isset($inparr[1]['thisisabbreviation'])
				)
		){
			array_splice($inparr,0,1);//remove verb
			//this will work incorrectly with "read the bug through tracker"
			if(count($inparr)>1){
				$inparr=order_2($inparr);
			}else{
				$inparr=$inparr[0];
			}
			$outparr[]=$inparr;
			$outparr[]=$word;
			return $outparr;
			//i should make dictionary with (several) properties (instead of word-per-word translations) (i need it now because i need check whether morphem is verb)
		}elseif($word['w']==','){
			if($inparr[$key+1]['w']=='the'||$inparr[$key+1]['w']=='a'){
				$noun=array_splice($inparr,0,$key);
				array_splice($inparr,0,1);//remove comma
				if(count($inparr)>1){
					$inparr=order_2($inparr);
				}else{
					$inparr=$inparr[0];
				}
				if(count($noun)>1){
					$inparr=order_2($noun);
				}else{
					$noun=$noun[0];
				}
				$outparr[]=$inparr;
				$outparr[]=$noun;
				return $outparr;
			}
		}elseif(isset($dic[$word['w']])&&$dic[$word['w']]['type']=='verb'&&$key==0&&$inparr[1]['w']!='ed-pp'&&$inparr[1]['w']!='er'){
			if($word['w']=='have'||$word['w']=='be'){
				if($key==0){//have is 1st
					if($inparr[2]['w']=='ed-pp'){//have(0) do(1) ed(2)
						array_splice($inparr,0,1);//remove have
						if(count($inparr)>1){
							$inparr=order_2($inparr);
						}
						$outparr[]=$inparr;
						$outparr[]=$word;
						return $outparr;
					}
				}
			}
			//this is not 'have' nor 'be' <-this is not true now...
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
					if($inparr[$i]['w']=='every'||$inparr[$i]['w']=='to'||$inparr[$i]['w']=='from'||$inparr[$i]['w']=='through'||$inparr[$i]['w']=='last'||$inparr[$i]['w']=='about'){//preposition or adverb
						$verb=array_splice($inparr,0,$i);
						//'built last year' is probably broken here, it should not come here
						//i have added &&$inparr[1]['w']!='ed-pp' in if condition (16 lines upper) and it is fixed
						if(count($verb)>1){
							$verb=order_2($verb);
							//"i go to school every day" is almost ordered now, need to (write code to) order "to school" and remove excessive array from "(go)"
						}else{
							$verb=$verb[0];
						}
						if($inparr[0]['w']=='to'||$inparr[0]['w']=='from'||$inparr[0]['w']=='through'||$inparr[0]['w']=='about'){//preposition
							$prep=array_splice($inparr,0,1);
							$prep=$prep[0];
							if(count($inparr)>1){//inparr is now "school" or "good school"
								$inparr=order_2($inparr);
							}else{
								$inparr=$inparr[0]['w'];
							}
							$tmpa=array();
							$tmpa[]=$inparr;
							$tmpa[]=$prep;
							$inparr=$tmpa;
							unset($tmpa);
							//$inparr[0]['w']=$inparr;
							//$inparr[1]['w']=$prep;
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
					if($inparr[$i]['w']=='through'){//preposition
						break;
					}
				}
				$thatsblock=array_splice($inparr,$i);
				//inparr is "verb block" now
				if(count($inparr)>1){
					$inparr=order_2($inparr);
				}else{
					$inparr=$inparr[0]['w'];
				}
				if(count($thatsblock)>1){
					$thatsblock=order_2($thatsblock);
				}else{
					$thatsblock=$thatsblock[0];
				}
				$outparr[]=$thatsblock;
				$outparr[]=$inparr;
				//return 0; // i see teacher whom ... has come here and have been broken. and it is (the 0) - it is not correct. i have added &&$inparr[1]['w']!='er' in condition and it does not come here and is fixed.
				return $outparr;
				//get: {[(that is ...)(through park)] walk} - not correct... but that is not fail of this code.
			}
		}elseif(($word['w']=='the'||$word['w']=='through'||$word['w']=='from'||$word['w']=='for')&&$key==0){
			$inparrtry=$inparr;
			array_splice($inparrtry,0,1);//remove the
			if(count($inparrtry)>1){
				$inparrtry=order_2($inparrtry);
			}
			if(isset($inparrtry[1]['w'])&&($inparrtry[1]['w']=='s'||$inparrtry[1]['w']=='pr-si'||$inparrtry[1]['w']=='ed')){
				//why is this? the | know n - process , the | know s - go out - that is impossible.. may be to go out from incorrect ordering. i have tried to search in github site for "unset($inparrtry);" but it shows just index2.php , not commit. then i have looked at script output for "the"s and i see error messages at top of page... the teacher... example is broken
				//i have remembered this. this makes a try ordering.
				//the {teacher ...}
				//it was ordered as {[... have] s}
				//so the teacher... was going out of there. but now it does not.
				//that is fixed, by adding !=er at condition
				unset($inparrtry);
				continue;
			}
			if($word['w']=='the'){
				$outparr[0]=$word;
				$outparr[1]=$inparrtry;
			}else{
				$outparr[0]=$inparrtry;
				$outparr[1]=$word;
			}
			//'they walk through ...' is ordered properly now... going to fix 'the teacher ...'. done
			return $outparr;
			//he had read the last known bug
			//i see "last know ed bug", it can be {subject verb object}, but it is not "knowed", it is "known", for that i will replace ed to ed-pp (past participle). no. i replace it to en. no. en is used itself in texts, change back.
		}elseif(($word['w']=='whom'||$word['w']=='that')&&$key>0){
			//this code separates : teacher | whom ...
			$inparrtry=$inparr;
			$main=array_splice($inparrtry,0,$key);
			//main will be all before "whom" ie "teacher" in last example
			//inparrtry will be whom and all after it
			$depcl=0;//dependent clause count
			foreach($inparrtry as $wordtry){
				if($wordtry['w']=='s'||$wordtry['w']=='pr-si'||$wordtry['w']=='ed'){
					$depcl--;
				}elseif($wordtry['w']=='whom'||$wordtry['w']=='that'){
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
				$inparrtry=order_2($inparrtry);
			}
			if(count($main)>1){
				$main=order_2($main);
			}elseif(count($main)==1){
				$main=$main[0];
			}
			//and i get "Fatal error:  Allowed memory size of 134217728 bytes exhausted (tried to allocate 65488 bytes) in C:\xampp\htdocs\tarjima\index2.php on line 413" after writing the 2 "if" blocks above. try to unset a copy of array. has not helped. will edit php.ini... actually i add ini_set... tried 256M and it says: "Allowed memory size of 268435456 bytes exhausted (tried to allocate 24 bytes)". tried 512M and i see: "Allowed memory size of 536870912 bytes exhausted (tried to allocate 65488 bytes)". i think it goes into infinit recursion... adding the "&&$key>0" in "elseif" above have fixed this.
			$outparr[]=$inparrtry;
			$outparr[]=$main;
			return $outparr;
		}elseif(isset($dic[$word['w']])&&$dic[$word['w']]['type']=='noun'&&$key==count($inparr)-1&&$inparr[$key-1]['w']=='ed-pp'){
			array_splice($inparr,$key,1);//remove noun
			if(count($inparr)>1){
				$inparr=order_2($inparr);
			}
			$outparr[]=$inparr;
			$outparr[]=$word['w'];
			return $outparr;
			//i see {last know ed-pp}. {(last know) n} or {last (know n)}? it is (usually) the 1st one, but how to select with program? if it is {last (know n)}, it would be {last (known bug)}...
		}elseif($word['w']=='last'&&$key==0&&$inparr[1]['w']=='know'&&$inparr[2]['w']=='ed-pp'&&count($inparr)==3){
			array_splice($inparr,2,1);//remove ed-pp
			$outparr[]=$inparr;
			$outparr[]='ed-pp';
			return $outparr;
		}
	}
	return $inparr;
	
}


?>