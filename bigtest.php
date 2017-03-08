<?php
require_once('vendor/autoload.php');
use Converter\Number2Text\Number2Text;

$number = '100001010011101111000110'; //Must be always a string!
$convert = new Number2Text($number); //init Converter
echo $convert->num2txt(); // Prints just a number
