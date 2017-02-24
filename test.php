<?php
declare(strict_types=1);

require_once 'TxtToNum_Converter.php';

$num = 90101101000621;
$strnum = ConvertToText($num);

echo mbUcfirst($strnum, "UTF-8", true);

function mbUcfirst($str, $encoding = "UTF-8", $lowerStrend = false)
{
    $firstLetter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
    $strEnd = null;
    if ($lowerStrend) {
        $strEnd = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
    } else {
        $strEnd = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
    }
    $str = $firstLetter . $strEnd;

    return $str;
}
