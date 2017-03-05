<?php
declare(strict_types = 1);

namespace Converter;
use PHP\Math\BigInteger\BigInteger;
/**
 * Converts numbers to their text representation e.g. 12 -> twelve (Russian only at the moment)
 *
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 * @package   Converter v.1.0.3
 * @require   PHP 7.x+
 */
class Number2Text
{
    public $iNumber;
    public $curr;
    private $allArrays = array();
    private $arrUnits = array();
    private $arrTens = array();
    private $arrHundreds = array();
    private $arrMagnitude = array();

    public function __construct(BigInteger $number)
    {
        $this->allArrays = $this->loadArrays();

        list($this->arrUnits,
            $this->arrTens,
            $this->arrHundreds,
            $this->arrMagnitude) = $this->allArrays;

        $this->iNumber =  $number;
    }

    private function loadArrays(): array
    {
        $jsonFile = __DIR__ . '/data.json';
        if (file_exists($jsonFile) && is_file($jsonFile)) {
            $data = file_get_contents($jsonFile);
            $this->allArrays = json_decode($data, true);
        } else {
            $this->allArrays = array(
                'ERROR:',
                ' Please make sure that file data.json is installed in (' .
                __DIR__ . ') directory!'
            );
        }

        return $this->allArrays;
    }

    public function printNumber()
    {
//        if ($this->iNumber < new BigInteger('0') || $this->iNumber > new BigInteger('99999999999999') {
//            return $message = 'Error: Input number should be between 0 and 99\'999\'999\'999\'999';
//        }
//        if ($this->iNumber == 0) {  //avoiding all calculations to display such a simple result
//            return $message = 'ноль рублей';
//        }

        return $this->num2txt();
    }

    private function num2txt(): string
    {
        $message = null;
        if ($this->allArrays[0] == 'ERROR:') {
            return $message = $this->allArrays[0] . $this->allArrays[1];
        }
        $arrChunks = $this->getChunks();
        $numGroups = count($arrChunks);
        $fullResult = null;

        for ($i = $numGroups; $i >= 1; $i--) {
            $currChunk = $arrChunks[$i - 1];
            $this->fixArray($i);

            $preResult = null;
            $centis = intval($currChunk / 100);
            $decimals = $currChunk - $centis * 100;

            if ($centis > 0) {
                $preResult .= $this->arrHundreds[$centis - 1];
            }
            if ($decimals > 0 && $decimals < 20) {
                $preResult .= $this->arrUnits[$decimals - 1];
                $decimals = 0;
            }
            if ($decimals != 0) {
                $preResult .= $this->arrTens[intval($decimals / 10) - 1];
            }
            if ($decimals % 10 != 0) {
                $preResult .= $this->arrUnits[$decimals % 10 - 1];
            }
            if ($currChunk != 0 || $i == 1) {
                $preResult .= $this->getMagnitude($i, $currChunk);
            }

            $fullResult .= $preResult;
        }

        return $fullResult;
    }

    private function getChunks(): array
    {
        $arrCh = array();
        $rvrsValue = strrev(strval($this->iNumber));
        $rvrsSize = strlen($rvrsValue);

        for ($i = 0; $i < $rvrsSize; $i += 3) {
            $arrCh[] = strrev(substr($rvrsValue, $i, 3));
        }

        return $arrCh;
    }

    private function fixArray(int $fem): void
    {
        if ($fem == 2) {
            $this->arrUnits[0] = 'одна ';
            $this->arrUnits[1] = 'две ';
        } else {
            $this->arrUnits[0] = 'один ';
            $this->arrUnits[1] = 'два ';
        }

        return;
    }

    private function getMagnitude(int $gnum, string $chunk): string
    {
        $subResult = null;
        $nls = strlen($chunk);
        $nxs = substr($chunk, -2);

        if (!$this->curr && $gnum == 1) {
            return "";
        }

        if ($nls > 1 && $nxs >= 11 && $nxs <= 14) {
            return $subResult = $this->arrMagnitude[$gnum][2];
        }

        $condition = substr($chunk, -1);
        switch ($condition) {
            case 1:
                $subResult = $this->arrMagnitude[$gnum][0];
                break;
            case 2:
            case 3:
            case 4:
                $subResult = $this->arrMagnitude[$gnum][1];
                break;
            default:
                $subResult = $this->arrMagnitude[$gnum][2];
                break;
        }

        return $subResult;
    }

    public function showCurrency(bool $show = true)
    {
        $this->curr = $show;

        return $this;
    }
}

