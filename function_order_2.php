<?php
function order_2_if_needed(&$inparr){
	//if(!isset($inparr[0])){echo'<pre>';print_r($inparr);exit;}
	if(count($inparr)>1){
		if(func_num_args()==2){
			$params=func_get_arg(1);
			$inparr=order_2($inparr,$params);
		}else{
			$inparr=order_2($inparr);
		}
	}else{
		$inparr=$inparr[0];
	}
}

function are_all_nouns($inparr){
	global $dic,$nounlikes;
	$tryallarenouns=true;
	foreach($inparr as $trynoun){
		// if(
			// isset($trynoun[1]['w'])
			// &&$trynoun[1]['w']=='er-comp'
		// ){
			// echo'OK';
		// }
		if(
			!(
				isset($trynoun['w'])
				&&(
					isset($dic[$trynoun['w']])
					&&$dic[$trynoun['w']]['type']=='noun'
					||isset($nounlikes[$trynoun['w']])
					||$trynoun['w']=='"'
					||$trynoun['w']=='('
					||$trynoun['w']==')'
				)
				||
				isset($trynoun[1]['w'])
				&&$trynoun[1]['w']==',,'
				||
				// !isset($trynoun['w'])
				// &&
				isset($trynoun[1]['w'])
				&&$trynoun[1]['w']=='er-comp'
				||
				// $trynoun['w']=='s-pl'
				// ||
				// $trynoun['w']==','
				// ||
				isset($trynoun[1]['w'])
				&&$trynoun[1]['w']=='ing'
			)
		){
			$tryallarenouns=false;
			break;
		}
	}
	return $tryallarenouns;
}


