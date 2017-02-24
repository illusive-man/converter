<?php
declare(strict_types=1);

if (!file_exists('data.json')) {
    exit('ERROR: No data file found. Please upload data.json in the same dir with this one.');
}

require_once 'TxtToNum_Converter.php';
$num = 0;

if (isset($_GET['number'])) {
    $data = floatval($_GET['number']);

    if ($data >= 0) {
        $num = $data;
        $strnum = ConvertToText($num);
        echo $num, ' : ', mbUcfirst($strnum, "UTF-8", true), '<br>';
    }
}

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

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Testing Converter</title>
</head>
<body>
<form action="test.php" method="get">
    <input type="text" name="number" value="<?php echo $num; ?>" title="Enter number: ">
    <input type="submit" value="Submit">
</form>
</body>
</html>
