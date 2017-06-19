<?php
declare(strict_types = 1);
// Since we want to profile all the code, include Profiler class before vendor/autoload.php.
require_once 'class/Profiler.php';
use Converter\Profiler\Profiler;
$profiler = new Profiler();
//Start the Profiler
$profiler->Start();

//Autoload dependencies
require_once('vendor/autoload.php');
use Converter\Core\Number2Text;
use Converter\Generator\Generator;

//Generate random numbers and print results of conversion
//$instGen = new Generator();
//$source = $instGen->generate(false);
$source = '974580005510369432002001222020215165416516206516516206505749846541631650650124588745510369432002001222
        020215165416516206516516206505749846541631651065012458874551036943124325987941651620651651620650574984654163165
        000515054855106501245887455103694320020012220202151654165162065165162065057498465416316510650';
$number = new Number2Text($source);
//$number->withCurrency();

//$power = $instGen->exponent;
//$base = $instGen->mantissa;
////$base_check = strlen((string)($base / 10));
//
//if ($base_check == 1) {
//    $base = $base /10;
//    $power += 1;
//}

echo '<strong>Input Number: </strong>' . $source . '<br>';
//echo '<strong>Exponential form: </strong>' . $base;
//if ($base != 0) {
//    echo  '•10';
//    echo "<span style='position: relative; bottom: 1ex; font-size: 70%;'>" . $power . "</span>";
//}
echo '<br>';
echo '<strong>Converted string: </strong>' . $number->convert();
echo '<br><br>';

//Stop Profiler and show total execution time
$profiler->Stop();
