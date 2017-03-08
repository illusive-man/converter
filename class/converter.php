<?php
declare(strict_types = 1);

namespace Converter\Number2Text;

use PHP\Math\BigInteger\BigInteger;

/**
 * Converts any numbers (up to 1.0e+300!) to their text representation e.g. 12 -> twelve (Russian only at the moment)
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 * @package   Converter v.1.0.4
 * @require   PHP 7.0+
 */
class Number2Text
{
    const MINUS = 'минус ';
    public $iNumber;
    public $currency;
    private $allArrays = array();
    private $arrUnits = array();
    private $arrTens = array();
    private $arrHundreds = array();
    private $arrMagnitude = array();
    private $fullResult = '';

    public function __construct(BigInteger $number)
    {
        $this->allArrays = $this->loadArrays();

        list(
            $this->arrUnits,
            $this->arrTens,
            $this->arrHundreds,
            $this->arrMagnitude
            ) = $this->allArrays;

        $this->iNumber = $number;
    }

    private function loadArrays(): array
    {
        $jsonFile = __DIR__ . DIRECTORY_SEPARATOR . "data.json";
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

//    public function printNumber()
//    {
//        if ($this->iNumber == '0') {
//            return "ноль рублей"; //TODO: сделать определения нулевого (или отрицательного) числа в коде.
//        }
//
//        return $this->num2txt();
//    }

    public function num2txt(): string
    {
        $message = null;
        if (!is_array($this->allArrays[0])) {
            return $message = $this->allArrays[0] . $this->allArrays[1];
        }
        $arrChunks = $this->getChunks();
        $numGroups = count($arrChunks);

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

            $this->fullResult .= $preResult;
        }

        return $this->fullResult;
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
        $chunkLength = strlen($chunk);
        $chunkUnits = substr($chunk, -2);

        if (!$this->currency && $gnum == 1) {
            return "";
        }

        if ($chunkLength > 1 && $chunkUnits >= 11 && $chunkUnits <= 14) {
            if ($gnum == 1) {
                $subResult = "рублей ";
            } elseif ($gnum == 2) {
                $subResult = "тысяч ";
            } else {
                $subResult = $this->arrMagnitude[$gnum - 3] . 'ов '; //2
            }

            return $subResult;
        }

        $condition = intval(substr($chunk, -1));

        if ($gnum == 1 || $gnum == 2) {
            $subResult = $this->getCase($gnum, $condition);

            return $subResult;
        }

        $offset = $gnum - 3;

        if ($condition == 1) {
            $subResult = $this->arrMagnitude[$offset] . ' '; //0
        } elseif ($condition >= 2 && $condition <= 4) {
            $subResult = $this->arrMagnitude[$offset] . 'а '; //1
        } else {
            $subResult = $this->arrMagnitude[$offset] . 'ов '; //2
        }

        return $subResult;
    }

    private function getCase(int $group, int $cond): string
    {
        if ($group == 1) {
            if ($cond == 1) {
                $result = 'рубль ';
            } elseif ($cond >= 2 && $cond <= 4) {
                $result = 'рубля ';
            } else {
                $result = 'рублей ';
            }
        } else {
            if ($cond == 1) {
                $result = 'тысяча ';
            } elseif ($cond >= 2 && $cond <= 4) {
                $result = 'тысячи ';
            } else {
                $result = 'тысяч ';
            }
        }

        return $result;
    }

    public function showCurrency(bool $show = true)
    {
        $this->currency = $show;

        return $this;
    }
}
