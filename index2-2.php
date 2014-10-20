<?php
//this file is to be included from/by index2.php
$mwdic=array();

//i want to translate these sentences, from https://en.wikipedia.org/wiki/DDR3_SDRAM :
//From Wikipedia, the free encyclopedia
//This article is about DDR3 SDRAM.
//For graphics DDR3, see GDDR3.
//For the video game, see Dance Dance Revolution 3rdMix.
//In computing, DDR3 SDRAM, an abbreviation for double data rate type three synchronous dynamic random access memory is a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface, and has been in use since 2007.
//It is the higher-speed successor to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips.
//DDR3 SDRAM is neither forward nor backward compatible with any earlier type of random access memory (RAM) due to different signaling voltages, timings, and other factors.
//DDR3 is a DRAM interface specification.
//The actual DRAM arrays that store the data are similar to earlier types, with similar performance.

echo'<br/>';
$engtext='From Wikipedia, the free encyclopedia';
$engtext=explode(' ', $engtext);
//$dic['buy']['type']='verb';
include 'function_explode_words_into_morphemes_2.php';
//$firstletterofsentenceiscapital=true;
$engtext2=explode_words_into_morphemes_2($engtext);
//i want to do something with commas and capital letters
print_r($engtext2);
echo'<pre>';
$dic['wikipedia']['type']='noun';
include 'function_order_2.php';
$engtext2=order_2($engtext2);
print_r($engtext2);
echo'</pre>';
$words['wikipedia']='википедия';
$words['free']='бушлай';
$words['encyclopedia']='энциклопедия';
$words[',']=',';
$words['the']='теге';
$words[',,']='булган';
include 'function_tr_simple_block_2.php';
$result=tr_simple_block_2($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';

function nstd_to_str_2($nstd){
	$result='';
	//global $firstletterofsentenceiscapital;
	global $nstd_to_str_2_firstwordisready;
	if(isset($nstd['dash'])){
		$result.=' — ';
	}
	if(isset($nstd['thisisheader'])||isset($nstd['inquotes'])){
		$result.='"';
	}
	if(isset($nstd[0][1]['w'])){
		if($nstd[0][1]['w']=='()'){
			//swap
			$parentheses=$nstd[0];
			$nstd[0]=$nstd[1];
			$nstd[1]=$parentheses;
		}elseif($nstd[0][1]['w']=='һәм'){
			$and=$nstd[0];//and's block
			$nstd[0]=$nstd[1];//main block
			$nstd[1]=$and;//swap
			$and=$nstd[1][1];
			//swapping the word 'and' with dependent block
			$nstd[1][1]=$nstd[1][0];
			$nstd[1][0]=$and;
			if(
				isset($nstd[1][1]['dash'])//possible dash of external and block , that is positioned later
				//&&isset($nstd[0][0][1]['w'])//possible 'and' of inner and block
				//&&$nstd[0][0][1]['w']=='and'
				//&&isset($nstd[0][0][0]['dash'])
				&&isset($nstd[0]['dash'])
			){
				//echo'*';
				unset($nstd[1][1]['dash']);//remove later dash
			}
		//}elseif($nstd[0][1]['w']==',,'){
		}
	}
	if(!isset($nstd[0]['w'])){
		$result.=nstd_to_str_2($nstd[0]);
	}else{
		$word=$nstd[0]['w'];
		if(isset($nstd[0]['firstiscapital'])&&$nstd[0]['firstiscapital']==true||!$nstd_to_str_2_firstwordisready){
			$word=mb_strtoupper(mb_substr($word,0,1)).mb_substr($word,1);
		}
		if(isset($nstd[1]['w'])&&$nstd[1]['w']=='е'&&$word=='тизлек'){
			$word='тизлег';
		}
		$result.=$word;
		$nstd_to_str_2_firstwordisready=true;
	}
	if(isset($nstd[1][1]['w'])){
		if($nstd[1][1]['w']=='()'){
			$nstd[1]=$nstd[1][0];
			$parentheses=true;
		}
	}
	if(!isset($nstd[1]['w'])){
		$result.=' ';
		if(isset($parentheses)){
			$result.='(';
		}
		$result.=nstd_to_str_2($nstd[1]);
	}else{
		$word=$nstd[1]['w'];
		if(isset($nstd[1]['firstiscapital'])&&$nstd[1]['firstiscapital']==true||!$nstd_to_str_2_firstwordisready){
			$word=mb_strtoupper(mb_substr($word,0,1)).mb_substr($word,1);
		}
		if(
			$word!='.'&&$word!='не'&&$word!='ны'&&$word!='е'&&$word!='гыз'
			&&$word!='дан'&&$word!='нче'&&$word!='енче'&&$word!='ы'&&$word!='лы'
			&&$word!='п'&&$word!='дән'
		){
			$result.=' ';
		}elseif($word=='п'){
			$main=get_main_word($nstd[0]);
			if($main['w']=='ал'){
				$result.='ы';
			}
		}
		if(isset($parentheses)){
			$result.='(';
		}
		$result.=$word;
		$nstd_to_str_2_firstwordisready=true;
	}
	if(isset($nstd['thisisheader'])||isset($nstd['inquotes'])){
		$result.='"';
	}
	if(isset($parentheses)){
		$result.=')';
		unset($parentheses);
	}
	return $result;
}

$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);

