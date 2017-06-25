<?php
declare(strict_types = 1);
namespace Bigtest;

// Since we want to profile all the code, include Profiler class before vendor/autoload.php.
require_once 'class/Profiler.php';
use Converter\Tools\Profiler;
$profiler = new Profiler();
//Start the Profiler
$profiler->Start();

//Autoload dependencies
require_once('vendor/autoload.php');
use Converter\Core\Number2Text;
use Converter\Demo\Generator;

//Generate random numbers and print results of conversion
$zerofill = false;
$source = Generator::generate(null, null, false, false);
$number = new Number2Text($source);
$number->currency(true);

$power = Generator::$exponent;
$base = Generator::$mantissa;
$base_check = strlen((string)($base / 10));

if ($base_check == 1) {
    $base = $base / 10;
    $power += 1;
}

echo '<strong>Input Number: </strong>' . $source . '<br><br>';
echo '<strong>Exponential form: </strong>';
if ($base != 0) {
    echo $zerofill === true ? $base . '•10' : 'RND•e';
    echo Generator::$sign == '-' ? '-' : '+';
    echo "<span style='position: relative; bottom: 1ex; font-size: 70%;'>" . $power . "</span>";
}
echo '<br><br>';
echo '<strong>Converted string: </strong>' . mb_strtoupper($number->convert());
echo '<br><br>';

//Stop Profiler and show total execution time
$profiler->Stop();
