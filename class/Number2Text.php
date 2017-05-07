<?php
declare(strict_types = 1);

namespace Converter\Number2Text;

use PHP\Math\BigInteger\BigInteger as BigInteger;

/**
 * Converts any numbers (up to 1e+303!) to their text representation e.g. 12 -> twelve (Russian only at the moment)
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 * @package   Converter v.1.1.3
 * @require   PHP 7.0+
 */
class Number2Text
{
    /**
     * The number we need to convert and print in words
     */
    public $iNumber;

    /**
     * // This flag indicates if we want to include currency name to the output
     */
    public $currency;

    /**
     * An array that contains all other arrays imported from data.json
     */
    private $allArrays = [];

    /**
    * An array that contains names of units and teens
    */
    private $arrUnits = [];

    /**
    * An array that contains names of tens
    */
    private $arrTens = [];

    /**
    * An array that contains names of hundreds
    */
    private $arrHundreds = [];

    /**
    * An array that contains exponent's names
    */
    private $arrExponents = [];

    /**
     * An array that contains register's names
     */
    private $arrRegisters = [];

    /**
    * Contains the converted number string
    */
    private $fullResult;

    /**
     * Number2Text constructor - Creates BigInt number and loads data from data.json file into arrays
     * @param string $number Contains the input number being converted
     */
    public function __construct(string $number)
    {
        $this->iNumber = new BigInteger($number);
        $this->allArrays = $this->loadArrays();

        list(
            $this->arrUnits,
            $this->arrTens,
            $this->arrHundreds,
            $this->arrExponents,
            $this->arrRegisters
            ) = $this->allArrays;
    }

    /**
     * Load data from .json file and populate corresponding arrays
     * @return array Contains all arrays
     */
    private function loadArrays(): array
    {
        $jsonFile = __DIR__ . DIRECTORY_SEPARATOR . "data.json";

        if (file_exists($jsonFile) && is_file($jsonFile)) {
            $data = file_get_contents($jsonFile);
            $this->allArrays = json_decode($data, true);
        } else {
            //TODO: Make error handling methods or class
            //throw new Exception('File data.json doesn\'t exist in the directory!');
        }

        return $this->allArrays;
    }

    public static function makeBignumber(int $value = 0, bool $generator = true): string
    {
        $mantissa = '';

        if ($generator && $value === 0) {
            $mantissa = mt_rand(1, 100);
            $value = mt_rand(1, 303);
        } elseif (!$generator) {
            $mantissa = '1';
        }

        $num = str_repeat('0', $value);
        
        return $mantissa . $num;
    }

    public function withCurrency(bool $show = true)
    {
        return $this->currency = $show;
    }

    public function num2txt(): string
    {
//        if ($this->iNumber == '0') {
//            //TODO: сделать определения нулевого (или отрицательного) числа до вызова метода.
//            return $this->fullResult = "ноль ";
//        }

        $this->fullResult = '';
        $arrChunks = $this->getChunks();
        $numGroups = count($arrChunks);

        for ($i = $numGroups; $i >= 1; $i--) {
            $currChunk = $arrChunks[$i - 1];
            $this->fixArray($i);

            $preResult = null;
            $centis = (int)($currChunk / 100);
            $decimals = (int)($currChunk - $centis * 100);

            if ($centis >= 1) {
                $preResult .= $this->arrHundreds[$centis - 1];
            }
            if ($decimals >= 1 && $decimals <= 19) {
                $preResult .= $this->arrUnits[$decimals - 1];
                $decimals = 0;
            }
            if ($decimals !== 0) {
                $preResult .= $this->arrTens[($decimals / 10) - 1];
            }
            if ($decimals % 10 !== 0) {
                $preResult .= $this->arrUnits[$decimals % 10 - 1];
            }
            if ($currChunk != 0 || $i === 1) {
                $preResult .= $this->getRegister($i, $currChunk);
            }

            $this->fullResult .= $preResult;
        }

        return $this->fullResult;
    }

    private function getChunks(): array
    {
        $arrCh = [];
        $rvrsValue = strrev((string)$this->iNumber);
        $rvrsSize = strlen($rvrsValue);

        for ($i = 0; $i < $rvrsSize; $i += 3) {
            $arrCh[] = strrev(substr($rvrsValue, $i, 3));
        }

        return $arrCh;
    }

    private function fixArray(int $fem): void
    {
        if ($fem === 2) {
            $this->arrUnits[0] = 'одна ';
            $this->arrUnits[1] = 'две ';
        } else {
            $this->arrUnits[0] = 'один ';
            $this->arrUnits[1] = 'два ';
        }

        return;
    }

    private function getRegister(int $gnum, string $chunk): string
    {
        $subResult = '';
        $chunkLength = strlen($chunk);
        $chunkUnits = substr($chunk, -2);

        $offset = abs($gnum - 3);
        $exponent = $this->arrExponents[$offset];
        $lastDigit = (int)substr($chunk, -1);

        if (!$this->currency && $gnum === 1) {
            return $subResult;
        }

        if ($chunkLength >= 2 && $chunkUnits >= 11 && $chunkUnits <= 14) {
            if ($gnum === 1 || $gnum === 2) {
                $subResult = $this->arrRegisters[$gnum * $gnum + 1];
            } else {
                $subResult = $exponent . 'ов '; //2
            }

            return $subResult;
        }

        if ($lastDigit === 1) {
            $subResult = $exponent . ' '; //0
        } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
            $subResult = $exponent . 'а '; //1
        } else {
            $subResult = $exponent . 'ов '; //2
        }

        if ($gnum === 1 || $gnum === 2) {
            $subResult = $this->getCase($gnum, $lastDigit);
        }

        return $subResult;
    }

    private function getCase(int $group, int $cond): string
    {
        if ($group === 1) {
            if ($cond === 1) {
                $result = $this->arrRegisters[0];
            } elseif ($cond >= 2 && $cond <= 4) {
                $result = $this->arrRegisters[1];
            } else {
                $result = $this->arrRegisters[2];
            }
        } else {
            if ($cond === 1) {
                $result = $this->arrRegisters[3];
            } elseif ($cond >= 2 && $cond <= 4) {
                $result = $this->arrRegisters[4];
            } else {
                $result = $this->arrRegisters[5];
            }
        }

        return $result;
    }
}