//i have made new versions of functions, (they are in separate php files,) working with arrays with several properties, among them string of word, instead of just strings of words.
//additional properties hold/store/save first letter capitalisation now
//i need to order comma...
//{from [wikipedia, the free encyclopedia]}
//no, i need to order "from" first ...
//then i should have {[(the {free encyclopedia}) wikipedia] from} ...
//{wikipedia, the free encyclopedia} can mean also {wikipedia and the free encyclopedia}...
//but after "from" it does not... usually
//after some editions i have
//тегебушлайэнциклопедиявикипедиядан
//it sounds like "that well known free encyclopedia" , like a little too proudly, for me , and i am not very sure that this is correct translation, i have asked about this in ##english in freenode, and as i have understood, this is what it means , or, it should have been "The Free Encyclopedia" - another name of Wikipedia, that is written in logo. anyway it means that.
//i have read about commas near a week ago , and i think, comma separated clause in this case means unimportant , additional information, explanation. i think that is ok in the tatar translation.
//one capital letter marker is lost; none is in translation; need to mark first letter of sentence;
//fixed. ТегебушлайэнциклопедияВикипедиядан

echo'<br/>';
$engtext='This article is about DDR3 SDRAM.';
$engtext=explode(' ', $engtext);
//$dic['buy']['type']='verb';
$engtext2=explode_words_into_morphemes_2($engtext);
print_r($engtext2);
echo'<pre>';
$dic['be']['type']='verb';
$engtext2=order_2($engtext2);
print_r($engtext2);
echo'</pre>';
$words['this']='бу';
$words['article']='мәкалә';
$words['about']='турында';
$words['DDR3']='DDR3';
$words['SDRAM']='SDRAM';
//$words['be']='be';//this was бул , set upper. fixing... бул is not correct. "бул" is "become"... though sometimes "be" is translated as "бул"... comment this out, do not change
$words['.']='.';
$result=tr_simple_block_2($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);
//i have БумәкаләDdr3Sdramтурындабула after some edition, it is almost ready...
//it should be БумәкаләDDR3SDRAMтурында.
//i have set dot as if it is a morphem, it is at "top" level. now dot appears in translation
//була is removed
//abbreviation captital letters are preserved/passed
//this example is done...


echo'<br/>';
$engtext='For graphics DDR3, see GDDR3.';
$engtext=explode(' ', $engtext);
//$dic['buy']['type']='verb';
$engtext2=explode_words_into_morphemes_2($engtext);
print_r($engtext2);
echo'<pre>';
$dic['see']['type']='verb';
$engtext2=order_2($engtext2);
print_r($engtext2);
echo'</pre>';
$words['for']='өчен';
$words['graphics']='графика';
$words['.']='.';
$words['see']='кара';
$words['GDDR3']='GDDR3';
$recursionlevel=0;
$result=tr_simple_block_2($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);
//after some editions: ГрафикаDDR3өченкараGDDR3.
//should be: ГрафикаDDR3етурындаGDDR3некара.
//or ГрафикаDDR3еөченGDDR3неач.
//or ГрафикаDDR3еөченGDDR3некара.
//and may be all imperatives should be translated in plural... now they are translated as singular...
//first of all, gddr3 and кара should be replaced... done.
//не is added. е is added. so, ГрафикаDDR3еөченGDDR3некара.
//i am going to make plural imperatives... done... ГрафикаDDR3еөченGDDR3некарагыз.
//and i have found a mistake... see is not кара, it is күр... look for/at is кара ... i have looked it in google translate and see it is both in english...
//i want to set spaces in result text, as in literary tatar language. i by myself am not agree with that spelling of spaces. done. Графика DDR3е өчен GDDR3не карагыз.

echo'<br/>';
$engtext='For the video game, see Dance Dance Revolution 3rdMix.';
$engtext=explode(' ', $engtext);
//$dic['buy']['type']='verb';
$engtext2=explode_words_into_morphemes_2($engtext);
//i have here redundant "pr-si" after "see", and 'w'=>'revolution' is absent
print_r($engtext2);
echo'<pre>';
$dic['dance']['type']='noun';
$dic['revolution']['type']='noun';
$multiwords=array(0=>array('random','access'));
$engtext2=order_2($engtext2);
print_r($engtext2);
echo'</pre>';
$words['video']='видео';
$words['game']='уен';
$words['dance']='бию';
$words['rd']='нче';
$words['mix']='болгатма';
$words['3']='3';
$words['revolution']='революция';
$nounlikes['dance']=array('tt'=>'бию','type'=>'noun');
$nounlikes['revolution']=array('tt'=>'революция','type'=>'noun');
$recursionlevel=0;
$result=tr_simple_block_2($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);
//need to order "Dance Dance Revolution 3rdMix"
//-> {{Dance Dance Revolution} {{3 rd} Mix}}
//is that {{Dance Dance} Revolution} or {Dance {Dance Revolution}}?
//seems it is the first.. and seems it is reduplicative, then it should have been written with hyphen, as such reduplicatives are usually spelled
//i am going to order it as the second. it is also meaningful for me now... it emphasizes that it is not actually a revolution, but something related to dance
//also the 1st "dance" could be imperative
//btw revolution can be separated as {{re volu} tion} (, i know this is not correct ... ), graphics in previous example as {{graph ic} s}...
//after some editions:
//Теге видео уен өчен Бию Бию Революция 3нче Болгату карагыз.
// - almost done... it can be left as this...
//more correct would be: Видеоуен турында "Бию бию революциясе 3нче болгатмасы"н карагыз.
//and more correct: Видеоуен турында "Dance Dance Revolution 3rdMix"ны карагыз.
//i have commented out code that passed capital letters into translation, it is code of copying array elements, it does not work properly if structure/dependency/tree is changed
//the problem is fixed. quotes are added. ны is added.
//Теге видео уен өчен "Бию Бию Революция 3нче Болгату"ны карагыз.
//i am going to left this as it is now, and start new example...

