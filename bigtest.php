<?php
declare(strict_types = 1);
require_once('vendor/autoload.php');
use Converter\Number2Text\Number2Text;

for ($i = 1; $i <= 1; $i++) {
    $number = '11010';//Number2Text::makeBignumber();
    $convert = new Number2Text($number);
    echo $number;
    echo '<br>';
    $convert->withCurrency(true);
    echo $convert->num2txt();
    echo '<br><br>';
}

echo 'GenNumber: ' . Number2Text::makeBignumber(10, false);
    echo '<br><br>';
