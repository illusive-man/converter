<?php
declare(strict_types = 1);
require_once('vendor/autoload.php');
require_once('converter.php');
use PHP\Math\BigInteger\BigInteger;

$number = new BigInteger('996468496518749010099'); //Make a number
$convert = new Converter\Number2Text($number); //init
echo $convert->showCurrency(false)->printNumber(); //Set option and print



//TODO: использовть ->showCurrency('RUB')
//TODO: убрать из массива слова, кроме единичного (миллион) и добавлять окончания 'a' и 'ов' в коде.
//TODO: добавить все оставшиеся числительные для больших чисел и массив валют.
//TODO: удалить файлы процедурной функции и теста.
//TODO: сделать инсталляцию illusive-man/converter с помощью composer + require phpmath/biginteger.
//TODO: протестировать с помощью phpUnit.
