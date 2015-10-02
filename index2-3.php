<?php
//this file is to be included from/by index2-2.php

//next sentence is:
//DDR3 SDRAM is neither forward nor backward compatible with any earlier type of random access memory (RAM) due to different signaling voltages, timings, and other factors.
echo'<br/>';
$engtext='DDR3 SDRAM is neither forward nor backward compatible with any earlier type of random access memory (RAM) due to different signaling voltages, timings, and other factors.';
$verbs['signal']=array('tt'=>'ишарәлә');
$nounlikes['early']=array('tt'=>'иртә','type'=>'adj');
$nounlikes['voltage']=array('tt'=>'вольтаж','type'=>'noun');
$nounlikes['timing']=array('tt'=>'вакытланыш','type'=>'noun');
$nounlikes['factor']=array('tt'=>'фактор','type'=>'noun');
$engtext2=explode_into_morphemes($engtext);
show_trees($engtext2);
$recursionlevel=0;
$nounlikes['any']=array('tt'=>'теләсә-кайсы','type'=>'adj');
$nounlikes['RAM']=array('tt'=>'RAM','type'=>'noun');
$nounlikes['other']=array('tt'=>'башка','type'=>'adj');
$nounlikes['different']=array('tt'=>'төрле','type'=>'adj');//башка төрле
$multiwords[]=array('signal','ing');
$nounlikes['neither']=array('tt'=>'түгел','type'=>'adj');
$nounlikes['compatible']=array('tt'=>'ярашучы','type'=>'adj');
$nounlikes['due']=array('tt'=>'күрә','type'=>'noun');
$engtext2=order_2($engtext2);
show_trees($engtext2);
$recursionlevel=0;
$nounlikes['backward']=array('tt'=>'артка таба','type'=>'adv');
$nounlikes['forward']=array('tt'=>'алга таба','type'=>'adv');
$result=tr_simple_block_2($engtext2);
show_trees($result);
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);
//i have now:
// {
	// {
		// DDR3 SDRAM
		// {
			// {
				// be neither forward nor backward compatible
				// with
					// {
						// any
						// {
							// early er
						// }
						// type
					// }
					// of
						// random access memory ( RAM ) due
			// }
			// to
				// different signal ing voltage s , timing s
					// and
					// other factor s
		// }
	// }
	// s
// }
// .
//it should be:
// {
	// be:{
		// DDR3 SDRAM
		// be:{
			// be:{
				// with
					// {
						// any
							// {
								// early er
							// }
							// type
					// }
					// of
						// random access memory ( RAM )
				// be:{
					// be
					// neither forward nor backward compatible
				// }
			// }
			// due to
				// different signal ing voltage s , timing s
					// and
					// other factor s
		// }
	// }
	// s
// }
// .
//has taken "be" out of "be neither..."
//has made "due to"
//i should not write work times at both branches , i am not sure that i can easily merge my branches now
//(i have) fixed "any earlier type"
//i have merged master branch into this branch
//made "random access memory ( RAM )"
//i have "other (factor s)" and need to change it to "(other factor) s" -- done
//need to order:
//neither forward nor backward compatible
//different signal ing voltage s , timing s {and {{other factor} s}}
//they shoulde be:
//{neither {forward {nor backward}}} compatible
// {
	// different
		// {
			// signal ing
			// {
				// voltage s
				// {
					// ,
					// timing s
				// }
			// }
		// }
	// and
		// other factor
		// s
// }
//"different signaling voltages, timings, and other factors" is done temporarily.
//"neither forward nor backward compatible" is done
//tr. "due"; fixed "s-pl" tr-n;
//almost ready!
//DDR3 SDRAM төрле белгерт у вольтажлар , вакытланышлар һәм башка факторларкә күрә теләсә-ничек керүле хәтер (RAM) нең теләсә-кайсы иртә рәк төр е белән түгел алга һәм түгел артка яраучы бул а.
//i am writing comments for open source branch in work_time.txt now.
//need to fix "түгел алга һәм түгел артка". should be ...
//need to fix "түгел алга һәм түгел артка яраучы" , it should be:
//алга таба да артка таба да яраучы түгел
// 0 {
	// 0 арткатаба да
	// 1 алгатаба да
// }
// 1 яраучы түгел
//from
// 0 {
	// 0 neither
	// 1 {
		// 0 {
			// 0 backward
			// 1 nor
		// }
		// 1 forward
	// }
