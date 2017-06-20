<?php
declare(strict_types = 1);

namespace Converter\Core;

/**
 * Converts a number (up to 1e+333) to its text representation e.g. 12 -> двенадцать (Russian only at the moment).
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 */
final class Number2Text
{
    private $iNumber;
    private $currency;
    private $fullResult = null;
    private $sign = '';
    public static $expSize;
    private static $arrHundreds;
    private static $arrTens;
    private static $arrUnits;
    private static $arrExponents;
    private static $arrRegisters;

    /**
     * Number2Text constructor: Analyzes and creates number as a BigNumber object
     * @param string $number Number to be converted
     */
    public function __construct(string $number)
    {
        $this->iNumber = $this->prepNumber($number);
        self::loadAllData();
    }

    private function prepNumber(string $number): string
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
            $fullResult = 'ноль ';
        }

        for ($i = $numGroups; $i >= 1; $i--) {
            $currChunk = strrev($arrChunks[$i - 1]);
            $this->fixArray($i);
            $preResult = $this->makeWords((int)$currChunk);

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

    private function makeWords(int $cChunk): string
    {
        $resWords = '';

        $cent = (int)($cChunk / 100);
        $dec = $cChunk - $cent * 100;

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

        if (!$this->currency && $chunkPos === 1) { //return empty string if number is up to 3 numbers
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
        if ($cond === 1) {
            $group == 1 ? $result = 0 : $result = 3;
        } elseif ($cond >= 2 && $cond <= 4) {
            $group == 1 ? $result = 1 : $result = 4;
        } else {
            $group == 1 ? $result = 2 : $result = 5;
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

//@formatter:off
    public static function loadAllData(): void
    {

        self::$arrExponents = ['миллион','миллиард','триллион','квадриллион','квинтиллион','секстиллион','септиллион',
            'октиллион','нониллион','дециллион','ундециллион','дуодециллион','тредециллион','кваттордециллион',
            'квиндециллион','сексдециллион','септендециллион','октодециллион','новемдециллион','вигинтиллион',
            'унвигинтиллион','дуовигинтиллион','тревигинтиллион','кватторвигинтиллион','квинвигинтиллион',
            'сексвигинтиллион','септенвигинтиллион','октовигинтиллион','новемвигинтиллион','тригинтиллион',
            'унтригинтиллион','дуотригинтиллион','третригинтиллион','кватортригинтиллион','квинтригинтиллион',
            'секстригинтиллион','септентригинтиллион','октотригинтиллион','новемтригинтиллион','квадрагинтиллион',
            'унквадрагинтиллион','дуоквадрагинтиллион','треквадрагинтиллион','кваторквадрагинтиллион',
            'квинквадрагинтиллион','сексквадрагинтиллион','септенквадрагинтиллион','октоквадрагинтиллион',
            'новемквадрагинтиллион','квинквагинтиллион','унквинкагинтиллион','дуоквинкагинтиллион',
            'треквинкагинтиллион','кваторквинкагинтиллион','квинквинкагинтиллион','сексквинкагинтиллион',
            'септенквинкагинтиллион','октоквинкагинтиллион','новемквинкагинтиллион','сексагинтиллион',
            'унсексагинтиллион','дуосексагинтиллион','тресексагинтиллион','кваторсексагинтиллион','квинсексагинтиллион',
            'секссексагинтиллион','септенсексагинтиллион','октосексагинтиллион','новемсексагинтиллион',
            'септагинтиллион','унсептагинтиллион','дуосептагинтиллион','тресептагинтиллион','кваторсептагинтиллион',
            'квинсептагинтиллион','секссептагинтиллион','септенсептагинтиллион','октосептагинтиллион',
            'новемсептагинтиллион','октогинтиллион','уноктогинтиллион','дуооктогинтиллион','треоктогинтиллион',
            'кватороктогинтиллион','квиноктогинтиллион','сексоктогинтиллион','септоктогинтиллион','октооктогинтиллион',
            'новемоктогинтиллион','нонагинтиллион','уннонагинтиллион','дуононагинтиллион','тренонагинтиллион',
            'кваторнонагинтиллион','квиннонагинтиллион','секснонагинтиллион','септеннонагинтиллион',
            'октононагинтиллион','новемнонагинтиллион','центиллион','анцентиллион','дуоцентиллион','трецентиллион',
            'кватторцентиллион','квинцентиллион','сексцентиллион','септемцентиллион','октоцентиллион','новемцентиллион',
            'децицентиллион'];

        self::$arrUnits = ['один ','два ','три ','четыре ','пять ','шесть ','семь ', 'восемь ', 'девять ','десять ',
            'одиннадцать ','двенадцать ','тринадцать ','четырнадцать ','пятнадцать ','шестнадцать ','семнадцать ',
            'восемнадцать ','девятнадцать '];

        self::$arrTens =  ['десять ','двадцать ','тридцать ','сорок ','пятьдесят ','шестьдесят ','семьдесят ',
                    'восемьдесят ','девяносто '];

        self::$arrHundreds =  ['сто ','двести ','триста ','четыреста ','пятьсот ',
                        'шестьсот ','семьсот ','восемьсот ','девятьсот '];

        self::$arrRegisters = ['рубль','рубля','рублей','тысяча ','тысячи ','тысяч '];

        self::$expSize = count(self::$arrExponents) + 1;
    }
    //@formatter:on
}