echo'<br/>';
$engtext='In computing, DDR3 SDRAM, an abbreviation for double data rate type three synchronous dynamic random access memory, is a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface, and has been in use since 2007.';
$dic['compute']['type']='verb';
include 'function_explode_into_morphemes.php';
$engtext2=explode_into_morphemes($engtext);
echo'<pre>';
print_r($engtext2);
$dic['double']['type']='noun';
$dic['data']['type']='noun';
$dic['rate']['type']='noun';
$dic['type']['type']='noun';
$dic['three']['type']='noun';
$dic['synchronous']['type']='noun';
$dic['dynamic']['type']='noun';
$dic['random']['type']='noun';
$dic['access']['type']='noun';
$dic['memory']['type']='noun';
$dic['DRAM']['type']='noun';
$dic['high']['type']='noun';
$dic['bandwidth']['type']='noun';
$dic['interface']['type']='noun';
$dic['DDR3']['type']='noun';
$dic['SDRAM']['type']='noun';
$engtext2=order_2($engtext2);
print_r($engtext2);
echo'</pre>';
$words['in']='эчендә';
$words['modern']='яңа';
$words['have']='have';
$words['compute']='хисапла';
$words['an']='бер';
$words['abbreviation']='аббревиатура';
$words['and']='һәм';
$words['ing']='у';
$words['double']='икекатлы';
$words['data']='мәгълүмат';
$words['high']='биек';
$words['with']='белән';
$words['dynamic']='динамик';
$words['random']='теләсәкайсы';
$words['of']='ның';
$words['since']='таналып';
$words['2007']='2007';
$words['use']='кулланылыш';
$words['bandwidth']='үткәрүчәнлек';
$words['type']='төр';
$words['three']='өч';
$words['rate']='тизлек';
$words['synchronous']='синхрон';
$words['access']='керү';
$words['memory']='хәтер';
$words['interface']='интерфейс';
$words['DRAM']='DRAM';
$words['()']='()';
$mwdic[]=array('en'=>array('compute','ing'),'tt'=>array('компьютер',array('гыйлем','е')));
$mwdic[]=array('en'=>array('random','access'),'tt'=>array('теләсә-ничек','керү'));
$mwdic[]=array('en'=>array('high','bandwidth'),'tt'=>array('югары','үткәрүчәнлек'));
$nounlikes=array();
$nounlikes['data']=array('tt'=>'мәгълүмат','type'=>'noun');
$nounlikes['dance']=array('tt'=>'бию','type'=>'noun');
$nounlikes['random']=array('tt'=>'мәгълүмат','type'=>'adj');
$nounlikes['high']=array('tt'=>'мәгълүмат','type'=>'adj');
$nounlikes['dynamic']=array('tt'=>'динамик','type'=>'adj');
$nounlikes['synchronous']=array('tt'=>'мәгълүмат','type'=>'adj');
$nounlikes['interface']=array('tt'=>'интерфейс','type'=>'noun');
$nounlikes['memory']=array('tt'=>'хәтер','type'=>'noun');
$nounlikes['bandwidth']=array('tt'=>'үткәрүчәнлек','type'=>'noun');
$nounlikes['access']=array('tt'=>'керү','type'=>'noun');
$nounlikes['rate']=array('tt'=>'тизлек','type'=>'noun');
$mwdic[]=array('en'=>array('<1>','since'),'tt'=>array(array(array('<1>','тан'),'ал'),'п'));
//in wiktionary:
//synchronous - adj
//dynamic - adj and noun
//high - adj, adv, noun, verb, and has 2 omonyms except that
//random - noun, adj
//seems, marking them just as adjective has some sense
$recursionlevel=0;
$result=tr_simple_block_2($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);
//i have some result without error messages after some edition
//i do not know, how much it is correct because the sentence is too long, and i have not thought about its structure...
//how this should be done:
//{[In computing], [ {[DDR3 SDRAM], [an abbreviation for double data rate type three synchronous dynamic random access memory]} {is [a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface], and [has been in use since 2007]} ] }.
//ie
/*
{
	[In computing]
	[
		{
			[DDR3 SDRAM]
			[an abbreviation for double data rate type three synchronous dynamic random access memory]
		}
		{
			[is a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface]
			and
			[has been in use since 2007]
		}
	]
}
*/
//there should be a comma between 'memory' and 'is'. i try to set it. (in $engtext).
//i think, how should i order "and", should there be 3 branches in a level? ie {A and B}?
//i think i will make it as {A {and B}}, and if they are more, {{A {and B}} and C}, same way with commas: {{A {, B}} {, C} }
//i am going to fix empty subjects before 'has been' and 'is' , that i have in result now, and this block of 2 verb blocks is already correctly separated from the subject "ddr3 sdram , ..., " and i do not know how. i will look that later...
//after some editing i have {a [modern type of ...]} , i think it is also logical at some level, but i think it is {{a [modern type]} {of ...}} and same problem at {an abbreviation for ...}. i think this have happened because i have added "or word==a/an " code after "word == the" code in condition. i have removed that code and see (notice) a little other(different) case: "a high bandwidth ("double data rate") interface".
//"a" is done, "and" is done, and code for preposition after noun is made
//now i need to order:
//double data rate type three synchronous dynamic random access memory
//dynamic random access memory (DRAM)
//high bandwidth ("double data rate") interface
//they should be:
//{ {[double (data rate)] (type three)} {synchronous {dynamic [(random access) memory]}} }
//{{dynamic [(random access) memory]} DRAM}
//{  [{high bandwidth} {double [data rate]}] interface  }
//there "type three", "random access", "double data rate type three", "double data rate", "high bandwidth" are joined/grouped earlier than usual flow... and i should handle/manage parentheses and quotes...
//may be i will use lexems in dic consisting of several words.
//i have ordered parts before and after "type three" with special code block for it
//what are differences of english from tatar in grammar of several nouns sitting one near other:
//"double data rate" - this is said as "double (rate of data)" in tatar
//"synchronous dynamic random access memory" - this is said as "synchronous ( dynamic (random-access-ful memory) )" in tatar
//ie noun-likes are just set one before other , only if they are just are more or less wide/distinct denotation of same thing
//ie if they can be said with "is" or "and" between them:
//something is double AND (data rate), (data rate) IS double
//but "rate" IS NOT "data", nothing is rate AND data "at same time".
//so we cannot say "data rate" in tatar.
//it is rate OF data
//but, in some dialects of tatar there is same way of using nouns as in english, as i know
//seems, tatar way of using 2 nouns is not in english, but only some words that are adjectives are used that way
//and also there are some nouns that r=are also adjectives: brick... - same in tatar ... materials...
//can any other noun be used that way in tatar and in english?
//seems that does not work with any nouns also in tatar...
//no.. seems they work both in english and in tatar : cave house - мәгарә өй ... but as i know that is (would be) spelled мәгарә-өй - i think that is not correct
//another example: editor program, text editor soft
//i am going to use a "semantic category" property - a number, to mark words with meaning of same type with same number. i have found/seen this method near a month ago in http://www.e-zerde.kz/conf/TurkLang.pdf "PROCEEDINGS Of the I International Conference on Computer processing of Turkic Languages (TurkLang-2013)" p. 274, pdf p. 270. i do not know whether i would have found this method by myself
//but then i should mark "program" and "editor" with same number...
//may be i should just use adjectives and nouns as in common tatar and english grammars...

