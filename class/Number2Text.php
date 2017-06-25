<?php
declare(strict_types = 1);

namespace Converter\Core;

use Converter\Init\Data;

/**
 * Converts a number (up to 1e+333) to its text representation e.g. 12 -> двенадцать (Russian only at the moment).
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 */
final class Number2Text
{
    private $data;
    private $iNumber;
    private $currency;
    private $sign = null;

    /**
     * Number2Text constructor: Analyzes and creates number as a BigNumber object
     * @param string $number Number to be converted
     */
    public function __construct(string $number)
    {
        $this->prepNumber($number);
        $this->data = new Data();
    }

    /**
     * Defines the sign of the number, returns its absolute value
     * @param string $number - signed initial number
     * @property string $this->sign - sign of a number
     * @return string - unsigned number
     */
    private function prepNumber(string $number): string
    {
        if (substr($number, 0, 1) == '-') {
            $this->sign = 'минус ';
            $number = ltrim($number, '-');
        }
        if ($number === '0') {
            $this->sign = '';
        }
        return $this->iNumber = $number;
    }

    /**
     * Flag that indicates whether to print out the currency name along the number
     * @param bool $show
     * @return bool
     */
    public function currency(bool $show = false): bool
    {
        return $this->currency = $show;
    }

    public function convert(): string
    {
        $fullResult = null;
        $arrChunks = $this->makeChunks();
        $numGroups = count($arrChunks);

        if ($this->iNumber === '0') {
            $fullResult = 'ноль ';
        }

        for ($i = $numGroups; $i >= 1; $i--) {
            $currChunk = strrev($arrChunks[$i - 1]);
            $this->fixArray($i, $this->data);
            $preResult = $this->makeWords((int)$currChunk);

            if ((int)$currChunk !== 0 || $i === 1) {
                $preResult .= $this->getRegister($i, $currChunk);
            }
            $fullResult .= $preResult;
        }
        return $this->sign . $fullResult;
    }

    /**
     * Creates an array with reversed 3-digit chunks of given number
     * Example: '1125468' => array['864', '521', '1']
     * @return array
     */
    private function makeChunks(): array
    {
        //Converting object to string before reversing is mandatory, otherwise it won't work
        $rvrsValue = strrev($this->iNumber);
        $chunks = chunk_split($rvrsValue, 3);
        $arrCh = explode("\r\n", rtrim($chunks));

        return $arrCh;
    }

    /**
     * Change data array so that femine names of units is correct (Russian specific language construct)
     * @param int $fem - flag that indicates chunk index
     * @param \Converter\Init\Data $data - Data object with data arrays.
     */
    private function fixArray(int $fem, Data $data)
    {
        if ($fem === 2) {
            $data->arrUnits[0] = 'одна ';
            $data->arrUnits[1] = 'две ';
        } else {
            $data->arrUnits[0] = 'один ';
            $data->arrUnits[1] = 'два ';
        }
    }

    private function makeWords(int $cChunk): string
    {
        $resWords = '';
        $cent = (int)($cChunk / 100);
        $dec = $cChunk - $cent * 100;

        if ($cent >= 1) {
            $resWords .= $this->data->arrHundreds[$cent - 1];
        }
        if ($dec >= 1 && $dec <= 19) {
            $resWords .= $this->data->arrUnits[$dec - 1];
            $dec = 0;
        }
        if ($dec !== 0) {
            $resWords .= $this->data->arrTens[$dec / 10 - 1];
        }
        if ($dec % 10 !== 0) {
            $resWords .= $this->data->arrUnits[$dec % 10 - 1];
        }
        return $resWords;
    }

    private function getRegister(int $chunkPos, string $chunkData): string
    {
        $subResult = '';
        $lastDigits = (int)substr($chunkData, -2);
        $exponent = $this->data->arrExponents[$chunkPos];
        if (!$this->currency && $chunkPos === 1) {
            return $subResult;
        }
        $subResult = $exponent . $this->addSuffix($lastDigits, $chunkPos);
        return $subResult;
    }

    private function addSuffix(int $lastDigits, int $group): string
    {
        if ($group > 3) {
            $group = 3;
        }
        $last = $lastDigits % 10;
        if ($lastDigits >= 11 && $lastDigits <= 14) {
            $result = $this->data->arrSuffix[2][$group];
        } elseif ($last === 1) {
            $result = $this->data->arrSuffix[0][$group];
        } elseif ($last >= 2 && $last <= 4) {
            $result = $this->data->arrSuffix[1][$group];
        } else {
            $result = $this->data->arrSuffix[2][$group];
        }
        return $result;
    }
}
