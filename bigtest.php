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
$zerofill = false;
$instGen = new Generator();
$source = $instGen->generate($zerofill);
$number = new Number2Text($source);
//$number->withCurrency();

$power = $instGen->exponent;
$base = $instGen->mantissa;
$base_check = strlen((string)($base / 10));

if ($base_check == 1) {
    $base = $base /10;
    $power += 1;
}

echo '<strong>Input Number: </strong>' . $source . '<br><br>';
echo '<strong>Exponential form: </strong>';
if ($base != 0) {
    echo $zerofill == true ? $base : 'RND';
    echo $zerofill == true ? '•10' : '•e';
    echo $instGen->Sign == '-' ? '-' : '+';
    echo "<span style='position: relative; bottom: 1ex; font-size: 70%;'>" . $power . "</span>";
}
echo '<br><br>';
echo '<strong>Converted string: </strong>' . mb_strtoupper($number->convert());
echo '<br><br>';

//Stop Profiler and show total execution time
$profiler->Stop();