//how can i order "random access" before "standart flow" now?
//1st way, i said about it, to set "random access" in dictionary, and check for it
//and i have found 2nd way:
//words are usually ordered so that they mean more and more narrow thing(meaning):
//big red book - not "red big book", nor "red book big", nor "book red big", nor "book big red"
//and so i think "big", "something big" means most many things, there are lot of big things,
//"red" means less things than "big", there are less red things in the world than big things
//"book" means less possible things than what "red" means, there are lot less "book"s than "red thing"s.
//same order of words is also in tatar and russian, and in arabic, but they have reverse order in arabic.
//ie it is "book red big" in arabic.
//so, i can set in dictionary some number, meaning how much things every word denotates (or, how much narrow meaning it has)
//then i should go in standart order oly if that number becomes less and less or larger and larger.
//synchronous dynamic ... - there are more synchronous things than dynamic things?? it is ok ...
//dynamic random ... i think , we would say "random synchronous dynamic ..." , "random" things are more than "synchronous" by this theory
//so, as soon as i get word with wider meaning in incorrect position, i should check whether it is in inner branch
//... random access ... - access is narrower than "random", i should check it...
// ... access memory ... - it is accessful memory, or memory with access ... so the number wich means wideness/narrowness of meaning does not work here ...
//i am going to make some temporary code just for this case ...
//if i think "random access" is ok , then again, (random access) memory - this is memory with (random access)
//i am going to just use "random access" as lexem in dictionary
//'random access memory' is ordered
//now i am going to make parentheses
//{dynamic random access memory ( DRAM )}
//to
//{DRAM {dynamic random access memory}}
//and
//high bandwidth ( " double data rate " ) interface
//to
//{ { [ " double data rate " ] [high bandwidth] } interface}
//parentheses are done for this case
//i have made quotes but quotes immediately in parentheses don't work and in my program...
//i am going to replace "inquotes" and "inparentheses" properties with "words" '""', "()" . no, i will make only parentheses as word... done, i have now:
//Хисапла у эчендә өч төр икекатлы мәгълүмат дәрәҗә синхрон динамик теләсәкайсы керү хәтер өчен бер аббревиатура DDR3 SDRAM 2007 таналып кулланылыш эчендә бул п куй а һәм бер икекатлы мәгълүмат дәрәҗә () биек агымкиңлеге интерфейс белән DRAM () динамик теләсәкайсы керү хәтер ның бер яңа төр бул а.
//trying to understand it:
//In computing, DDR3 SDRAM, an abbreviation for double data rate type three synchronous dynamic random access memory, is a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface, and has been in use since 2007.
//Хисаплау эчендә,
//[өч төр икекатлы мәгълүмат дәрәҗә синхрон динамик теләсәкайсы керү хәтер] өчен бер аббревиатура, DDR3 SDRAM
//2007 таналып кулланылыш эчендә бул п куй а һәм
//бер биек агымкиңлеге ("икекатлы мәгълүмат дәрәҗә") интерфейс белән динамик теләсәкайсы керү хәтер (DRAM) ның бер яңа төр бул а.
//have fixed quotes in output. have fixed parentheses in output. have fixed order of blocks at 2 sides of "and".
//now i have:
//Хисапла у эчендә өч төр икекатлы мәгълүмат дәрәҗә синхрон динамик теләсәкайсы керү хәтер өчен бер аббревиатура DDR3 SDRAM бер биек агымкиңлеге ("икекатлы мәгълүмат дәрәҗә") интерфейс белән динамик теләсәкайсы керү хәтер (DRAM) ның бер яңа төр бул а һәм 2007 таналып кулланылыш эчендә бул п куй а.
//need to add commas...
//this file is too large, 64 kbytes, i will divide it. done, this text is in index2-2.php now
//i have edited commas and have a "bug" with them now:
//second comma of " ,an abbreviation for ... , "
//is not treated as second comma of pair of commas, but treated same way as comma of "in computing,"
//and it works for the sentence, but just an redundant comma appears in translation...
//i need to do something with all commas, probably
//i try it:
//1st separation: 1st comma
//In computing,
//2nd: 2nd and 3rd commas...
//, an abbreviation for double data rate type three synchronous dynamic random access memory,
//3rd: 4th comma:
//, and has been in use since 2007
//DDR3 SDRAM is a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface.
//may be i should just set this blocks in array elements:
//(1) DDR3 SDRAM (2) is a modern type of dynamic random access memory (DRAM) with a high bandwidth ("double data rate") interface (3).
//and order this then... done
//i have fixed commas after some editing:
//Хисапла у эчендә , өч төр икекатлы мәгълүмат дәрәҗә синхрон динамик теләсәкайсы керү хәтер өчен бер аббревиатура булган DDR3 SDRAM бер биек агымкиңлеге ("икекатлы мәгълүмат дәрәҗә") интерфейс белән динамик теләсәкайсы керү хәтер (DRAM) ның бер яңа төр бул а һәм 2007 таналып кулланылыш эчендә бул п куй а.
//there were comma between SDRAM and бер, i have removed it. though it is more correct now, seems it is less readable... but i think i can add comma now if i want...
//what i need to edit:
// хисапла у -> компьютер гыйлеме - done
// компьютер гыйлеме эчендә -> компьютер гыйлемендә
// өч төр -> өченче төр (done) -> өченче төрдәге - done
// дәрәҗә -> тизлек - done
// мәгълүмат тизлек -> мәгълүмат тизлеге
// теләсәкайсы керү -> теләсә-ничек керү - done
// теләсә-ничек керү хәтер -> теләсә-ничек керү хәтере
// (өченче төрдәге ике катлы мәгълүмат тизлеге) (синхрон динамик теләсә-ничек керү хәтере)
// -> өченче төрдәге ике катлы мәгълүмат тизлеге ле синхрон динамик теләсә-ничек керү хәтере
// агымкиңлеге -> үткәрүчәнлек - done
// биек үткәрүчәнлек -> югары үткәрүчәнлек - done
// югары үткәрүчәнлек ("ике катлы мәгълүмат тизлеге") интерфейс
// -> югары үткәрүчәнлек ("ике катлы мәгълүмат тизлеге") ле интерфейс
// or -> югары үткәрүчәнлекле ("ике катлы мәгълүмат тизлекле") интерфейс
// ... интерфейс белән -> ... интерфейслы
// maybe ... хәтере (DRAM) ның -> ... хәтере (DRAM) нең
// бер яңа төр -> бер яңа төре
// delete бул а - done
// 2007 таналып -> 2007дән алып
// кулланылыш эчендә -> кулланылышта
// delete бул п куй а - done
function is_mw_eq($simbl,$mw,&$inner){
	if(is_array($mw[0])){
	}elseif($mw[0]=='<1>'){
		$inner=$simbl[0];
	}else{
		if(
			!(
				isset($simbl[0]['w'])&&$simbl[0]['w']==$mw[0]
			)
		){
			//$notequal=true;
			return false;
		}
	}
	if(is_array($mw[1])){
	}else{
		if(!(isset($simbl[1]['w'])&&$simbl[1]['w']==$mw[1])){
			//$notequal=true;
			return false;
		}
	}
	return true;
}
function assign_mw_tr(&$s2,$tr,$inner){
	if(is_array($tr[0])){
		$s2[0]=array();
		assign_mw_tr($s2[0],$tr[0],$inner);
	}elseif($tr[0]=='<1>'){
		//$s2[0]=array();
		$s2[0]=$inner;
	}else{
		$s2[0]=array('w'=>$tr[0]);
	}
	if(is_array($tr[1])){
		$s2[1]=array();
		assign_mw_tr($s2[1],$tr[1],$inner);
	}else{
		$s2[1]=array('w'=>$tr[1]);
	}
	apply_fixes_after_1_by_s2($s2);
}
//multi morphem lexem translation is started
//'computing' is translated special way now, before it is translated simply morphem-to-morphem ...
//өч төр -> өченче төр is done
//i have written some code about "type three", i do not commit it, i use now commits just for history, not for reusing that commits...
//теләсәкайсы керү -> теләсә-ничек керү is done
//биек үткәрүчәнлек -> югары үткәрүчәнлек is done
//(these are made with the new multi morphem dic instrument)
//бул а is deleted
//бул п куй а is deleted
//(these are made with special code)
//now i am going to make:
// мәгълүмат тизлек -> мәгълүмат тизлеге
// керү хәтер -> керү хәтере
// хәтеренең бер яңа төр -> хәтеренең бер яңа төре
// үткәрүчәнлек интерфейс does not become үткәрүчәнлек интерфейсы, but үткәрүчәнлекле интерфейс
// in english, something like: 
// data rate -> data, its rate
// ... access memory -> ... access, its memory
// ... memory 's a modern type -> ... memory 's , its a modern type
// ... bandwidth interface -> ... bandwidth -ful interface
//i do not know how to know out that it is not ... bandwidth, its interface
//also, if i do not get "random access" from multi-word dic, how would i know out that it is not
// random, its access
//?
//but access memory also can be done as access-ful memory
//"access, its memory" seems to me unlogical
//so, i get:
// in case хәтеренең ... төр it is easily known that е should be added, from presence of "ның".
// in case data rate seems it is feature of word "rate"
// in other cases use "-ful"
//also may be i should fix now previous example
//Теге видео уен өчен "Бию Бию Революция 3нче Болгату"ны карагыз.
//Бию Бию Революция -> Бию Бию Революциясе
//Болгату->Болгатма - done
//Бию Бию Революциясе 3нче Болгатма -> Бию Бию Революциясе 3нче Болгатмасы
//and,
// {(revolution of dance) of dance} could be
// {Бию (Бию Революциясен)е}
//but 2 "(с)е(н)" one after another are not used
//also "лы" and "сыз" suffixes are not used in modern tatar language after "(с)е(н)"
//i do not know, whether they are used in that place in other turkic languages
//and i want to edit
//Теге бушлай энциклопедия булган Википедиядан
//to
//Теге бушлай энциклопедия Википедиядан - done
//as it was. it has become with "булган" after i added that for DDR SDRAM , ... ,
//it is also same 2 commas as it is in wikipedia, ... - (though there is no last comma, just end of sentence)
//i am going to make different translation for "... , the ... ," and "... , an ... ," ...
//i am not sure whether that would be correct with other sentences
//dance revolution is not dance-ful revolution, but dance's revolution
//how to know that out automatically?
//also,
//... тизлеге ... хәтер -> ... тизлеге(н)ле ... хәтер
// ie ... rate ... memory -> ... rate -ful memory
//not rate's memory
//how to know that out automatically?
//so i get...
//data rate, dance revolution -> data's rate, dance's revolution
//randomaccess memory, highbandwidth interface, ddrate sdramemory -> randomaccess-ful memory, highbandwidth-ful interface, ddrate-ful sdramemory
//i see a rule now: multiwords are like adjectives with "ful" and one-words are "owners"
//(i am not sure whether that would be correct with other sentences)
//but i have found another example ... :
// double d.rate -> neither of the 2 : nor double's d.rate, nor double-ful d.rate ... it is just double d.rate...
//and there are lot of such cases:
// random access, high bandwidth, dynamic r.a.memory, synchronous d.r.a.memory
//i see a rule... multiword first word (describer) -> use -ful
//else: if first word is "adjective" -> do not change
// else -> first word is noun -> 's.
//but i will need to mark adjectives in dictionary...
//now i was going to use adjectives and nouns and adverbs together...
//btw in wiktionary: data is only noun, dance is verb and noun
//and i was going to use separate arrays for noun-likes and verbs... i think that is ok ... but will not i have problems while same word has noun and adverb and adjective variants? i do not know yet ...
function get_main_word($simbl){
	if(isset($simbl['w'])){
		return $simbl;
	}else
	if(isset($simbl[1]['w'])){
		return $simbl[1];
	}else{
		return get_main_word($simbl[1]);
	}
}
//after some fixes i have:
//Компьютер гыйлеме эчендә , өченче төрдәге икекатлы мәгълүмат тизлекылы синхрон динамик теләсә-ничек керүлы хәтер өчен бер аббревиатура булган DDR3 SDRAM бер югары үткәрүчәнлек ("икекатлы мәгълүмат тизлекы")лы интерфейс белән динамик теләсә-ничек керүлы хәтер (DRAM) ның бер яңа төры һәм 2007 таналып кулланылыш эчендә.
//i want to change:
//өченче төрдәге икекатлы мәгълүмат тизлекылы -> өченче төрдәге икекатлы мәгълүмат тизлекы лы
//may be i will just write all suffixes separately - done
//i had written suggestion to do so with ural-altaic languages in blogs and forums
//... интерфейс белән -> ... интерфейс лы - done
//now i have:
//Компьютер гыйлем е эчендә , өч енче төрдәге икекатлы мәгълүмат тизлек ы лы синхрон динамик теләсә-ничек керү лы хәтер өчен бер аббревиатура булган DDR3 SDRAM бер югары үткәрүчәнлек ("икекатлы мәгълүмат тизлек ы") лы интерфейс лы динамик теләсә-ничек керү лы хәтер (DRAM) ның бер яңа төр ы һәм 2007 таналып кулланылыш эчендә .
//i want to make these changes:
//add long "-" after ... DDR3 SDRAM (and before бер югары үткәрүчәнлек ...) - done
//make лы->ле ы->е where needed - done
function is_soft($s){
	if($s=='тизлек'||$s=='төр'||$s=='е'||$s=='керү'||$s=='үткәрүчәнлек'||$s=='хәтер'||$s=='2007'){
		return true;
	}else{
		return false;
	}
}
//after some fixes i have:
//Компьютер гыйлеме эчендә , өченче төрдәге икекатлы мәгълүмат тизлеке ле синхрон динамик теләсә-ничек керү ле хәтер өчен бер аббревиатура булган DDR3 SDRAM — бер югары үткәрүчәнлек ("икекатлы мәгълүмат тизлеке") ле интерфейс лы динамик теләсә-ничек керү ле хәтер (DRAM) нең бер яңа төр е һәм — 2007 таналып кулланылыш эчендә.
//(also a ның->нең is fixed)
//i should fix:
//тизлеке -> тизлеге - done
//2007 таналып -> 2007дән алып - done
//i should make one dash instead of two - done
//now i have:
//Компьютер гыйлеме эчендә , өченче төрдәге икекатлы мәгълүмат тизлеге ле синхрон динамик
//теләсә-ничек керү ле хәтер өчен бер аббревиатура булган DDR3 SDRAM
// — бер югары үткәрүчәнлек ("икекатлы мәгълүмат тизлеге") ле интерфейс лы
// динамик теләсә-ничек керү ле хәтер (DRAM) нең бер яңа төр е
// һәм 2007дән алып кулланылыш эчендә.