function order_all_nouns($inparr){
	global $multiwords;
	$outparr=array();
	//echo'nouns<pre>';print_r($inparr);echo'</pre>';
	foreach($inparr as $key=>$noun){
		if(
			isset($noun['w'])
			&&$noun['w']=='type'
			&&isset($inparr[$key+1])
			&&$inparr[$key+1]['w']=='three'
			&&$key>0
			&&$key<count($inparr)-2
		){
		//before and after "type three"
			$beforetype=array_splice($inparr,0,$key);
			/*if(count($beforetype)>1){
				$beforetype=order_2($beforetype);
			}else{
				$beforetype=$beforetype[0];
			}*/
			order_2_if_needed($beforetype);
			$typeblock=array_splice($inparr,0,2);
			$typeblock=order_2($typeblock);
			/*if(count($inparr)>1){
				$inparr=order_2($inparr);
			}else{
				$inparr=$inparr[0];
			}*/
			order_2_if_needed($inparr);
			$outparr[]=array($typeblock,$beforetype);
			$outparr[]=$inparr;
			goto return_outparr;//return $outparr;
		}elseif(
			isset($noun['w'])
			&&$noun['w']=='('
			&&$key>0
			&&$key<count($inparr)-2
		){
			for($i=count($inparr)-1;$i>1;$i--){
				if($inparr[$i]['w']==')'){
					$inparentheses=array_splice($inparr,$key+1,$i-$key-1);
					order_2_if_needed($inparentheses);
					if(isset($inparentheses['w'])&&$inparentheses['thisisabbreviation']==true){
						$countcaps=preg_match_all('/[A-Z0-9]/u',$inparentheses['w'],$matches);
					}
					$additionalwordsc=0;
					//print_r($matches);
					for($j=$key-1;$j>=0;$j--){
						//if($inparr[$j]['firstiscapital']==true){}
						//-1*($j-($key-1))=-j+(k-1)=k-1-j
						//cc-1-(k-1-j-awc)=cc-1-k+1+j+awc=cc-k+j+awc
						//echo $inparr[$j]['w'].' '.$matches[0][$countcaps-$key+$j+$additionalwordsc].' ; ';
						if(
							isset($inparentheses['thisisabbreviation'])
							&&mb_strtolower(mb_substr($inparr[$j]['w'],0,1))==mb_strtolower($matches[0][$countcaps-$key+$j+$additionalwordsc])
						){
							if($key-1-$j-$additionalwordsc==count($matches[0])-1){
								$itmatches=true;
								break;
							}
							//else{
								//continue;
							//}
						}
						elseif($inparr[$j]=='of'){
							$additionalwords++;
							//continue;
						}
						else{
							$itmatches=false;
							break;
						}
					}
					if($itmatches){
						$parenthesesfor=array_splice($inparr,$j,$key-$j);
						if($j>0){
							$beforebeforeparentheses=array_splice($inparr,0,$j);
						}
						order_2_if_needed($parenthesesfor);
						$parenthesesgroup=array(array($inparentheses,array('w'=>'()')),$parenthesesfor);
					}else{
						$beforeparentheses=array_splice($inparr,0,$key);
						$parenthesesgroup=array(array($inparentheses,array('w'=>'()')),$beforeparentheses);
					}
					//order_2_if_needed($beforeparentheses);
					$afterparentheses=array_splice($inparr,2);
					if(count($afterparentheses)==0){
						unset($afterparentheses);
						$inner=$parenthesesgroup;
					}else{
						order_2_if_needed($afterparentheses);
						$inner=array($parenthesesgroup,$afterparentheses);
					}
					if(isset($beforebeforeparentheses)){
						$outparr=$beforebeforeparentheses;
						$outparr[]=$inner;
					}else{
						$outparr=$inner;
					}
					goto return_outparr;//return $outparr;
					/*
					$beforeparentheses=array_splice($inparr,0,$key);
					order_2_if_needed($beforeparentheses);
					//count=8,key=2,i=5
					//01(34)67
					$inparentheses=array_splice($inparr,1,$i-$key-1);
					//print_r($inparentheses);
					order_2_if_needed($inparentheses);
					//print_r($inparentheses);
					//(12)45
					$afterparentheses=array_splice($inparr,2);
					if(count($afterparentheses)==0){
						unset($afterparentheses);
					}else{
						order_2_if_needed($afterparentheses);
					}
					//()23
					//$inparentheses['inparentheses']=true;
					$inparentheses=array($inparentheses,array('w'=>'()'));
					if(isset($afterparentheses)){
						$outparr[]=array($inparentheses,$beforeparentheses);
						$outparr[]=$afterparentheses;
					}else{
						$outparr[]=$inparentheses;
						$outparr[]=$beforeparentheses;
					}
					goto return_outparr;//return $outparr;
					*/
					/*
					$inparentheses=array_splice($inparr,$key+1,$i-$key-1);
					order_2_if_needed($inparentheses);
					$parentheses=array($inparentheses,array('w'=>'()'));
					array_splice($inparr,$key,2,'0');
					$inparr[$key]=$parentheses;
					*/
				}
			}
		}elseif(
			isset($noun['w'])
			&&$noun['w']=='"'
			&&$key<count($inparr)-1
		){
			for($i=count($inparr)-1;$i>0;$i--){
				if($inparr[$i]['w']=='"'){
					$beforequotes=array_splice($inparr,0,$key);
					if(count($beforequotes)==0){
						unset($beforequotes);
					}else{
						order_2_if_needed($beforequotes);
					}
					//count=8,key=2,i=5
					//01(34)67
					$inquotes=array_splice($inparr,1,$i-$key-1);
					//print_r($inquotes);
					order_2_if_needed($inquotes);
					//print_r($inparentheses);
					//(12)45
					$afterquotes=array_splice($inparr,2);
					if(count($afterquotes)==0){
						unset($afterquotes);
					}else{
						order_2_if_needed($afterquotes);
					}
					//()23
					$inquotes['inquotes']=true;
					if(isset($afterquotes)&&isset($beforequotes)){
						$outparr[]=array($inquotes,$beforequotes);
						$outparr[]=$afterquotes;
					}elseif(isset($beforequotes)){
						$outparr[]=$inquotes;
						$outparr[]=$beforequotes;
					}elseif(isset($afterquotes)){
						$outparr[]=$afterquotes;
						$outparr[]=$inquotes;
					}else{
						$outparr=$inquotes;
					}
					goto return_outparr;//return $outparr;
					/*
					$inquotes=array_splice($inparr,$key+1,$i-$key-1);
					order_2_if_needed($inquotes);
					$quotes=array($inquotes,array('w'=>'""'));
					array_splice($inparr,$key,2,'0');
					$inparr[$key]=$quotes;
					*/
				}
			}
		}elseif(
			isset($noun[1]['w'])
			&&$noun[1]['w']==',,'
			&&$key==count($inparr)-1
		){
			/*
			$described=$inparr[$key-1];
			$inparr[$key-1]=array($noun,$described);
			array_splice($inparr,$key);//remove
			//print_r($inparr);
			if(count($inparr)==1){
				$inparr=$inparr[0];
			}
			*/
			array_splice($inparr,-1);
			order_2_if_needed($inparr);
			$outparr[]=$noun;
			$outparr[]=$inparr;
			goto return_outparr;//return $outparr;
		}
	}


	if(
		isset($inparr[0]['w'])
		&&$inparr[0]['w']=='type'
	){
	//inside "type three"
		$type=array_splice($inparr,0,1); //cut out "type"
		/*if(count($inparr)>1){
			$inparr=order_2($inparr);
		}else{
			$inparr=$inparr[0];
		}*/
		order_2_if_needed($inparr);
		$outparr[]=$inparr;
		$outparr[]=$type[0];
		goto return_outparr;//return $outparr;
	//}elseif($key<count($inparr)-1){ // 5: 0,1,2,3,4. 5-1=4. <4 is 0,1,2,3.
	}
	$inparr=separate_a_mw($inparr);
	if(count($inparr)==2){
		$outparr=$inparr;
		goto return_outparr;
	}


	$firstnoun=array_splice($inparr,0,1);
	$firstnoun=$firstnoun[0];
	/*if(count($inparr)>1){
		$inparr=order_2($inparr);
	}else{
		$inparr=$inparr[0];
	}*/
	order_2_if_needed($inparr);
	$outparr[]=$firstnoun;
	$outparr[]=$inparr;
	goto return_outparr;//return $outparr;
	return_outparr:{
		//echo'out nouns<br>';
		return $outparr;
	}
}


