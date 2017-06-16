<?php
declare(strict_types = 1);
// Since we want to profile all the code, include Profiler class before vendor/autoload.php.
require 'class/Profiler.php';
use Converter\Profiler\Profiler;
$profiler = new Profiler();
//Start the Profiler
$profiler->Start();

//Autoload dependencies
require_once('vendor/autoload.php');
use Converter\Core\Number2Text;
use Converter\Generator\Generator;

//Generate random numbers and print results of conversion
for ($i = 1; $i <= 5; $i++) {
    $instGen = new Generator();
    $source = $instGen->generate();
    $number = new Number2Text($source);
    $number->withCurrency();

    echo 'Input Number: ' . $source . '<br>';
    echo 'Converted string: ' . mb_strtoupper($number->convert());
    echo '<br>';

    $power = $instGen->exponent;
    $base = $instGen->mantissa;
    $base_check = strlen((string)($base / 10));

    if ($base_check == 1) {
        $base = $base /10;
        $power += 1;
    }
    echo 'Exponential form: ' . $base;
    if ($base != 0) {
            echo  '•10';
            echo "<span style='position: relative; bottom: 1ex; font-size: 70%;'>" . $power . "</span>";
    }
    echo '<br><br>';
}

//Stop Profiler and show total execution time
$profiler->Stop();
