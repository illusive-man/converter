<<<<<<< Updated upstream
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
     * @var mixed array
     */
    private $data;

    private $iNumber;

    /**
     * Flag for whether to show currency text or not
     *
     * @var bool
     */
    private $currency;

    /**
     * Array of triplets
     *
     * @var mixed array
     */
    private $arrChunks;

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
        $input === '0' ? $fullResult = 'ноль ' : $fullResult = '';
        $numGroups = count($this->arrChunks);

        $this->data = new Data();

        for ($i = $numGroups; $i >= 1; $i--) {
            $fullResult .= $this->getWords($i);
        }

        return $fullResult;
    }

    private function initData(string $number)
    {
        $this->iNumber = preg_replace("/[^\d]/", "", $number);
        $rvrsValue = strrev($this->iNumber);
        $chunks = chunk_split($rvrsValue, 3);
        $this->arrChunks = explode("\r\n", $chunks);
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

    /**
     * Depending on group's gender switches array to reflect that
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

    private function getCentum(int $chunk): string
    {
        $cent = (int)($chunk / 100);

        if ($cent >= 1) {
            return $this->data->arrHundreds[$cent - 1];
        }

        return '';
    }

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

        return $this->checkSingleChunk($last);
    }

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
=======
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
     * Array of triplets
     *
     * @var mixed array
     */
    private $arrChunks;

    /**
     * Number2Text constructor. Implements loading of functional data.
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
        $input === '0' ? $fres[0] = 'ноль ' : $fres = [];

        return implode($this->fetchData($fres));
    }

    public function fetchData(array $fullResult): array
    {
        $numGroups = count($this->arrChunks);

        for ($i = $numGroups; $i >= 1; $i--) {
            $fullResult[] = $this->getWords($i);
        }

        return $fullResult;
    }

    private function initData(string $number)
    {
        $this->iNumber = preg_replace("/[^\d]/", "", $number);
        $rvrsValue = strrev($this->iNumber);
        $chunks = chunk_split($rvrsValue, 3);
        $this->arrChunks = explode("\r\n", $chunks);
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

    private function getCentum(int $chunk): string
    {
        $cent = (int)($chunk / 100);

        if ($cent >= 1) {
            return $this->data->arrHundreds[$cent - 1];
        }

        return '';
    }

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

        return $this->checkSingleChunk($last);
    }

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
>>>>>>> Stashed changes