function separate_a_mw($inparr){
	global $multiwords;
	$outparr=array();
	if(count($inparr)>2){//if at least 3 : 2 for multiword and 1 for word after, so that it do not do infinite recursion
		foreach($multiwords as $amultiword){
			$trythisismatched=true;
			foreach($amultiword as $mwkey=>$wordofmultiword){
				//echo'*';
				//echo($inparr[$key+$mwkey]);
				if(isset($inparr[$mwkey]['w'])&&$wordofmultiword==$inparr[$mwkey]['w']){
					continue;
				}else{
					$trythisismatched=false;
					break;
				}
			}
			if($trythisismatched==true){
				$foundmw=array_splice($inparr,0,$mwkey+1);
				$foundmw=order_2($foundmw);
				/*if(count($inparr)>1){
					$inparr=order_2($inparr);
				}else{
					$inparr=$inparr[0];
				}*/
				order_2_if_needed($inparr);
				$outparr[]=$foundmw;
				$outparr[]=$inparr;
				return $outparr;
			}else{
				continue;
			}
		}
	}
	return $inparr;
}














function try_last_plural(&$inparr,&$outparr){
	if(
		//isset($inparr[count($inparr)-1]['w'])&&
		$inparr[count($inparr)-1]['w']=='s-pl'
		&&count($inparr)>2
	){
		$tryarr=$inparr;
		$try=array_splice($tryarr,0,count($tryarr)-1);
		//echo'*<pre>';print_r($try);echo'</pre>';
		// echo'<pre>';print_r($inparr);echo'</pre>*';
		if(are_all_nouns($try)==true){
			//echo'OK';exit;
			//echo'ok:<pre>';print_r($try);echo'</pre>';
			$try=order_all_nouns($try);
			$outparr[]=$try;
			$outparr[]=$tryarr[0];
			//goto return_outparr;//return $outparr;
			return true;
		}
	}
	//return false;//default null
}

function try_last_dot(&$inparr,&$outparr){
	if(
		isset($inparr[count($inparr)-1]['w'])
		&&$inparr[count($inparr)-1]['w']=='.'
	){
		$dot=array_splice($inparr,-1);
		if(count($inparr)>1){
			$inparr=order_2($inparr);
		}
		$dot=$dot[0];
		$outparr[]=$inparr;
		$outparr[]=$dot;
		//goto return_outparr;//return $outparr;
		return true;
	}
}

function try_introduction(&$inparr,&$outparr){
	global $dic;
	foreach($inparr as $key=>$word){
		if(isset($word['w'])&&$word['w']==','){
			if(
				isset($inparr[$key+1]['w'])
				&&isset($dic[$inparr[$key+1]['w']])
				&&$dic[$inparr[$key+1]['w']]['type']=='verb'
				//this catched both for ... , see ...
				//and second comma in
				//... , an abbreviation for ... , is ...
				//but the second comma is other thing...
				//&&$inparr[$key+2]['w']!='s'
				//but if i add this my example becomes broken...
				//i need to do something with all commas, probably - done
				&&$inparr[0]['w']=='for'
				||
				$inparr[0]['w']=='in'
				//In computing
			){
				// if($inparr[0]['w']=='in'){
					// echo 'OK<pre>';
					// echo $key;
					// print_r ($inparr);
					// echo '</pre>';
					// $test=true;
				// }
				// echo 'OK<pre>';
				// echo $key;
				// print_r ($inparr);
				// echo '</pre>';
				/*
				$verb=array_splice($inparr,$key+1);
				array_splice($inparr,$key);//remove comma
				//inparr is "for ..." now,  of "for ... , see ..."
				order_2_if_needed($inparr);
				order_2_if_needed($verb);
				$outparr[]=array($inparr,array('w'=>','));
				$outparr[]=$verb;
				goto return_outparr;//return $outparr;
				*/
				$intro=array_splice($inparr,0,$key);
				order_2_if_needed($intro);
				//1st (0th) element of inparr is "," now
				$outparr[]=array($intro,array('w'=>'intro'));
				array_splice($inparr,0,1);
				order_2_if_needed($inparr);
				$outparr[]=$inparr;
				//goto return_outparr;
				// if($test){
					// echo 'OK2<pre>';
					// echo $key;
					// print_r ($inparr);
					// echo '</pre>';
				// }
				//break;
				//after finding 1 introductory i assume there are none other one
				return true;
			}
		}
	}
}