// }
// 1 compatible
//done
//i think i probably should remove all morphem fonetic changes from translate function and set them into nstd function, "nstd" means "from nested to string".
//removed from translate function
//i am writing almost all comments in work time txt now



echo'<br/>';
$engtext='DDR3 is a DRAM interface specification.';
$engtext2=explode_into_morphemes($engtext);
show_trees($engtext2);
$nounlikes['specification']=array('tt'=>'спецификация','type'=>'noun');
$multiwords[]=array('DRAM','interface');
$recursionlevel=0;
$engtext2=order_2($engtext2);
show_trees($engtext2);
$nounlikes['DRAM']=array('tt'=>'DRAM','type'=>'noun');
$recursionlevel=0;
$result=tr_simple_block_2($engtext2);
show_trees($result);
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);


//The actual DRAM arrays that store the data are similar to earlier types, with similar performance.
echo'<br/>';
$engtext='The actual DRAM arrays that store the data are similar to earlier types, with similar performance.';
$nounlikes['array']=array('tt'=>'массив','type'=>'noun');
$verbs['store']=array('tt'=>'сакла');
$engtext2=explode_into_morphemes($engtext);
show_trees($engtext2);
$nounlikes['actual']=array('tt'=>'асыл','type'=>'adj');
$recursionlevel=0;
$engtext2=order_2($engtext2);
show_trees($engtext2);
$nounlikes['similar']=array('tt'=>'охшаш','type'=>'adj');
$nounlikes['performance']=array('tt'=>'җитештерүчәнлек','type'=>'noun');
$recursionlevel=0;
$result=tr_simple_block_2($engtext2);
show_trees($result);
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);







//=== new order function ===


echo'<br><br>=== new order function ===';
//$show_trees_3=true;
//$show_trees_3_tr=true;
//$show_invis_chars=true;//this is for a discontinued new function
include 'explode_3.php';
include 'order_3.php';
//=== new translate and nested-to-string functions ===
// include 'function_tr_simple_block_3.php';
// include 'nstd_to_str_3.php';

//The actual DRAM arrays that store the data are similar to earlier types, with similar performance.
sh_trees_and_result($engtext);

$suf_prep['from']=array('tt'=>'тан');
$suf_prep['with']=array('tt'=>'белән');
$suf_prep['v-0']=array('tt'=>'элар');
$suf_prep['v-re']=array('tt'=>'элар');
$suf_prep['that']=array('tt'=>'теге');
$suf_prep['s-pl']=array('tt'=>'лар');
$suf_prep['to']=array('tt'=>'кә');
$suf_prep[',,']=array('tt'=>'булган');
$nounlikes['the']=array('tt'=>'шул');
$nounlikes['free']=array('tt'=>'бушлай');
$nounlikes['encyclopedia']=array('tt'=>'энциклопедия');
$nounlikes['wikipedia']=array('tt'=>'википедия');
$verbs['be']=array('tt'=>'бул');
// tr_simple_block_3($engtext2);
// show_tree_3($engtext2);
// echo s_tr_to_str_3($engtext2);

//prev. ex. with new order function
$engtext='DDR3 is a DRAM interface specification.';
sh_trees_and_result($engtext);

$engtext='DDR3 SDRAM is neither forward nor backward compatible with any earlier type of random access memory (RAM) due to different signaling voltages, timings, and other factors.';
//$engtext='be neither forward nor backward compatible with any earlier type of random access memory (RAM)';
sh_trees_and_result($engtext);
//show_tree_3(order_a_sentence_3(explode_3('different signaling voltages, timings, and other factors')));
//exit;

$multiwords[]=array('DDR4','SDRAM');
$engtext='It is the higher-speed successor to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips.';
sh_trees_and_result($engtext);

//$multiwords[]=array('double','data','rate');
$engtext='In computing, DDR3 SDRAM, an abbreviation for double data rate type three synchronous dynamic random access memory, is a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface, and has been in use since 2007.';
//$engtext='DDR3 SDRAM, an abbreviation for double data rate type three synchronous dynamic random access memory, is a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface, and has been in use since 2007.';
//$engtext='DDR3 SDRAM is a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface, and has been in use since 2007.';
sh_trees_and_result($engtext);
/*
echo '<br><br>',$engtext;
$engtext2=explode_3($engtext);
//show_tree_3($engtext2);
//try{
$engtext2=order_a_sentence_3($engtext2);
// }catch(Exception $e){
// show_tree_3($engtext2);
// was just " Fatal error: Allowed memory size of 134217728 bytes exhausted ...". and not printed also here in the catch
// }
show_tree_3($engtext2);
$result=tr_simple_block_2($engtext2);
show_tree_3_tr($result);
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);
*/

