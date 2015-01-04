<?php
//this file is to be included from/by index2-2.php

//next sentence is:
//DDR3 SDRAM is neither forward nor backward compatible with any earlier type of random access memory (RAM) due to different signaling voltages, timings, and other factors.
echo'<br/>';
$engtext='DDR3 SDRAM is neither forward nor backward compatible with any earlier type of random access memory (RAM) due to different signaling voltages, timings, and other factors.';
$verbs['signal']=array('tt'=>'ишарәлә');
$nounlikes['early']=array('tt'=>'иртә','type'=>'attr');
$nounlikes['voltage']=array('tt'=>'вольтаж','type'=>'noun');
$nounlikes['timing']=array('tt'=>'вакытланыш','type'=>'noun');
$nounlikes['factor']=array('tt'=>'фактор','type'=>'noun');
$engtext2=explode_into_morphemes($engtext);
show_trees($engtext2);
$recursionlevel=0;
$nounlikes['any']=array('tt'=>'теләсә-кайсы','type'=>'attr');
$nounlikes['RAM']=array('tt'=>'RAM','type'=>'noun');
$nounlikes['other']=array('tt'=>'башка','type'=>'attr');
$nounlikes['different']=array('tt'=>'төрле','type'=>'attr');//башка төрле
$multiwords[]=array('signal','ing');
$nounlikes['neither']=array('tt'=>'түгел','type'=>'attr');
$nounlikes['compatible']=array('tt'=>'ярашучы','type'=>'attr');
$nounlikes['due']=array('tt'=>'күрә','type'=>'noun');
$engtext2=order_2($engtext2);
show_trees($engtext2);
$recursionlevel=0;
$nounlikes['backward']=array('tt'=>'артка таба','type'=>'noun');
$nounlikes['forward']=array('tt'=>'алга таба','type'=>'noun');
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
$nounlikes['actual']=array('tt'=>'чын','type'=>'adj');
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


include 'explode_3.php';
$engtext2=explode_3($engtext);
include 'order_3.php';
$engtext2=order_a_sentence_3($engtext2);
show_trees($engtext2);












































