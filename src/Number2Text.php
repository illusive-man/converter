<?php
declare(strict_types = 1);

namespace Converter\Core;

use Converter\Init\Data;

/**
 * Converts a number up to 1e+510 to its text representation e.g. 312 -> триста двенадцать (Russian only).
 *
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 */
final class Number2Text
{

    /**
     * Contains all the language specific data for Number2Text class
     *
     * @var object
     */
    private $data;

    /**
     * The number to be converted to text
     *
     * @var string
     */
    private $iNumber;

    /**
     * Flag that indicates whether to show currency name
     *
     * @var bool
     */
    private $currency;

    /**
     * Array with all triplets
     *
     * @var mixed array
     */
    private $arrChunks;

    /**
     * Load of functional data from Data class.
     *
     * Number2Text constructor.
     */
    public function __construct()
    {
        $this->data = new Data();
    }

    /**
     * Sets the visibility of currency name
     *
     * @param bool $show
     */
    public function currency(bool $show = true)
    {
        $this->currency = $show;
    }

    public function convert(string $input): string
    {
        $this->initData($input);

        $input === '0' ? $fullResult = 'ноль ' : $fullResult = null;

        return implode($this->fetchData($fullResult));
    }

    /**
     * Iterates through triplets and calls main converter method
     *
     * @param $fres
     * @return array
     */
    public function fetchData($fres): array
    {
        $numGroups = count($this->arrChunks);

        $fullResult[] = $fres;
        for ($i = $numGroups; $i >= 1; $i--) {
            $fullResult[] = $this->getWords($i);
        }

        return $fullResult;
    }

    /**
     * Removes non numeric data from number and divides it to by chunks, 3 digits each (triplets)
     *
     * @param string $number
     */
    private function initData(string $number)
    {
        $this->iNumber = preg_replace("/[^\d]/", "", $number);
        $rvrsValue = strrev($this->iNumber);
        $chunks = chunk_split($rvrsValue, 3);
        $this->arrChunks = explode("\r\n", $chunks);
    }

    /**
     * Get the triplet and sends it to the methods to process
     *
     * @param int $iterator
     * @return string
     */
    private function getWords(int $iterator): string
    {
        $currChunk = (int)strrev($this->arrChunks[$iterator - 1]);
        $iterator < 3 ? $this->switchArray($iterator) : true;
        $preResult = $this->makeWords($currChunk);

        if ($currChunk !== 0 || $iterator === 1) {
            $preResult .= $this->getExponent($iterator, $currChunk);
        }

        return $preResult;
    }

    /**
     * Depending on group's gender switches array to adjust that
     *
     * @param int $group
     */
    private function switchArray(int $group)
    {
        if ($group === 2) {
            $this->data->arrUnits[0] = 'одна ';
            $this->data->arrUnits[1] = 'две ';

            return;
        }
        $this->data->arrUnits[0] = 'один ';
        $this->data->arrUnits[1] = 'два ';
    }

    /**
     * Parent method for transforming the triplet to its text representation
     *
     * @param int $cChunk
     * @return string
     */
    private function makeWords(int $cChunk): string
    {
        $decs = $cChunk % 100;
        $resWords = $this->getCentum($cChunk);

        if ($decs === 0) {
            return $resWords;
        }

        $resWords .= $this->getDecem($decs);

        return $resWords;
    }

    /**
     * Returns wording for hundreds
     *
     * @param int $chunk
     * @return string
     */
    private function getCentum(int $chunk): string
    {
        $cent = (int)($chunk / 100);

        if ($cent >= 1) {
            return $this->data->arrHundreds[$cent - 1];
        }

        return '';
    }

    /**
     *  Returns wording for tens and/or teens
     *
     * @param int $decs
     * @return string
     */
    private function getDecem(int $decs): string
    {
        $result = '';
        if ($decs < 20) {
            $result .= $this->data->arrUnits[$decs - 1];

            return $result;
        }

        $result .= $this->data->arrTens[$decs / 10 - 1];

        if ($decs % 10 !== 0) {
            $result .= $this->data->arrUnits[$decs % 10 - 1];
        }

        return $result;
    }

    /**
     * Gets the exponent name and returns it along with suffix (language specific)
     *
     * @param int $chunkPos
     * @param int $chunkData
     * @return string
     */
    private function getExponent(int $chunkPos, int $chunkData): string
    {
        if (!$this->currency && $chunkPos === 1) {
            return '';
        }
        $exponent = $this->data->arrExponents[$chunkPos];
        $chunkPos > 3 ? $chunkPos = 3 : true;
        $index = $this->getIndex($chunkData % 100);
        $suffix = $this->data->arrSuffix[$index][$chunkPos];

        return $exponent . $suffix;
    }

    /**
     * Returns index for suffix of two-digit number
     *
     * @param int $lastDigits
     * @return int
     */
    private function getIndex(int $lastDigits): int
    {
        $last = $lastDigits % 10;

        if ($lastDigits >= 11 && $lastDigits <= 14) {
            return 2;
        }

        return $this->checkSingleChunk($last);
    }

    /**
     * Returns index for suffix of single digit number
     *
     * @param int $digit
     * @return int
     */
    public function checkSingleChunk(int $digit): int
    {
        if ($digit === 1) {
            return 0;
        }
        if ($digit >= 2 && $digit <= 4) {
            return 1;
        }

        return 2;
    }
}