function try_comma_the(&$inparr,&$outparr){
	for($i=0;$i<count($inparr);$i++){
		if(isset($inparr[$i]['w'])&&$inparr[$i]['w']==','){
			if(isset($inparr[$i+1]['w'])&&($inparr[$i+1]['w']=='the'||$inparr[$i+1]['w']=='a'||$inparr[$i+1]['w']=='an')){
				/*
				$noun=array_splice($inparr,0,$key);
				array_splice($inparr,0,1);//remove comma
				order_2_if_needed($inparr);
				//$inparr['incommas']=true;
				order_2_if_needed($noun);
				$outparr[]=array($inparr,array('w'=>',,'));
				$outparr[]=$noun;
				goto return_outparr;//return $outparr;
				*/
				for($j=$i+1;$j<count($inparr);$j++){
					if($inparr[$j]['w']==','){
						break;
					}
				}
				$additionalinfo=array_splice($inparr,$i+1,$j-$i-1);
				order_2_if_needed($additionalinfo);
				//0,23,5
				//i=1,j=4
				array_splice($inparr,$i,1);//remove 1 of 2 commas
				$inparr[$i]=array($additionalinfo,array('w'=>',,'));
			}
		}
	}
}


function try_order_that_block(&$inparr,&$outparr){
	global $dic, $verbs;
	foreach($inparr as $key=>$word){
		if(
			// $word['w']=='s'
			// ||$word['w']=='pr-si'
			// ||$word['w']=='ed'
			isset($verbs[$word['w']])
		){
			for($i=0,$dependentcl=0,$whoes=0,$ises=0;$i<$key;$i++){
				if($inparr[$i]['w']=='whom'||$inparr[$i]['w']=='that'){
					$dependentcl++;
					$whoes++;
				}elseif(
					//$inparr[$i]['w']=='s'||$inparr[$i]['w']=='pr-si'||$inparr[$i]['w']=='ed'
					isset($verbs[$word['w']])
				){
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
						if(
							$inparr[$i]['w']=='whom'||$inparr[$i]['w']=='that'
							//||$inparr[$i]['w']=='s'||$inparr[$i]['w']=='pr-si'||$inparr[$i]['w']=='ed'
							||isset($verbs[$word['w']])
						){
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
			if(
				$inparr[$key-1]['w']=='have'
				||$inparr[$key-1]['w']=='be'
				||$dic[$inparr[$key-1]['w']]['type']=='verb'
				||isset($verbs[$inparr[$key-1]['w']])
			){
				//var_dump($inparr);
				echo'OK';show_trees($inparr);
				if(isset($inparr[0]['w'])&&($inparr[0]['w']=='whom'||$inparr[0]['w']=='that')){
					$whoword=array_splice($inparr,0,1);
					$whoword=$whoword[0];
					$key--;
				}
				$subject=array_splice($inparr,0,$key-1);//all before has or is
				if(count($subject)>1){//i have seen there should be >2 and will replace in other places <- this was mistake
					$subject=order_2($subject);
				}elseif(count($subject)==1){
					//i have seen that he is in array and fix
					$subject=$subject[0];
				}elseif(count($subject)==0){
					unset($subject);//comm.out, dont rem. what for is it
					//remove commenting, with 'is ... and has been ... ' example i see there is no subject in the block...
					//i have empty subject blocks and i want to remove them
				}
				//inparr is without subject now
				if(
					isset($inparr[count($inparr)-1][1]['w'])
					&&$inparr[count($inparr)-1][1]['w']=='and'
					&&$inparr[count($inparr)-1][0][1]['w']=='s'
				){
					//and block (should be and) is put in inparr element
					//need to remove 'and' block
					//echo'OK<pre>';print_r($inparr);exit;
					$andblock=array_splice($inparr,-1);
					order_2_if_needed($inparr);
					$andblock=$andblock[0];
					if(isset($subject)){
						$outparr[]=$subject;
						$outparr[]=array($andblock,$inparr);
					}else{
						$outparr[]=$andblock;
						$outparr[]=$inparr;
					}
					//goto return_outparr;//return $outparr;
					return true;
				}
				array_splice($inparr,1,1);//remove s
				//is a modern type of ..., and has been in use since ...
				//need to remove also second pr-si...
				foreach($inparr as $key2=>$word2){
					if(
						$word2['w']=='and'
						&&$inparr[$key2+1]['w']=='have'
						&&$inparr[$key2+2]['w']=='pr-si'
					){
						array_splice($inparr,$key2+2,1);//remove pr-si
						//break;
					}
				}
				// if(count($inparr)>1){
					// $inparr=order_2($inparr);
				// }
				order_2_if_needed($inparr);
				if(isset($whoword)){
					$outparr[]=array();//outparr0
					$outparr[0][]=$whoword;
					if(isset($subject)){
						$outparr[0][]=array();
						$outparr[0][1][]=$subject;
						$outparr[0][1][]=$inparr;
					}else{
						$outparr[0][]=$inparr;
					}
				}else{
					if(isset($subject)){
						$outparr[]=array();//outparr0
						$outparr[0][]=$subject;
						$outparr[0][]=$inparr;
					}else{
						$outparr[]=$inparr;
					}
				}
				$outparr[]=$word;//outparr1//s or ed or pr-si
				//goto return_outparr;//return $outparr;
				return true;
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
				goto return_outparr;//return $outparr;
			}else*/
			/*if($key==0){//have is 1st
				if($inparr[2]['w']=='ed-pp'){//have(0) do(1) ed(2)
					array_splice($inparr,0,1);//remove have
					if(count($inparr)>1){
						$inparr=order_2($inparr);
					}
					$outparr[]=$inparr;
					$outparr[]=$word['w'];
					goto return_outparr;//return $outparr;
				}
			}*//*else{
				if($key==1){//have in 2nd place
					$removedword=array_splice($inparr,0,1);//remove he
					if(count($inparr)>1){
						$inparr=order_2($inparr);
					}
					$outparr[]=$removedword[0];
					$outparr[]=$inparr;
					goto return_outparr;//return $outparr;
				}
			}*/
		}
	}
}


function try_be_prep(&$inparr,&$outparr){
	global $dic, $verbs;
	//be ...ed
	//be .... from ...
	//be ... that ...
	foreach($inparr as $key=>$word){
		if(
			$key==0
			&&$dic[$word['w']]['type']=='verb'
			&&$inparr[1]['w']!='ed-pp'
			&&$inparr[1]['w']!='er'
			&&$inparr[1]['w']!='s'
		){
			if($word['w']=='have'||$word['w']=='be'){
				if($inparr[2]['w']=='ed-pp'){//have(0) do(1) ed(2)
					array_splice($inparr,0,1);//remove have
					// if(count($inparr)>1){
						// $inparr=order_2($inparr);
					// }
					order_2_if_needed($inparr);
					$outparr[]=$inparr;
					$outparr[]=$word;
					//goto return_outparr;//return $outparr;
					return true;
				// }else{//try to order "be never ..." ... it has run too early
					// array_splice($inparr,0,1);//remove be
					// order_2_if_needed($inparr);
					// $outparr[]=$inparr;
					// $outparr[]=$word;
					// goto return_outparr;//return $outparr;
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
				//separate before preposition after some words after verb
				for($i=count($inparr)-1;$i>=0;$i--){
					if(
						isset($inparr[$i]['w'])
						&&(
							$inparr[$i]['w']=='every'
							||$inparr[$i]['w']=='to'
							&&$inparr[$i-1]['w']!='due'
							&&$inparr[$i-1]['w']!='similar'
							||$inparr[$i]['w']=='from'
							||$inparr[$i]['w']=='through'
							||$inparr[$i]['w']=='last'
							||$inparr[$i]['w']=='about'
							||$inparr[$i]['w']=='since'
							||$inparr[$i]['w']=='in'
							||$inparr[$i]['w']=='with'
						)
						&&$inparr[$i-1]['w']!=','
					){//preposition or adverb
					//adding ||$inparr[$i]['w']=='since' have not worked "correctly" - it has been separated too early
					//with 'has been in use since 2007'
					//as i see it should not have come here so early
					//i see why that happened.. &&$inparr[1]['w']!='s' upper helped
						$verb=array_splice($inparr,0,$i);
						//'built last year' is probably broken here, it should not come here
						//i have added &&$inparr[1]['w']!='ed-pp' in if condition (16 lines upper) and it is fixed
						/*if(count($verb)>1){
							$verb=order_2($verb);
							//"i go to school every day" is almost ordered now, need to (write code to) order "to school" and remove excessive array from "(go)"
						}else{
							$verb=$verb[0];
						}*/
						order_2_if_needed($verb);
						if(
							$inparr[0]['w']=='to'
							||$inparr[0]['w']=='from'
							||$inparr[0]['w']=='through'
							||$inparr[0]['w']=='about'
							||$inparr[0]['w']=='since'
							||$inparr[0]['w']=='in'
						){//preposition
						//swapping preposition with its word
							$prep=array_splice($inparr,0,1);
							$prep=$prep[0];
							/*if(count($inparr)>1){//inparr is now "school" or "good school"
								$inparr=order_2($inparr);
							}else{
								$inparr=$inparr[0];
							}*/
							order_2_if_needed($inparr);
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
						//goto return_outparr;//return $outparr;
						return true;
					}elseif(
						$inparr[$i]['w']=='to'
						&&(
							$inparr[$i-1]['w']=='due'
							||$inparr[$i-1]['w']=='similar'
						)
					){
						$verb=array_splice($inparr,0,$i-1);
						order_2_if_needed($verb);
						$outparr=array();
						/*
						$outparr[]=array();
						$outparr[]=$verb;
						$outparr[0][0]=array();
						$outparr[0][1]=$inparr[0];//due
						$outparr[0][0][1]=$inparr[1];//to
						array_splice($inparr,0,2);
						order_2_if_needed($inparr);
						$outparr[0][0][0]=$inparr;
						ksort($outparr[0][0]);
						goto return_outparr;//return $outparr;
						*/
						order_2_if_needed($inparr);
						$outparr[]=$inparr;
						$outparr[]=$verb;
						//goto return_outparr;
						return true;
					}elseif(
						(
							$inparr[$i]['w']=='every'
							||$inparr[$i]['w']=='to'
							||$inparr[$i]['w']=='from'
							||$inparr[$i]['w']=='through'
							||$inparr[$i]['w']=='last'
							||$inparr[$i]['w']=='about'
							||$inparr[$i]['w']=='since'
							||$inparr[$i]['w']=='in'
							||$inparr[$i]['w']=='with'
						)
						&&$inparr[$i-1]['w']==','
					){
						$there_is_comma_prep=true;
					}
				}
				//try to order "be never ..." ... it has run too early
				// array_splice($inparr,0,1);//remove be
				// order_2_if_needed($inparr);
				// $outparr[]=$inparr;
				// $outparr[]=$word;
				// goto return_outparr;//return $outparr;
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
				/*if(count($inparr)>1){
					$inparr=order_2($inparr);
				}else{
					$inparr=$inparr[0]['w'];
				}*/
				order_2_if_needed($inparr);
				/*if(count($thatsblock)>1){
					$thatsblock=order_2($thatsblock);
				}else{
					$thatsblock=$thatsblock[0];
				}*/
				order_2_if_needed($thatsblock);
				$outparr[]=$thatsblock;
				$outparr[]=$inparr;
				//return 0; // i see teacher whom ... has come here and have been broken. and it is (the 0) - it is not correct. i have added &&$inparr[1]['w']!='er' in condition and it does not come here and is fixed.
				//goto return_outparr;//return $outparr;
				return true;
				//get: {[(that is ...)(through park)] walk} - not correct... but that is not fail of this code.
			}
		}
	}
}


function try_first_prep(&$inparr,&$outparr){
	//global $dic, $verbs;
	//divide after preposition
	//from ...
	foreach($inparr as $key=>$word){
		if(
			$key==0
			&&isset($word['w'])
			&&(
				$word['w']=='the'
				||$word['w']=='through'
				||$word['w']=='from'
				||$word['w']=='for'
				||$word['w']=='in'
				||$word['w']=='of'
				||$word['w']=='with'
				||$word['w']=='to'
			)
		){
		//i have tried ||$word['w']=='a'||$word['w']=='an' here, it does not work as i expected
		//i have got {a [modern type of ...]} but i want {{a [modern type]} {of ...}}
		//no, when have removed this, i see "of" is already separated... indeed, it should be so, how it had come here before "of"? - at "a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface". i have set separation of "a" lower...
			$inparrtry=$inparr;
			array_splice($inparrtry,0,1);//remove the
			// if(count($inparrtry)>1){
				// $inparrtry=order_2($inparrtry);
			// }
			order_2_if_needed($inparrtry);
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
			//goto return_outparr;//return $outparr;
			return true;
			//he had read the last known bug
			//i see "last know ed bug", it can be {subject verb object}, but it is not "knowed", it is "known", for that i will replace ed to ed-pp (past participle). no. i replace it to en. no. en is used itself in texts, change back.
		}
	}
}

function try_whom(&$inparr,&$outparr){
	//global $dic, $verbs;
	// ... / that ...
	foreach($inparr as $key=>$word){
		if(isset($word['w'])&&($word['w']=='whom'||$word['w']=='that')&&$key>0){
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
				//example: +1who ha-1s read go+1es to home
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
			// if(count($inparrtry)>1){
				// $inparrtry=order_2($inparrtry);
			// }
			order_2_if_needed($inparrtry);
			// if(count($main)>1){
				// $main=order_2($main);
			// }elseif(count($main)==1){
				// $main=$main[0];
			// }
			order_2_if_needed($main);
			//and i get "Fatal error:  Allowed memory size of 134217728 bytes exhausted (tried to allocate 65488 bytes) in C:\xampp\htdocs\tarjima\index2.php on line 413" after writing the 2 "if" blocks above. try to unset a copy of array. has not helped. will edit php.ini... actually i add ini_set... tried 256M and it says: "Allowed memory size of 268435456 bytes exhausted (tried to allocate 24 bytes)". tried 512M and i see: "Allowed memory size of 536870912 bytes exhausted (tried to allocate 65488 bytes)". i think it goes into infinit recursion... adding the "&&$key>0" in "elseif" above have fixed this.
			$outparr[]=$inparrtry;
			$outparr[]=$main;
			//goto return_outparr;//return $outparr;
			return true;
		}
	}
}


function try_ed_noun(&$inparr,&$outparr){
	global $dic;
	//...ed / bug
	foreach($inparr as $key=>$word){
		if(isset($word['w'])&&isset($dic[$word['w']])&&$dic[$word['w']]['type']=='noun'&&$key==count($inparr)-1&&isset($inparr[$key-1]['w'])&&$inparr[$key-1]['w']=='ed-pp'){
			array_splice($inparr,$key,1);//remove noun
			if(count($inparr)>1){
				$inparr=order_2($inparr);
			}
			$outparr[]=$inparr;
			$outparr[]=$word;
			//goto return_outparr;//return $outparr;
			//i see {last know ed-pp}. {(last know) n} or {last (know n)}? it is (usually) the 1st one, but how to select with program? if it is {last (know n)}, it would be {last (known bug)}...
			return true;
		}
	}
}


function try_adv_verb_ed(&$inparr,&$outparr){
	//global $dic, $verbs;
	//last know / ed
	foreach($inparr as $key=>$word){
		if(isset($word['w'])&&$word['w']=='last'&&$key==0&&$inparr[1]['w']=='know'&&$inparr[2]['w']=='ed-pp'&&count($inparr)==3){
			array_splice($inparr,2,1);//remove ed-pp
			$outparr[]=$inparr;
			$outparr[]='ed-pp';
			//goto return_outparr;//return $outparr;
			return true;
		}
	}
}

function try_end_ordinal(&$inparr,&$outparr){
	//global $dic, $verbs;
	//... ... / 3 rd mix
	//see explode func line 159
	foreach($inparr as $key=>$word){
		if(isset($word[0]['ordnum'])&&$key=count($inparr)-1){
			for($i=0,$allwithcapital=true;$i<count($inparr)-1;$i++){
				if(!(isset($inparr[$i]['firstiscapital'])&&$inparr[$i]['firstiscapital']==true)){
					$allwithcapital=false;
					break;
				}
			}
			if($allwithcapital){
				$edition=array_splice($inparr,-1);
				// if(count($inparr)>1){
					// $inparr=order_2($inparr);
				// }
				order_2_if_needed($inparr);
				$outparr[]=$inparr;
				$outparr[]=$edition[0];
				$outparr['thisisheader']=true;
				//goto return_outparr;//return $outparr;
				return true;
			}
		}
	}
}

function try_prep_block(&$inparr,&$outparr){
	//global $dic, $verbs;
	//separate before preposition
	//... ... / for ... ...
	foreach($inparr as $key=>$word){
		if(
			$key>0
			&&isset($word['w'])
			&&(
				$word['w']=='for'
				||$word['w']=='of'
				||$word['w']=='with'
				//&&$inparr[$key-1]['w']!=','
				||($word['w']=='to'&&$inparr[$key-1]['w']!='due')
				// ||(//has not worked here, too late
					// $word['w']=='due'
					// &&isset($inparr[$key+1]['w'])
					// &&$inparr[$key+1]['w']=='to'
				// )
			)
		){
		//abbreviation for ..
			$main=array_splice($inparr,0,$key);
			// if(count($inparr)>1){
				// $inparr=order_2($inparr);
			// }
			order_2_if_needed($inparr);
			// if(count($main)>1){
				// $main=order_2($main);
			// }else{
				// $main=$main[0];
			// }
			order_2_if_needed($main);
			$outparr[]=$inparr;
			$outparr[]=$main;
			//goto return_outparr;//return $outparr;
			return true;
		}
	}
}

function try_first_be(&$inparr,&$outparr){
	//global $dic, $verbs;
	//separate after be
	//be / ... ...
	//see also line 790, 740
	if(isset($inparr[0]['w'])&&$inparr[0]['w']=='be'){
		//try to order "be never ..." ... done...
		$word=array_splice($inparr,0,1);//remove be
		order_2_if_needed($inparr);
		$outparr[]=$inparr;
		$outparr[]=$word[0];
		//$outparr[1]['test']='test';
		//goto return_outparr;//return $outparr;
		return true;
	}
}

function try_first_an(&$inparr,&$outparr){
	//global $dic, $verbs;
	foreach($inparr as $key=>$word){
		if(isset($word['w'])&&($word['w']=='a'||$word['w']=='an')&&$key==0){
		//an abbreviation
		//{{a [modern type]} {of ...}}
			$prepishere=false;
			foreach($inparr as $trypreposition){
				if($trypreposition['w']=='of'||$trypreposition['w']=='for'){
					$prepishere=true;
					break;
				}
			}
			if($prepishere==false){
				$an=array_splice($inparr,0,1);
				$an=$an[0];
				/*if(count($inparr)>1){
					$inparr=order_2($inparr);
				}else{
					$inparr=$inparr[0];
				}*/
				order_2_if_needed($inparr);
				$outparr[]=$an;
				$outparr[]=$inparr;
				//goto return_outparr;//return $outparr;
				return true;
			}
		}
		//elseif(isset($word['w'])&&isset($inparr[$key+1])&&$word['w']=='type'&&$inparr[$key+1]=='three'){
		//}
	}
}


function try_last_attr(&$inparr,&$outparr){
	global $nounlikes;
	if(isset($nounlikes[ $inparr[count($inparr)-1]['w'] ] )&& $nounlikes[ $inparr[count($inparr)-1]['w'] ]['type']=='attr' ){
		$last=array_splice($inparr,-1);
		order_2_if_needed($inparr);
		$outparr[]=$inparr;
		$outparr[]=$last[0];
		//goto return_outparr;
		return true;
	}
}

function try_first_attr(&$inparr,&$outparr){
	global $nounlikes;
	if(isset($nounlikes[$inparr[0]['w']])&&$nounlikes[$inparr[0]['w']]['type']=='attr'){
		$try=$inparr;
		array_splice($try,0,1);
		order_2_if_needed($try);
		if(count($try)==2){//temporary version of check of success
			$outparr[]=$inparr[0];
			$outparr[]=$try;
			//goto return_outparr;
			return true;
		}
	}
}


function try_and_block(&$inparr,&$outparr){
	//... ... ... / [,] and / ...
	//global $nounlikes;
	for($i=count($inparr)-1-1;$i>=0;$i--){
		$word=$inparr[$i];
		$key=$i;
		if(isset($word['w'])&&($word['w']=='and'||$word['w']==','||($word['w']=='nor'&&$i==1&&count($inparr)==3))){
			//echo'OK<pre>';print_r($inparr);exit;
			$andblock=array_splice($inparr,$key);
			$andsblock=array_splice($andblock,1);
			order_2_if_needed($andsblock);
			$andblock_new[0]=$andsblock;
			$andblock_new[1]=$andblock[0];
			//$and_is_just_ordered=true;
			if($inparr[count($inparr)-1]['w']==','){
				unset($inparr[count($inparr)-1]);
			}
			order_2_if_needed($inparr,array('and_is_just_ordered'=>true));
			$outparr[]=$andblock_new;
			$outparr[]=$inparr;
			//goto return_outparr;//return $outparr;
			return true;
		}
	}
}



//function order_2($inparr,$params){
function order_2($inparr){
	global $dic,$multiwords,$nounlikes,$verbs;
	global $recursionlevel;
	$recursionlevel++;
	//echo'in level '.$recursionlevel.'<pre>';print_r($inparr);echo'</pre>';echo'*';
	//if($recursionlevel==3){echo'in level '.$recursionlevel.'<pre>';print_r($inparr);echo'</pre>';}
	$outparr=array();
	if(try_last_plural($inparr,$outparr)){goto return_outparr;}
	if(try_last_dot($inparr,$outparr)){goto return_outparr;}
	if(are_all_nouns($inparr)){
		return order_all_nouns($inparr);
	}
	if(try_introduction($inparr,$outparr)){goto return_outparr;}
	try_comma_the($inparr,$outparr);
	//show_trees($inparr);
	if(try_order_that_block($inparr,$outparr)){goto return_outparr;}
	if(try_be_prep($inparr,$outparr)){goto return_outparr;}
	if(try_first_prep($inparr,$outparr)){goto return_outparr;}
	if(try_whom($inparr,$outparr)){goto return_outparr;}
	if(try_ed_noun($inparr,$outparr)){goto return_outparr;}
	if(try_adv_verb_ed($inparr,$outparr)){goto return_outparr;}
	if(try_end_ordinal($inparr,$outparr)){goto return_outparr;}
	$inparr=separate_a_mw($inparr);
	if(count($inparr)==2){
		$outparr=$inparr;
		goto return_outparr;
	}
	if(func_num_args()==2){
		$params=func_get_arg(1);
	}
	if(!isset($params['and_is_just_ordered'])){
		if(try_and_block($inparr,$outparr)){goto return_outparr;}
	}
	if(try_prep_block($inparr,$outparr)){goto return_outparr;}
	if(try_first_be($inparr,$outparr)){goto return_outparr;}
	if(isset($params['and_is_just_ordered'])){
		if(try_and_block($inparr,$outparr)){goto return_outparr;}
	}
	if(try_first_an($inparr,$outparr)){goto return_outparr;}
	if(try_last_attr($inparr,$outparr)){goto return_outparr;}
	if(try_first_attr($inparr,$outparr)){goto return_outparr;}
	//echo'out level '.$recursionlevel.'<pre>';print_r($inparr);echo'</pre>';
	$recursionlevel--;
	return $inparr;
	return_outparr:{
		//echo'out level '.$recursionlevel.'<pre>';print_r($inparr);echo'</pre>';
		$recursionlevel--;
		// $outparr[0]['up']=&$outparr;
		// $outparr[1]['up']=&$outparr;
		return $outparr;
	}
}







/*
for($i=count($inparr)-1;$i>=0;$i--){
	$word=$inparr[$i];
	$key=$i;
	if(isset($word['w'])&&($word['w']=='and')){
		$andblock=array_splice($inparr,$key);
		$andsblock=array_splice($andblock,1);
		order_2_if_needed($andsblock);
		$andblock_new[0]=$andsblock;
		$andblock_new[1]=$andblock[0];
		//$and_is_just_ordered=true;
		order_2_if_needed($inparr,array('and_is_just_ordered'=>true));
		$outparr[]=$andblock_new;
		$outparr[]=$inparr;
		goto return_outparr;//return $outparr;
	}
}
*/



















?>