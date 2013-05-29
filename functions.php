<?php


function translation_of_sentence($one_sentence){

//preg_match_all('/\r\n/',$one_sentence,$new_line_positions,PREG_OFFSET_CAPTURE );
// пока моны ясап бетерә алмыйм 2013март6

/*
echo '<pre>';
var_dump($new_line_positions);
echo '</pre>';
*/

//$one_sentence=preg_replace('/\r\n/','  ',$one_sentence);
$one_sentence=preg_replace('/\r\n/','',$one_sentence);
/*
  кирәк булмаган ...
for($i=0;$i<count($new_line_positions[0]);$i++){
$output=substr($output,0,$new_line_positions[0][$i][1])."\r\n".substr($output,$new_line_positions[0][$i][1]);
}
*/

global $prepositions, $adverbs, $tatar;
$input_divided_by_space=explode(' ' , $one_sentence);
$blocks=array();
$add_to_block=false;


for($i=0;$i<count($input_divided_by_space);$i++){
if ($input_divided_by_space[$i]=='')continue;
echo $input_divided_by_space[$i];
echo '-';
if (in_array($input_divided_by_space[$i],$prepositions)){
echo 'prep';
if(isset($block))$blocks[]=$block;
$block=array();
$block[]=$input_divided_by_space[$i];
$add_to_block=true;
}elseif(in_array($input_divided_by_space[$i],$adverbs)){
echo 'adverb';
if(isset($block))$blocks[]=$block;
$block=array();
$block[]=$input_divided_by_space[$i];
$add_to_block=true;
}else{

if($add_to_block){
$block[]=$input_divided_by_space[$i];
}else{
$blocks[]=$input_divided_by_space[$i];
}

}
echo ';';
}//for
if(isset($block))$blocks[]=$block;
/*
echo '<pre>';
var_dump($blocks);
echo '</pre>';
*/

echo '<br>';




$blocks=array_reverse($blocks);
for($i=0;$i<count($blocks);$i++){
if(is_array($blocks[$i])){
$block=array_reverse($blocks[$i]);
$blocks[$i]=$block;
}
}
/*
echo '<pre>';
var_dump($blocks);
echo '</pre>';
*/







for($i=0;$i<count($blocks);$i++){
if(is_array($blocks[$i])){
for($j=0;$j<count($blocks[$i]);$j++){
$blocks[$i][$j]=$tatar[$blocks[$i][$j]];
}
}else{
//echo '^'.$blocks[$i].'^';
$blocks[$i]=$tatar[$blocks[$i]];
}
}
/*
echo '<pre>';
var_dump($blocks);
echo '</pre>';
*/




/*
$output='';
for($i=0;$i<count($blocks);$i++){
if(is_array($blocks[$i])){
for($j=0;$j<count($blocks[$i]);$j++){
$output.=$blocks[$i][$j];
}
$output.=' ';
}else{
$output.=$blocks[$i];
$output.=' ';
}
}
*/


for($i=0;$i<count($blocks);$i++){
if(is_array($blocks[$i])){
$blocks[$i]=implode(' ', $blocks[$i]);
}
}
$output=implode(' ', $blocks);






return $output;







}//translation_of_sentence





