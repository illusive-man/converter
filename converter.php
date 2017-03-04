<?php
declare(strict_types = 1);

namespace Converter;

/**
 * Converts numbers to their text representation e.g. 12 -> twelve (Russian only at the moment)
 *
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 *
 * @package   Converter
 * @require   PHP 7.x+
 */



class Number2Text
{
    public $curr;

    /**
     * Checks the input number, calls main converter function and returns result
     *
     * @param float $iNumber - number to convert
     *
     * @return string - text result
     */
    public function typeNumber(float $iNumber)
    {
        if ($iNumber < 0 || $iNumber > 99999999999999) {
            return $message = 'Error: Input number should be between 0 and 99\'999\'999\'999\'999';
        }

        if ($iNumber == 0) {  //avoiding all calculations to display such a simple result
            return $message = 'ноль рублей';
        }

        $result = $this->num2txt($iNumber);

        return $result;
    }

    /**
     * Deconstruct, transform and convert number to its text representation
     *
     * @param float $iNumber
     *
     * @return string
     */
    private function num2txt(float $iNumber): string
    {
        $message = null;
        $allArrays = $this->getArrays();
        if ($allArrays[0] == 'ERROR:') {
            return $message = $allArrays[0] . $allArrays[1];
        }
        list($arrUnits, $arrTens, $arrHundreds, $arrMagnitude) = $allArrays;

        $arrChunks = $this->getChunks($iNumber);
        $numGroups = count($arrChunks);
        $fullResult = null;

        for ($i = $numGroups; $i >= 1; $i--) {
            $currChunk = $arrChunks[$i - 1];
            $arrUnits = $this->fixArray($i, $arrUnits);

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
                $preResult .= $this->getMagnitude($arrMagnitude, $i, $currChunk);
            }

            $fullResult .= $preResult;
        }

        return $fullResult;
    }

    /**
     * Load array data from JSON file
     *
     * @return array
     */
    private function getArrays(): array
    {
        $jsonFile = __DIR__ . '/data.json';
        if (file_exists($jsonFile) && is_file($jsonFile)) {
            $data = file_get_contents($jsonFile);
            $allArrays = json_decode($data, true);
        } else {
            $allArrays = array(
                'ERROR:',
                ' Please make sure that file data.json is installed in (' .
                __DIR__ . ') directory!'
            );
        }

        return $allArrays;
    }

    /**
     * Returns an array with number divided into chunks
     *
     * @param float $iNumber
     *
     * @return array
     */
    private function getChunks(float $iNumber): array
    {
        $arrCh = array();
        $reversedValue = strrev(strval($iNumber));
        $reversedSize = strlen($reversedValue);

        for ($i = 0; $i < $reversedSize; $i += 3) {
            $arrCh[] = strrev(substr($reversedValue, $i, 3));
        }

        return $arrCh;
    }

    /**
     * If a number group has a feminine/masculine name, fix the words array to address that
     *
     * @param int   $fem :group identificator
     * @param array $arr :array with words corresponding to group identificator
     *
     * @return array Fixed array
     */
    private function fixArray(int $fem, array $arr): array
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

    /**
     * Defines the group name according to its place in a number (millions, thousands, roubles etc.)
     *
     * @param array  $group
     * @param int    $gnum
     * @param string $number
     *
     * @return string
     */
    private function getMagnitude(array $group, int $gnum, string $number): string
    {
        $subResult = null;
        $nls = strlen($number);
        $nxs = substr($number, -2);

        if (!$this->curr && $gnum == 1) {
            return "";
        }

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

    /**
     * Defines whether to show the currency name in the end of a string
     *
     * @param bool $show
     *
     * @return $this
     */
    public function showCurrency(bool $show = false)
    {
        $this->curr = $show;

        return $this;
    }
}

//Usage example below:

$number = 11123654987000; // 99.(9) trillion max
$show = false; //Whether to show currency name

$converter = new Number2Text();
$text = $converter->showCurrency($show)->typeNumber($number);
echo $text;