$verbs['see']=array('tt'=>'кара');
$engtext='For the video game, see Dance Dance Revolution 3rdMix.';
sh_trees_and_result($engtext);

$nounlikes['GDDR3']=array('tt'=>'GDDR3','type'=>'noun');
$engtext='For graphics DDR3, see GDDR3.';
sh_trees_and_result($engtext);

$engtext='This article is about DDR3 SDRAM.';
sh_trees_and_result($engtext);

$engtext='From Wikipedia, the free encyclopedia';
sh_trees_and_result($engtext);
/*
echo '<br><br>',$engtext;
$engtext2=explode_3($engtext);
// show_tree_3($engtext2);
$engtext2=order_a_sentence_3($engtext2);
show_tree_3($engtext2);
$result=tr_simple_block_2($engtext2);
show_tree_3_tr($result);
echo s_nstd_to_str_3($result);
*/

function s_nstd_to_str_3($nstd){
	global $nstd_to_str_2_firstwordisready,$firstletterofsentenceissmall;
	$nstd_to_str_2_firstwordisready=false;
	if($nstd['firstletterofsentenceissmall']){
		$firstletterofsentenceissmall=true;
		unset($nstd['firstletterofsentenceissmall']);
	}
	return nstd_to_str_2($nstd);
}

// tr_simple_block_3($engtext2);
// show_tree_3($engtext2);
// echo s_tr_to_str_3($engtext2);

$engtext='good school';
sh_trees_and_result($engtext);

function sh_trees_and_result($engtext){
	global $firstletterofsentenceissmall;
	echo '<br><br>',$engtext;
	$engtext2=explode_3($engtext);
	show_tree_3($engtext2);
	/*
	if($engtext2['firstletterofsentenceissmall']){
		$firstletterofsentenceissmall=true;
		//unset($engtext2['firstletterofsentenceissmall']);
	}
	*/
	$engtext2=order_a_sentence_3($engtext2);
	show_tree_3($engtext2);
	$result=tr_simple_block_2($engtext2);
	show_tree_3_tr($result);
	echo '<br>',s_nstd_to_str_3($result);
}

$nounlikes['last']=array('tt'=>'соңгы','type'=>'adj');
$verbs['know']=array('tt'=>'бел');
$verbs['read']=array('tt'=>'укы');
$verbs['mention']=array('tt'=>'искә ал');
$verbs['meet']=array('tt'=>'очрат');

// $engtext='last know';
// sh_trees_and_result($engtext);

// $engtext='last known';
// sh_trees_and_result($engtext);

// $engtext='last known bug';
// sh_trees_and_result($engtext);

$engtext='read last known bug';
sh_trees_and_result($engtext);

$engtext='read the last known bug';
sh_trees_and_result($engtext);

// $engtext='have read';
// sh_trees_and_result($engtext);

$engtext='he has read the last known bug';
sh_trees_and_result($engtext);

$engtext='the teacher whom we have met has read the bug that was mentioned';
//$engtext='be mentioned';
//$engtext='whom we have met';
//$engtext='teacher whom we have met';
sh_trees_and_result($engtext);

//$show_trees_3=true;
//$show_trees_3_tr=true;

$nounlikes['every']=array('tt'=>'һәр','type'=>'adv');
$engtext='i go to school every day';
sh_trees_and_result($engtext);

$engtext='we go from park every morning';
sh_trees_and_result($engtext);

$engtext='they walk through park that is built last year';
sh_trees_and_result($engtext);

//$show_trees_3_tr=true;
$engtext='the boy that bought a bicycle walks through park';
sh_trees_and_result($engtext);

$engtext='last year';
sh_trees_and_result($engtext);

$engtext='they walk through park';
sh_trees_and_result($engtext);

//$show_trees_3_tr=true;
$engtext='they walked through park last year';
sh_trees_and_result($engtext);
















