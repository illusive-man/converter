<?php

function convertToText(float $numberToConvert): string
{
    if ($numberToConvert < 0 || $numberToConvert > 99999999999999) {
        return $message = "Error: Input number should be between 1 and 99'999'999'999'999";
    }

    if ($numberToConvert == 0) {
        return $message = "ноль рублей";
    }

    $result = num2txt($numberToConvert);

    return $result;
}

function num2txt(float $numberToConvert): string
{
    $allArrays = getArrays();
    $arrUnits = $allArrays[0];
    $arrTens = $allArrays[1];
    $arrHundreds = $allArrays[2];
    $arrMagnitude = $allArrays[3];

    $arrChunks = getChunks($numberToConvert);
    $numGroups = count($arrChunks) - 1;
    $fullResult = null;

    for ($i = $numGroups; $i >= 1; $i--) {
        $preResult = null;
        $currChunk = $arrChunks[$i];
        $numLen = strlen($currChunk);

        $arrUnits = fixArray($i, $arrUnits);

        $centis = intval(substr($currChunk, 0, 1));
        if ($numLen == 3 && $centis != 0) {
            $preResult .= $arrHundreds[$centis];
        }

        $decimals = intval(substr($currChunk, -2));
        if ($decimals > 0 && $decimals <= 19) {
            $preResult .= $arrUnits[$decimals];
        } else {
            $preResult .= $arrTens[substr($currChunk, -2, 1)];
            $preResult .= $arrUnits[substr($currChunk, -1)];
        }

        if ($currChunk != 0 || $i == 1) {
            $preResult .= getMagnitude($arrMagnitude, $i, $currChunk);
        }

        $fullResult .= $preResult;
    }

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

function getChunks($inputNumber): array
{

    $arrCh = array();
    $arrCh[] = 0;
    $reversedValue = strrev($inputNumber);
    $reversedSize = strlen($reversedValue);

    for ($i = 0; $i < $reversedSize; $i += 3) {
        $arrCh[] = strrev(substr($reversedValue, $i, 3));
    }

    return $arrCh;
}

function fixArray(int $fem, array $arr): array
{
    if ($fem == 2) {
        $arr[1] = "одна ";
        $arr[2] = "две ";
    } else {
        $arr[1] = "один ";
        $arr[2] = "два ";
    }

    return $arr;
}

function getArrays(): array
{
    //@formatter:off
    $arrMagnitude = array(
                            array(0,"копейка ", "копейки ", "копеек "), //future functionality
                            array(0,"рубль ", "рубля ", "рублей "),
                            array(0,"тысяча ", "тысячи ", "тысяч "),
                            array(0,"миллион ", "миллиона ", "миллионов "),
                            array(0,"миллиард ", "миллиарда ", "миллиардов "),
                            array(0,"триллион ", "триллиона ", "триллионов "),
                            array(0,"квадриллион ", "квадриллиона ", "квадриллионов "),
                            array(0,"квинтиллион ", "квинтиллиона ", "квинтиллионов ")
                    );
    $arrUnits = array("","один ","два ","три ","четыре ","пять ","шесть ","семь ", "восемь ", "девять ",
                    "десять ","одиннадцать ","двенадцать ","тринадцать ","четырнадцать ","пятнадцать ",
                    "шестнадцать ","семнадцать ","восемнадцать ","девятнадцать ");
    $arrTens = array("","десять ","двадцать ","тридцать ","сорок ","пятьдесят ","шестьдесят ","семьдесят ",
                    "восемьдесят ","девяносто ");
    $arrHundreds = array("","сто ","двести ","триста ","четыреста ","пятьсот ","шестьсот ","семьсот ",
                    "восемьсот ","девятьсот ");
    //@formatter:on

    return array($arrUnits, $arrTens, $arrHundreds, $arrMagnitude);
}
