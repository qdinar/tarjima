<?php
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');


// echo preg_match_all('/[A-Z0-9]/u','AsaAAAssssssddddSSEFRFRG0',$matches);
// print_r($matches);
// echo '<br>';



// $arr=array();
// function &f1(&$input){
	// return $input;
// }
// function &f2(&$input){
	// $tmp=f1($input);
	// $tmp['ok']='ok';
	// return $input;
// }
// print_r( f2($arr) );//Array ( )


// function &f3(&$input){
	// $input['ok']='ok';
	// return $input;
// }
// print_r( f3($arr) );//Array ( [ok] => ok )


// $tmp=f1($arr);
// $tmp['ok2']='ok2';
// print_r( $arr );//still Array ( [ok] => ok )



// $arr=array();
// function &f1(&$input){
	// return $input;
// }
// $tmp=&f1($arr);
// $tmp['ok']='ok';
// print_r( $arr );


// $arr=array();
// $arr[0]=array();
// $arr[0]['up']=&$arr;
// print_r( $arr );
// echo '<br>';
// print_r( $arr[0] );
// echo '<br>';
// print_r( $arr[0]['up'] );


// preg_match('/([әеиөү])[бвгджҗзйклмнпрстфхцчшщ]*$/ui','төр',$lastvowel);
// echo'<pre>';
// print_r($lastvowel);

// $arr=array('a','b','c',array('da','db',array('dca','dcb'),'dd'),'e','f');
// $ref=$arr[3][2];
// $ref=&$arr[3][2];
// $ref[]='dcc';
// echo'<pre>';
// print_r($arr);
// echo'</pre>';


?>