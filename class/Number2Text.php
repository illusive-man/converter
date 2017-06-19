<?php
declare(strict_types = 1);

namespace Converter\Core;

use Exception;
use PHP\Math\BigInteger\BigInteger;

/**
 * Converts a number (up to 1e+333) to its text representation e.g. 12 -> двенадцать (Russian only at the moment).
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 */
final class Number2Text
{
    public $iNumber;
    private $currency;
    private $fullResult = null;
    private $zero = 'ноль ';
    private static $dataFile = "data.json";
    private $sign = '';
    private static $arrHundreds;
    private static $arrTens;
    private static $arrUnits;
    private static $arrExponents;
    private static $arrRegisters;
    public static $expSize;
    private $cache = [];

    /**
     * Number2Text constructor: Analyzes and creates number as a BigNumber object
     * @param string $number Number to be converted
     * @throws Exception
     */
    public function __construct(string $number)
    {
        $absolute = $this->checkNegative($number);
        $this->iNumber = new BigInteger($absolute);
        self::initConfig();
    }

    private function checkNegative(string $number): string
    {
        if (substr($number, 0, 1) == '-') { //TODO: Try to implement as closure
            $this->sign = 'минус ';
            $number = ltrim($number, '-');
        }
        if ($number === '0') {
            $this->sign = '';
        }

        return $number;
    }

    /**
     * Loads fuctional data from JSON file and populates arrays with that data.
     * If data.json is not exist in class dir, creates that file.
     * @throws \Exception
     */
    public static function initConfig()
    {
        $jsonFile = __DIR__ . DIRECTORY_SEPARATOR . self::$dataFile;
        $arrays = null;
        if (file_exists($jsonFile) && is_file($jsonFile)) {
                $data = file_get_contents($jsonFile);
                $arrays = json_decode($data, true);
                list(self::$arrUnits, self::$arrTens, self::$arrHundreds,
                    self::$arrExponents, self::$arrRegisters) = $arrays;
                self::$expSize = count(self::$arrExponents) + 1;
        } else {
            require_once(__DIR__ . DIRECTORY_SEPARATOR . "..\make_data_json_file.php");
            createData();
            //TODO: Replace recursive call with separate method(?)
            self::initConfig();
        }
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

    public function convert(): string //TODO: MEMOIZATION
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
     * Changes the array femine name set data array to reflect that (Russian specific language construct)
     * @param int $fem
     */
    private function fixArray(int $fem): void
    {
        if ($fem === 2) {
            self::$arrUnits[0] = 'одна ';
            self::$arrUnits[1] = 'две ';
        } else {
            self::$arrUnits[0] = 'один ';
            self::$arrUnits[1] = 'два ';
        }
    }

    private function makeWords(string $cChunk): string
    {
        $resWords = '';
        $cent = (int)($cChunk / 100);
        $dec = (int)$cChunk - $cent * 100;

        if ($cent >= 1) {
            $resWords .= self::$arrHundreds[$cent - 1];
        }
        if ($dec >= 1 && $dec <= 19) {
            $resWords .= self::$arrUnits[$dec - 1];
            $dec = 0;
        }
        if ($dec !== 0) {
            $resWords .= self::$arrTens[$dec / 10 - 1];
        }
        if ($dec % 10 !== 0) {
            $resWords .= self::$arrUnits[$dec % 10 - 1];
        }

        return $resWords;
    }

    private function getRegister(int $chunkPos, string $chunkData): string
    {
        $subResult = '';
        $chunkUnits = substr($chunkData, -2);

        $lastDigit = (int)substr($chunkData, -1);
        $offset = abs($chunkPos - 3);
        $exponent = self::$arrExponents[$offset];

        if (!$this->currency && $chunkPos === 1) {
            return $subResult;
        }

        if ($chunkUnits >= 11 && $chunkUnits <= 14) {
            if ($chunkPos === 1 || $chunkPos === 2) {
                $subResult = self::$arrRegisters[$chunkPos ** 2 + 1];
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

        return self::$arrRegisters[$result];
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
