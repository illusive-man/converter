<?php
declare(strict_types = 1);

namespace Converter\Core;

use Converter\Init\Data;

/**
 * Converts a number (up to 1e+510) to its text representation e.g. 312 -> триста двенадцать (Russian only).
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 */
final class Number2Text
{
    private $data;
    private $iNumber;
    private $currency;
    private $arrChunks;

    public function currency(bool $show = true)
    {
        $this->currency = $show;
    }

    public function convert(string $input): string
    {
        $this->initData($input);
        $fullResult = '';
        if ($this->iNumber === '0') {
            $fullResult = 'ноль ';
        }
        $numGroups = count($this->arrChunks);

        return $this->magicConverter($numGroups, $fullResult);
    }

    private function initData(string $number)
    {
        $this->iNumber = preg_replace("/[^\d]/", "", $number);
        $rvrsValue = strrev($this->iNumber);
        $chunks = chunk_split($rvrsValue, 3);
        $this->arrChunks = explode("\r\n", $chunks);
    }

    private function magicConverter(int $numgrps, string $fullres): string
    {
        $this->data = new Data();

        for ($i = $numgrps; $i >= 1; $i--) {
            $fullres .= $this->getWords($i);
        }

        return $fullres;
    }

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

    private function makeWords(int $cChunk): string
    {
        $resWords = '';
        $cent = (int)($cChunk / 100);
        $decs = $cChunk % 100;
        if ($cent >= 1) {
            $resWords = $this->data->arrHundreds[$cent - 1];
        }
        if ($decs === 0) {
            return $resWords;
        }
        if ($decs < 20) {
            $resWords .= $this->data->arrUnits[$decs - 1];

            return $resWords;
        }
        $resWords .= $this->data->arrTens[$decs / 10 - 1];
        if ($decs % 10 !== 0) {
            $resWords .= $this->data->arrUnits[$decs % 10 - 1];
        }

        return $resWords;
    }

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

    private function getIndex(int $lastDigits): int
    {
        $last = $lastDigits % 10;
        if ($lastDigits >= 11 && $lastDigits <= 14) {
            return 2;
        }
        if ($last === 1) {
            return 0;
        }
        if ($last >= 2 && $last <= 4) {
            return 1;
        }

        return 2;
    }
}
