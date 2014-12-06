<?php
//this file is to be included from/by index2-2.php

//next sentence is:
//DDR3 SDRAM is neither forward nor backward compatible with any earlier type of random access memory (RAM) due to different signaling voltages, timings, and other factors.
echo'<br/>';
$engtext='DDR3 SDRAM is neither forward nor backward compatible with any earlier type of random access memory (RAM) due to different signaling voltages, timings, and other factors.';
$verbs=array();
$verbs['signal']=array('tt'=>'белгертү');
$nounlikes['early']=array('tt'=>'иртә','type'=>'attr');
$nounlikes['voltage']=array('tt'=>'вольтаж','type'=>'noun');
$nounlikes['timing']=array('tt'=>'вакытланыш','type'=>'noun');
$nounlikes['factor']=array('tt'=>'фактор','type'=>'noun');
$engtext2=explode_into_morphemes($engtext);
echo'<pre>';
print_r($engtext2);
$recursionlevel=0;
$nounlikes['any']=array('tt'=>'теләсә-кайсы','type'=>'attr');
$engtext2=order_2($engtext2);
print_r($engtext2);
echo'</pre>';
$recursionlevel=0;
$nounlikes['DDR3']=array('tt'=>'DDR3','type'=>'noun');
$nounlikes['different']=array('tt'=>'башка төрле','type'=>'attr');
$nounlikes['neither']=array('tt'=>'түгел','type'=>'attr');
$result=tr_simple_block_2($engtext2);
echo'<pre>';
//print_r($result);
echo'</pre>';
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






























