<?php

function convertToText(float $numberToConvert): string
{
    $errMessage = null;

    if ($numberToConvert < 0 || $numberToConvert > 99999999999999) {
        return $errMessage = "Error: Input number should be between 1 and 99'999'999'999'999";
    }

    $allArrays = getArrays();
    $arrUnits = $allArrays[0];
    $arrTens = $allArrays[1];
    $arrHundreds = $allArrays[2];
    $arrMagnitude = $allArrays[3];

    $arrChunks = getChunks($numberToConvert);
    $numGroups = count($arrChunks) - 1;
    $fullResult = null;

    for ($i = $numGroups; $i >= 1; $i--) { // Main big cycle

        if ($i == 2) {
            $arrUnits[1] = "одна ";
            $arrUnits[2] = "две ";
        } else {
            $arrUnits[1] = "один ";
            $arrUnits[2] = "два ";
        }

        $resArray = array();
        $preResult = null;
        $numLen = strlen($arrChunks[$i]);

        $centis = null;
        if ($numLen == 3) {
            $centis = intval(substr($arrChunks[$i], 0, 1));
        }

        if ($centis != 0) {
            $preResult .= $arrHundreds[$centis];
        }

        $decimals = intval(substr($arrChunks[$i], -2));

        if ($decimals >= 0 && $decimals < 21) {
            $preResult .= $arrUnits[$decimals];
        } elseif ($decimals != 0) {
            $preResult .= $arrTens[intval(substr($arrChunks[$i], -2, 1))];

            if (intval(substr($decimals, -1)) != 0) {
                $preResult .= $arrUnits[intval(substr($arrChunks[$i], -1))];
            }
        }

        if ($arrChunks[$i] != '000') {
            $preResult .= getMagnitude($arrMagnitude, $i, $arrChunks[$i]);
        } elseif ($arrChunks[$i] == '000' && $i == 1) {
            $preResult .= getMagnitude($arrMagnitude, $i, $arrChunks[$i]);
        }

        $resArray[$i] = $preResult;
        $fullResult .= $resArray[$i];
    }

    displayResult($fullResult);
    return $fullResult;
}

function getMagnitude(array $group, int $gnum, string $number): string
{

    $subResult = null;
    $nls = strlen($number);
    $nxs = substr($number, -2);

    if ($nls > 1 && $nxs >= 11 && $nxs <= 14) {
        return $subResult = $group[$gnum][3];
    }

    switch (substr($number, -1)) {
        case 1:
            $subResult = $group[$gnum][1];
            break;
        case 2:
        case 3:
        case 4:
            $subResult = $group[$gnum][2];
            break;
        default:
            $subResult = $group[$gnum][3];
            break;
    }

    return $subResult;
}

function getChunks($inputNumber)
{

    $arrCh = array(); // TODO: Convert all chunks to string type from int.
    $arrCh[] = 0;
    $reversedValue = strrev($inputNumber);
    $reversedSize = strlen($reversedValue);

    for ($i = 0; $i < $reversedSize; $i += 3) {
        $arrCh[] = strrev(substr($reversedValue, $i, 3));
    }

    return $arrCh;
}

function getArrays()
{

    //@formatter:off
    $arrUnits =
    ["ноль ","один ","два ","три ","четыре ","пять ","шесть ","семь ", "восемь ", "девять ",
     "десять ","одиннадцать ","двенадцать ","тринадцать ","четырнадцать ","пятнадцать ","шестнадцать ",
     "семнадцать ","восемнадцать ","девятнадцать ","двадцать "
    ];

    $arrTens =
    ["empty","десять ","двадцать ","тридцать ","сорок ","пятьдесят ","шестьдесят ","семьдесят ",
     "восемьдесят ","девяносто "
    ];

    $arrHundreds =
    ["empty","сто ","двести ","триста ","четыреста ","пятьсот ","шестьсот ","семьсот ",
     "восемьсот ","девятьсот "
    ];

    $arrMagnitude =
        array(
                array(0, "копейка ", "копейки ", "копеек "), //future functionality
                array(0, "рубль ", "рубля ", "рублей "),
                array(0, "тысяча ", "тысячи ", "тысяч "),
                array(0, "миллион ", "миллиона ", "миллионов "),
                array(0, "миллиард ", "миллиарда ", "миллиардов "),
                array(0, "триллион ", "триллиона ", "триллионов ")
        );
    //@formatter:on

    return array($arrUnits, $arrTens, $arrHundreds, $arrMagnitude);

}

function displayResult(string $resText){

    print ($resText . "<br>");

}

$num = 100000000;
$result = ConvertToText($num);
