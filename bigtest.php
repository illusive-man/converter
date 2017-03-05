<?php
declare(strict_types = 1);
require_once('vendor/autoload.php');
require_once('converter.php');
use PHP\Math\BigInteger\BigInteger;

$number = '98498494694';
$bigint = new BigInteger($number); //Make a BigInt number object
$convert = new Converter\Number2Text($bigint); //init Converter
$textValue = $convert->showCurrency(true)->printNumber(); //Set option and print
echo $textValue;

//TODO: удалить файлы процедурной функции и теста из git.
//TODO: сделать инсталляцию illusive-man/converter с помощью composer + require phpmath/biginteger
//TODO: и не забыть удалить соотв. файлы из .gitignore
//TODO: протестировать с помощью phpUnit.
