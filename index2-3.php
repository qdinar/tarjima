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
$engtext2=order_2($engtext2);
print_r($engtext2);
echo'</pre>';
$recursionlevel=0;
$nounlikes['DDR3']=array('tt'=>'DDR3','type'=>'noun');
$nounlikes['different']=array('tt'=>'башка төрле','type'=>'attr');
$nounlikes['any']=array('tt'=>'теләсә-кайсы','type'=>'attr');
$nounlikes['neither']=array('tt'=>'түгел','type'=>'attr');
$result=tr_simple_block_2($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);



































