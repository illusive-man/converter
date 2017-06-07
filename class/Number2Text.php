<?php
declare(strict_types = 1);

namespace Converter\Number2Text;

use PHP\Math\BigInteger\BigInteger;
use PHPUnit\Runner\Exception;

/**
 * Converts a number (up to 1e+303) to its text representation e.g. 12 -> twelve (Russian only at the moment)
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 * @package   Converter v.1.1.3
 * @require   PHP 7.0+
 */
class Number2Text
{
    private $iNumber;
    private $currency;
    private $allArrays = [];
    private $arrUnits;
    private $arrTens;
    private $arrHundreds;
    private $arrExponents;
    private $arrRegisters;
    private $fullResult;
    private $zero = 'ноль ';
    private $dataFile = "data.json";
    private $sign = '';

    public function __construct(string $number)
    {
        if (substr($number, 0, 1) == '-') {
            $this->sign = 'минус ';
            $number = ltrim($number, '-');
        }

        $this->iNumber = new BigInteger($number);

        try {
            $this->allArrays = $this->loadArrays();
        } catch (\TypeError $e) {
            throw new Exception('File data.json doesn\'t exist in the directory!');
        }

        list($this->arrUnits, $this->arrTens, $this->arrHundreds,
            $this->arrExponents, $this->arrRegisters) = $this->allArrays;
    }

    private function loadArrays(): array
    {
        $jsonFile = __DIR__ . DIRECTORY_SEPARATOR . $this->dataFile;
        $arrays = null;
        if (file_exists($jsonFile) && is_file($jsonFile)) {
            $data = file_get_contents($jsonFile);
            $arrays = json_decode($data, true);
        }

        return $arrays;
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

    public function withCurrency(bool $show = true): bool
    {
        return $this->currency = $show;
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
            $preResult = null;
            $centum = (int)($currChunk / 100);
            $decem = (int)$currChunk - $centum * 100;

            $preResult = $this->makeWords($centum, $decem);

            if ($currChunk != 0 || $i === 1) {
                $preResult .= $this->getRegister($i, $currChunk);
            }

            $fullResult .= $preResult;
        }

        return $this->fullResult = $this->sign . $fullResult;
    }

    private function makeChunksArray(): array
    {
        $rvrsValue = strrev((string)$this->iNumber);
        //Converting object to string before reversing is mandatory, otherwise it won't work
        $chunks = chunk_split($rvrsValue, 3);
        $arrCh = explode("\r\n", rtrim($chunks));

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
    }

    private function makeWords(int $cent, int $dec): string
    {
        $resWords = '';

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
        $chunkLength = strlen($chunkData);
        $chunkUnits = substr($chunkData, -2);

        $lastDigit = (int)substr($chunkData, -1);
        $offset = abs($chunkPos - 3);
        $exponent = $this->arrExponents[$offset];

        if (!$this->currency && $chunkPos === 1) {
            return $subResult;
        }

        if ($chunkLength >= 2 && $chunkUnits >= 11 && $chunkUnits <= 14) {
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

    //Used only for PHPUnit testing's sake by granting access to class' private properties.
    public function getAllArrays(): array
    {
        return $this->allArrays;
    }
}
