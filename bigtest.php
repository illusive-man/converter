<?php
require_once('vendor/autoload.php');
use PHP\Math\BigInteger\BigInteger as BigInteger;
use Converter\Number2Text\Number2Text;

$number = '15554783124983211984';
$bigint = new BigInteger($number); //Make a BigInt number object
$convert = new Number2Text($bigint); //init Converter
$textResult = $convert->showCurrency(true)->num2txt(); //Set option and print
echo $textResult;