echo'<br/>';
$engtext='It is the higher-speed successor to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips.';
//$dic['compute']['type']='verb';
$nounlikes['high']=array('tt'=>'биек','type'=>'adjective');
$nounlikes['chip']=array('tt'=>'микросхема','type'=>'noun');
$engtext2=explode_into_morphemes($engtext);
echo'<pre>';
print_r($engtext2);
//$dic['double']['type']='noun';
$nounlikes['high']=array('tt'=>'биек','type'=>'noun');
$nounlikes['speed']=array('tt'=>'тизлек','type'=>'noun');
$nounlikes['er-comp']=array('tt'=>'рәк','type'=>'noun');
$nounlikes['successor']=array('tt'=>'соңрак килүче','type'=>'noun');
$nounlikes['DDR4']=array('tt'=>'DDR4','type'=>'noun');
$nounlikes['synchronous']=array('tt'=>'синхрон','type'=>'adj');
//$nounlikes['dynamic']=array('tt'=>'динамик','type'=>'adj');
$nounlikes['random']=array('tt'=>'теләсә-нинди','type'=>'noun');
$nounlikes['access']=array('tt'=>'керү','type'=>'noun');
$nounlikes['memory']=array('tt'=>'хәтер','type'=>'noun');
$nounlikes['SDRAM']=array('tt'=>'SDRAM','type'=>'noun');
$nounlikes['s-pl']=array('tt'=>'лар','type'=>'suf');
$engtext2=order_2($engtext2);
print_r($engtext2);
echo'</pre>';
//$words['-']='-';
//$mwdic[]=array('en'=>array('compute','ing'),'tt'=>array('компьютер',array('гыйлем','е')));
$nounlikes['it']=array('tt'=>'ул','type'=>'noun');
$nounlikes['-']=array('tt'=>'-','type'=>'hyphen');//it is not noun-like but i left it that way for now
$nounlikes['er-comp']=array('tt'=>'рәк','type'=>'nnp');//nnp - noun-noun-postfix - i have "created"/(thought out?) this term ... and i am writing it first time (i was going to write about that, postfix  types, in a tatar grammar "book" i am writing)
//$mwdic[]=array('en'=>array('<1>','since'),'tt'=>array(array(array('<1>','тан'),'ал'),'п'));
$nounlikes['it']=array('tt'=>'ул','type'=>'noun');
$nounlikes['DDR2']=array('tt'=>'DDR2','type'=>'noun');
$nounlikes['predecessor']=array('tt'=>'алдан килүче','type'=>'noun'); 
$nounlikes['DDR']=array('tt'=>'DDR','type'=>'noun');
$recursionlevel=0;
$result=tr_simple_block_2($engtext2);
echo'<pre>';
print_r($result);
echo'</pre>';
$nstd_to_str_2_firstwordisready=false;
echo nstd_to_str_2($result);
//need to "order":
//higher-speed successor to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips
//it should become:
//{{higher-speed successor to [DDR and DDR2]} and {predecessor to DDR4}} {synchronous dynamic random access memory (SDRAM) chips}
//or
//{higher-speed successor to [DDR and DDR2]} and {predecessor to {DDR4 synchronous dynamic random access memory (SDRAM) chips}}
//it would be ...
//It is the {[higher-speed successor] [to [{{DDR [synchronous dynamic random access memory]} (SDRAM)} chips]]}
//so it should be ...
// {{higher-speed successor to [[DDR and DDR2] ..<sdram chips>..]} and {predecessor to [DDR4 ..<sdram chips>..]}}
// { [ ..<ddr and ddr2 and ddr4>.. ( {synchronous dynamic random access memory}SDRAM ) ]  chips}
//how do/would i translate this into tatar? - :
//ddr белән ddr2 синхрон динамик теләсә-ничек керүле хәтер (sdram) микросхемаларына карата югарырак тизлекле соңрак килүче һәм ddr4 енә карата алдан килүче.
//i do not understand , why is here "the" article, and i am asking about it in ##english in freenode
//i have got answer that it means that there is only one such predecessor and successor and that answer is enough for me, i also thought/assumed it that way
//or, i can translate this way:
//синхрон динамик теләсә-ничек керүле хәтер (sdram) микросхемаларының ddr белән ddr2 сенә карата югарырак тизлекле соңрак килүче һәм ddr4 енә карата алдан килүче.
//if i make it just the 2nd way it will be translated:
//ddr белән ddr2 гә карата югарырак тизлекле соңрак килүче һәм ddr4 синхрон динамик теләсә-ничек керүле хәтер (sdram) микросхемаларына карата алдан килүче.
//it would be also acceptable, but a little strange
//
// {  {higher-speed successor to [[DDR and DDR2] ..<sdram chips>..]} and {predecessor to [DDR4 ..<sdram chips>..]}  }
// { [ ..<ddr and ddr2 and ddr4>.. ( {synchronous dynamic random access memory}SDRAM ) ]  chips}
//this in tatar is:
// {  {[[ddr белән ddr2] ..<сдрам микросхемалары>..] гә карата югарырак тизлекле соңрак килүче} һәм {[ddr4 ..<сдрам микросхемалары>..] кә карата алдан килүче}  }
// {  ..<ddr, ddr2, dd4>..  ( {синхрон динамик теләсә-ничек керүле хәтер} sdram ) микросхемалары }
//and by the way how my program could know out that this is strange structure here
//2014-06-12 i have come here after several months
//i think this this way now:
//(higher-speed successor to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips)
//higher-speed successor to (DDR ...) and (DDR2 ...) and predecessor to (DDR4 ...)    (... synchronous dynamic random access memory (SDRAM) chips)
//higher-speed successor to (DDR ..pointer..) and (DDR2 ..pointer..) and predecessor to (DDR4 > >>points to here<< synchronous dynamic random access memory (SDRAM) chips< )
//so, first, i should start to make it the second way
//so, i need to catch the second "and" now.
//
//how can my program know out that it is not this:
//
//higher-speed successor to DDR
//and
//DDR2
//and
//predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips
//
//and not this:
//
//higher-speed successor to DDR
//and
//(DDR2 and predecessor) to DDR4 synchronous dynamic random access memory (SDRAM) chips
//
//? the first, 2 "and"s, is rare and says ddr3 is ddr2, it is strange...
//the second uses "ddr2" like "predecessor", it is strange...
//i can find strangeness if i count phrase usage in texts...
//i have started to look for "and" from end
//now i have incorrect
//{{higher-speed successor to DDR} {and DDR2}} {and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips}
//how my program should know out whether it is
//{higher-speed successor to DDR} {and DDR2}
//or
//{higher-speed successor} {to DDR and DDR2}
// the first is part of " a and b and c " and it is strange ...
//after simply adding "to" now i have incorrect
//{higher-speed successor} {[[[to DDR] [and DDR2]] [and predecessor]] [to DDR4 synchronous dynamic random access memory (SDRAM) chips]}
//how should i choose , what to catch earlier, set at "upper" level: the first "to" or the second "and"
//if i try the first "to" i will get
//[higher-speed successor] [to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips]
//then
//to [[DDR and DDR2 and predecessor] to DDR4 synchronous dynamic random access memory (SDRAM) chips]
//to [DDR and DDR2 and [predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips]]
//to [[DDR and DDR2 and [predecessor to DDR4]] synchronous dynamic random access memory (SDRAM) chips]
//to [DDR and [DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips]]
//seems they all can be rejected because of a and b and c construction
//intermediate result after adding "to" to other place:
//higher-speed successor [to [(DDR and DDR2 and predecessor) (to (DDR4 synchronous dynamic random access memory (SDRAM) chips))]]
//i am going to identify "DDR and DDR2 and predecessor" earlier...
//then, whether it is
//{higher-speed successor to DDR} and {DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips}
//or
//{higher-speed successor to DDR and DDR2} and {predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips}
//?
//i am going to just move checking for last "and" earlier. that is temporary solution.
//and intermediate result is /   and i get:
//[{{It [be [the ((higher-speed successor) (to DDR))]] *s} and DDR2} {and {predecessor {to {DDR4 synchronous dynamic random access memory (SDRAM) chips}}}}].
//check for and is too early. moved it and get:
//[[It [be (the (higher-speed successor to DDR and DDR2 and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips))]] *s].
// - that part/level is ok. inner is:
// (((higher-speed successor) (to DDR)) and DDR2) (and predecessor to DDR4 synchronous dynamic random access memory (SDRAM) chips)
//so second "and" is ok but first "and" needs to be "ordered" later than "to"
//(higher-speed successor (to (DDR and DDR2))) (and predecessor to (DDR4 synchronous dynamic random access memory (SDRAM) chips))
//this part is done!
//i need "gosub" to do not double code, but there is no such thing, and i am searching other solution. i have already tried "function", but that was unsuccessful.
//i have an other idea now : using "include". - i have tried it now , and it also does not work.
//link to the my code that has example of php code that needs gosub: https://github.com/qdinar/tarjima/commit/e78da41329aae6b2e733722a2c0049300378222d
//see lines 775 and 810 (only the foreach block between that 2 blocks). (code is doubled) . i have tried to use function (&$inparr , &$outparr ) but that was unsuccessful.
//and i have one more idea now : setting subroutine code into a variable and "eval"ing it - i have tried it , it does not work.
//also i know from php.net/goto that using "goto" with variable label in "eval" won't work
//now i need to do:
//higher-speed successor
//predecessor
//DDR4 synchronous dynamic random access memory (SDRAM) chips
//DDR
//DDR2
//first, i am going to separate the "or" suffixes and the "s" suffix.
//no, i will not make "or", because both "success" and "predecess" do not work as grammatical for that
//"chips" is done. next:
//high er - speed successor -> (((high er) speed) successor)
//temporary  : (high (er (speed successor) ))   -  it separated from left to right, the "high" at first.
//(((high er) speed) successor) is done (for now)
//temp. result for DD4 ...:
// { [DDR4 [synchronous [dynamic ((random access) memory)]]] (SDRAM) } chips
// - "sdram" is "ordered" incorrectly
//("ordering" is setting priorities of words, in other words)
//how should my program know out that "(sdram)" refers to "synchronous dynamic random access memory" ?
//currently it simply takes all words before "(".
//"ddr4" is set now before parentheses ...
//now i have problem i said in line 585
//there is "random acces memory of dynamic" in tatar. i should fix it. fixed.
//also synchronous, SDRAM, plural s.
//now i have redundant ны. fixed. almost ready! :
//Ул — теге DDR һәм DDR2 кә биек рәк тизлек ле соңрак килүче һәм DDR4 синхрон динамик теләсә-ничек керү ле хәтер (SDRAM) микросхема лар кә алдан килүче.
// i am going to continue development closed source , commiting in github for windows without syncing with github.com . because i think it will be hard for me to earn money with donations or with crowd funding because i think that things are not popular in tatar community. and main idea of the translating method is already published if any body wants ... but i will not struggle hard to hold my closed source codes safe because it is like impossible ... i will look whether i can earn money with this by translating and selling this program usage license.
//i have not made yet anything . i do not want to make closed source program. 2014-oct-17 now. i think what license to use
//this is "closed source" branch
//i have written by mistake here. i will merge this branch to master for now.
//i have change my mind again. i will try to develop some time with "all rights reserved" and will not sync this branch , if god wills
//here i will write comments about development. begin
//end. ^there should be comments about development























