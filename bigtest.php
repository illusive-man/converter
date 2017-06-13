<?php
declare(strict_types = 1);
require 'class/Profiler.php';
use Converter\Profiler\Profiler;
$profiler = new Profiler();
$profiler->Start();

require_once('vendor/autoload.php');
use Converter\Number2Text\Number2Text;

for ($i = 1; $i <= 1; $i++) {
    $source = '-0';//Number2Text::makeBignumber();
    $number = new Number2Text($source);
    $number->withCurrency();
    echo $source . "<br>";
    echo $number->convert();
    echo '<br><br>';
}

$profiler->Stop();
