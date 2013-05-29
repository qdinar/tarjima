<?php 
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', '1');

include 'words.php';

include 'functions.php';


//echo $_POST['input'] 
$input=htmlspecialchars( $_POST['input'] );

$translation=array();
$paragraphtranslation=array();
$input_divided_by_dot_and_newline=explode(".\r\n" , $input);
for($l=0;$l<count($input_divided_by_dot_and_newline);$l++){
	$input_divided_by_dot_and_newline[$l]=explode('.' , $input_divided_by_dot_and_newline[$l]);
	$translation[$l]=array();
	for($k=0;$k<count($input_divided_by_dot_and_newline[$l]);$k++){
		$translation[$l][$k]=translation_of_sentence($input_divided_by_dot_and_newline[$l][$k]);
	}
	$paragraphtranslation[$l]=implode('. ', $translation[$l]);
}

echo implode('.<br />',$paragraphtranslation);


/*
$input_divided_by_dot=explode('.' , $input);
$translation=array();
for($k=0;$k<count($input_divided_by_dot);$k++){
	$translation[$k]=translation_of_sentence($input_divided_by_dot[$k]);
}
echo implode('. ',$translation);
*/




  ?>

<form method="POST">

<textarea name="input" style="width:500px;height:150px;">i go to school every day. we go from park every morning.
they walk through park that is built last year.
the boy that bought a bycicle walks through park.
</textarea>

<input type="submit">
</form>
