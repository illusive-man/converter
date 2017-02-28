<?php

declare(strict_types = 1); // New tet with pushed commit

function convertToText(float $inputNumber): string
{
    if ($inputNumber < 0 || $inputNumber > 99999999999999) {
        return $message = 'Error: Input number should be between 0 and 99\'999\'999\'999\'999';
    }

    if ($inputNumber == 0) {  //avoiding all calculations to display such a simple result
        return $message = 'ноль рублей';
    }

    $result = num2txt($inputNumber);

    return $result;
}

function num2txt(float $numberConvert): string
{
    $allArrays = getArrays();
    list($arrUnits, $arrTens, $arrHundreds, $arrMagnitude) = $allArrays;

    $arrChunks = getChunks($numberConvert);
    $numGroups = count($arrChunks);
    $fullResult = null;

    for ($i = $numGroups; $i >= 1; $i--) {
        $currChunk = $arrChunks[$i - 1];
        $arrUnits = fixArray($i, $arrUnits);

        $preResult = null;
        $centis = intval($currChunk / 100);
        $decimals = $currChunk - $centis * 100;

        if ($centis > 0) {
            $preResult .= $arrHundreds[$centis - 1];
        }
        if ($decimals > 0 && $decimals < 20) {
            $preResult .= $arrUnits[$decimals - 1];
            $decimals = 0;
        }
        if ($decimals != 0) {
            $preResult .= $arrTens[intval($decimals / 10) - 1];
        }
        if ($decimals % 10 != 0) {
            $preResult .= $arrUnits[$decimals % 10 - 1];
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

    //    if ($gnum == 1) { //If only number needed, without currency word(s)
    //        return "";
    //    }

    if ($nls > 1 && $nxs >= 11 && $nxs <= 14) {
        return $subResult = $group[$gnum][2];
    }

    $condition = substr($number, -1);
    switch ($condition) {
        case 1:
            $subResult = $group[$gnum][0];
            break;
        case 2:
        case 3:
        case 4:
            $subResult = $group[$gnum][1];
            break;
        default:
            $subResult = $group[$gnum][2];
            break;
    }

    return $subResult;
}

function getChunks(float $inputNumber): array
{
    $arrCh = array();
    $reversedValue = strrev(strval($inputNumber));
    $reversedSize = strlen($reversedValue);

    for ($i = 0; $i < $reversedSize; $i += 3) {
        $arrCh[] = strrev(substr($reversedValue, $i, 3));
    }

    return $arrCh;
}

function fixArray(int $fem, array $arr): array
{
    if ($fem == 2) {
        $arr[0] = 'одна ';
        $arr[1] = 'две ';
    } else {
        $arr[0] = 'один ';
        $arr[1] = 'два ';
    }

    return $arr;
}

function getArrays(): array
{
    $data = file_get_contents('data.json'); //Unescaped UTF-8
    $allArrays = json_decode($data, true, 4);

    return $allArrays;
}
