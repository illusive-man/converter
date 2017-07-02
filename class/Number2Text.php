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
    private $sign;
    private $arrChunks;

    public function convert(string $input, bool $show = false): string
    {
        $this->currency = $show;
        $this->prepNumber($input);
        $this->makeChunks();
        $numGroups = count($this->arrChunks);
        $fullResult = '';

        if ($this->iNumber === '0') {
            $fullResult = 'ноль ';
            $this->sign = '';
        }

        return $this->magicConverter($numGroups, $fullResult);
    }

    private function magicConverter(int $numgrps, string $fullres): string
    {
        $this->data = new Data();

        for ($i = $numgrps; $i >= 1; $i--) {
            $currChunk = (int)strrev($this->arrChunks[$i - 1]);
            $this->fixArray($i);
            $preResult = $this->makeWords($currChunk);
            if ($currChunk !== 0 || $i === 1) {
                $preResult .= $this->getRegister($i, $currChunk);
            }
            $fullres .= $preResult;
        }

        return $this->sign . $fullres;
    }

    /**
     * Checks and normalizes input number, defines its sign and returns absolute value.
     * @param string    $number - signed input number
     * @property string $sign   - sign of a number
     * @return string - unsigned number
     */
    private function prepNumber(string $number): string
    {
        $this->sign = '';
        if (substr($number, 0, 1) === "-") {
            $this->sign = 'минус ';
            $number = substr($number, 1);
        }

        return $this->iNumber = preg_replace("/[^\d]/", "", $number);
    }

    /**
     * Creates an array with reversed 3-digit chunks of given number.
     * Example: '1125468' => array['864', '521', '1']
     */
    private function makeChunks()
    {
        $rvrsValue = strrev($this->iNumber);
        $chunks = chunk_split($rvrsValue, 3);
        $this->arrChunks = explode("\r\n", rtrim($chunks));
    }

    /**
     * Change data array so that femine names of units are correct (Russian specific language construct)
     * @param int $group - chunk's group in number from the end
     * @internal param \Converter\Init\Data $data - Data object with data arrays.
     */
    private function fixArray(int $group)
    {
        if ($group === 2) {
            $this->data->arrUnits[0] = 'одна ';
            $this->data->arrUnits[1] = 'две ';
        } else {
            $this->data->arrUnits[0] = 'один ';
            $this->data->arrUnits[1] = 'два ';
        }
    }

    private function makeWords(int $cChunk): string
    {
        $resWords = '';
        $cent = (int)($cChunk / 100);
        $decs = $cChunk % 100;
        if ($cent >= 1) {
            $resWords .= $this->data->arrHundreds[$cent - 1];
        }
        if ($decs >= 1 && $decs <= 19) {
            $resWords .= $this->data->arrUnits[$decs - 1];
            return $resWords;
        } elseif ($decs !== 0) {
            $resWords .= $this->data->arrTens[$decs / 10 - 1];
        }
        if ($decs % 10 !== 0) {
            $resWords .= $this->data->arrUnits[$decs % 10 - 1];
        }

        return $resWords;
    }

    private function getRegister(int $chunkPos, int $chunkData): string
    {
        $lastDigits = $chunkData % 100;
        $exponent = $this->data->arrExponents[$chunkPos];
        if (!$this->currency && $chunkPos === 1) {
            return '';
        }
        $group = $chunkPos;
        if ($chunkPos > 3) {
            $group = 3;
        }
        $index = $this->getSuffix($lastDigits, $group);
        $suffix = $this->data->arrSuffix[$index][$group];
        return $exponent . $suffix;
    }

    private function getSuffix(int $lastDigits): int
    {
        $last = $lastDigits % 10;

        if ($lastDigits > 10 && $lastDigits < 15) {
            return 2;
        } elseif ($last === 1) {
            return 0;
        } elseif ($last >= 2 && $last <= 4) {
            return 1;
        }

        return 2;
    }
}
