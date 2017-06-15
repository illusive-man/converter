<?php
declare(strict_types = 1);

namespace Converter\Core;

use Exception;
use PHP\Math\BigInteger\BigInteger;

/**
 * Converts a number (up to 1e+303) to its text representation e.g. 12 -> двенадцать (Russian only at the moment)
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 */
final class Number2Text
{
    private $iNumber;
    private $currency;
    private $fullResult = null;
    private $zero = 'ноль ';
    private $dataFile = "data.json";
    private $sign = '';
    private $arrHundreds;
    private $arrTens;
    private $arrUnits;
    private $arrExponents;
    private $arrRegisters;

    /**
     * Number2Text constructor: Checks the number,
     * @param string $number Number to be converted
     * @throws Exception
     */
    public function __construct(string $number)
    {
        $number = $this->checkNumber($number);
        $this->iNumber = new BigInteger($number);
        $this->initConfig();
    }

    private function checkNumber(string $number): string
    {
        if (substr($number, 0, 1) == '-') {
            $this->sign = 'минус ';
            $number = ltrim($number, '-');
        }
        if ($number === '0') {
            $this->sign = '';
        }

        return $number;
    }

    /**
     * Loads fuctional data from JSON file to arrays with that data.
     * @throws \Exception
     */
    private function initConfig()
    {
        $jsonFile = __DIR__ . DIRECTORY_SEPARATOR . $this->dataFile;
        $arrays = null;
        if (file_exists($jsonFile) && is_file($jsonFile)) {
                $data = file_get_contents($jsonFile);
                $arrays = json_decode($data, true);
                list($this->arrUnits, $this->arrTens, $this->arrHundreds,
                    $this->arrExponents, $this->arrRegisters) = $arrays;
        } else {
            require_once(__DIR__ . DIRECTORY_SEPARATOR . "..\make_data_json_file.php");
            createData();
            $this->initConfig();
        }
    }

    public static function makeBignumber(int $value = 0, bool $generator = true): string
    {
        $mantissa = '';

        if ($generator && $value === 0) {
            $mantissa = (string)mt_rand(1, 100);
            $value = mt_rand(1, 303);
        } elseif (!$generator) {
            $mantissa = '1';
        }

        $num = str_repeat('0', $value);

        return $mantissa . $num;
    }

    /**
     * Flag that indicates whether to print out the currency name along the number
     * @param bool $use
     * @return bool
     */
    public function withCurrency(bool $use = true): bool
    {
        return $this->currency = $use;
    }

    public function convert(): string
    {
        $fullResult = null;
        $arrChunks = $this->makeChunksArray();
        $numGroups = count($arrChunks);

        if ($this->iNumber == '0') {
            $fullResult = $this->zero;
        }

        for ($i = $numGroups; $i >= 1; $i--) {
            $currChunk = strrev($arrChunks[$i - 1]);
            $this->fixArray($i);
            $preResult = $this->makeWords($currChunk);

            if ($currChunk != 0 || $i === 1) {
                $preResult .= $this->getRegister($i, $currChunk);
            }

            $fullResult .= $preResult;
        }

        return $this->fullResult = $this->sign . $fullResult;
    }

    /**
     * Creates an array with reversed 3-digit chunks of given number
     * Example: '1125468' => array['864', '521', '1']
     * @return array
     */
    private function makeChunksArray(): array
    {
        //Converting object to string before reversing is mandatory, otherwise it won't work
        $rvrsValue = strrev((string)$this->iNumber);
        $chunks = chunk_split($rvrsValue, 3);
        $arrCh = explode("\r\n", rtrim($chunks));

        return $arrCh;
    }

    /**
     * Changes the array ne name set data array to reflect that (Russian specific language construct)
     * @param int $fem
     */
    private function fixArray(int $fem): void
    {
        if ($fem === 2) {
            $this->arrUnits[0] = 'одна ';
            $this->arrUnits[1] = 'две ';
        } else {
            $this->arrUnits[0] = 'один ';
            $this->arrUnits[1] = 'два ';
        }
    }

    private function makeWords(string $cChunk): string
    {
        $resWords = '';
        $cent = (int)($cChunk / 100);
        $dec = (int)$cChunk - $cent * 100;

        if ($cent >= 1) {
            $resWords .= $this->arrHundreds[$cent - 1];
        }
        if ($dec >= 1 && $dec <= 19) {
            $resWords .= $this->arrUnits[$dec - 1];
            $dec = 0;
        }
        if ($dec !== 0) {
            $resWords .= $this->arrTens[$dec / 10 - 1];
        }
        if ($dec % 10 !== 0) {
            $resWords .= $this->arrUnits[$dec % 10 - 1];
        }

        return $resWords;
    }

    private function getRegister(int $chunkPos, string $chunkData): string
    {
        $subResult = '';
        $chunkUnits = substr($chunkData, -2);

        $lastDigit = (int)substr($chunkData, -1);
        $offset = abs($chunkPos - 3);
        $exponent = $this->arrExponents[$offset];

        if (!$this->currency && $chunkPos === 1) {
            return $subResult;
        }

        if ($chunkUnits >= 11 && $chunkUnits <= 14) {
            if ($chunkPos === 1 || $chunkPos === 2) {
                $subResult = $this->arrRegisters[$chunkPos ** 2 + 1];
            } else {
                $subResult = $exponent . 'ов ';
            }

            return $subResult;
        }

        if ($chunkPos === 1 || $chunkPos === 2) {
            $subResult = $this->getCase($chunkPos, $lastDigit);
        } else {
            $subResult = $this->addSuffix($lastDigit, $exponent);
        }

        return $subResult;
    }

    private function getCase(int $group, int $cond): string
    {
        $result = null;
        switch ($group) {
            case 1:
                if ($cond === 1) {
                    $result = 0;
                } elseif ($cond >= 2 && $cond <= 4) {
                    $result = 1;
                } else {
                    $result = 2;
                }
                break;
            case 2:
                if ($cond === 1) {
                    $result = 3;
                } elseif ($cond >= 2 && $cond <= 4) {
                    $result = 4;
                } else {
                    $result = 5;
                }
        }

        return $this->arrRegisters[$result];
    }

    private function addSuffix(int $lastDigit, string $exponent): string
    {
        if ($lastDigit === 1) {
            $result = $exponent . ' ';
        } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
            $result = $exponent . 'а ';
        } else {
            $result = $exponent . 'ов ';
        }

        return $result;
    }
}
