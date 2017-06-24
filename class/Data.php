<?php
declare(strict_types = 1);
namespace Converter\Init;

class Data
{
    public $arrExponents;
    public $arrUnits;
    public $arrTens;
    public $arrHundreds;
    public $arrRegisters;

    public function __construct()
    {
//@formatter:off
        $this->arrExponents = ['миллион', 'миллиард', 'триллион', 'квадриллион', 'квинтиллион', 'секстиллион',
            'септиллион', 'октиллион', 'нониллион', 'дециллион', 'ундециллион', 'дуодециллион', 'тредециллион',
            'кваттордециллион', 'квиндециллион', 'сексдециллион', 'септендециллион', 'октодециллион', 'новемдециллион',
            'вигинтиллион', 'унвигинтиллион', 'дуовигинтиллион', 'тревигинтиллион', 'кватторвигинтиллион',
            'квинвигинтиллион', 'сексвигинтиллион', 'септенвигинтиллион', 'октовигинтиллион', 'новемвигинтиллион',
            'тригинтиллион', 'унтригинтиллион', 'дуотригинтиллион', 'третригинтиллион', 'кватортригинтиллион',
            'квинтригинтиллион', 'секстригинтиллион', 'септентригинтиллион', 'октотригинтиллион', 'новемтригинтиллион',
            'квадрагинтиллион', 'унквадрагинтиллион', 'дуоквадрагинтиллион', 'треквадрагинтиллион',
            'кваторквадрагинтиллион', 'квинквадрагинтиллион', 'сексквадрагинтиллион', 'септенквадрагинтиллион',
            'октоквадрагинтиллион', 'новемквадрагинтиллион', 'квинквагинтиллион', 'унквинкагинтиллион',
            'дуоквинкагинтиллион', 'треквинкагинтиллион', 'кваторквинкагинтиллион', 'квинквинкагинтиллион',
            'сексквинкагинтиллион', 'септенквинкагинтиллион', 'октоквинкагинтиллион', 'новемквинкагинтиллион',
            'сексагинтиллион', 'унсексагинтиллион', 'дуосексагинтиллион', 'тресексагинтиллион', 'кваторсексагинтиллион',
            'квинсексагинтиллион', 'секссексагинтиллион', 'септенсексагинтиллион', 'октосексагинтиллион',
            'новемсексагинтиллион', 'септагинтиллион', 'унсептагинтиллион', 'дуосептагинтиллион', 'тресептагинтиллион',
            'кваторсептагинтиллион', 'квинсептагинтиллион', 'секссептагинтиллион', 'септенсептагинтиллион',
            'октосептагинтиллион', 'новемсептагинтиллион', 'октогинтиллион', 'уноктогинтиллион', 'дуооктогинтиллион',
            'треоктогинтиллион', 'кватороктогинтиллион', 'квиноктогинтиллион', 'сексоктогинтиллион',
            'септоктогинтиллион', 'октаоктогинтиллион', 'новемоктогинтиллион', 'нонагинтиллион', 'уннонагинтиллион',
            'дуононагинтиллион', 'тренонагинтиллион', 'кваторнонагинтиллион', 'квиннонагинтиллион',
            'секснонагинтиллион', 'септеннонагинтиллион', 'октононагинтиллион', 'новемнонагинтиллион', 'центиллион',
            'анцентиллион', 'дуоцентиллион', 'трецентиллион', 'кватторцентиллион', 'квинцентиллион', 'сексцентиллион',
            'септемцентиллион', 'октоцентиллион', 'новемцентиллион', 'децицентиллион'];

        $this->arrUnits = ['один ', 'два ', 'три ', 'четыре ', 'пять ', 'шесть ', 'семь ', 'восемь ', 'девять ',
            'десять ', 'одиннадцать ', 'двенадцать ', 'тринадцать ', 'четырнадцать ', 'пятнадцать ', 'шестнадцать ',
            'семнадцать ', 'восемнадцать ', 'девятнадцать '];

        $this->arrTens =  ['десять ', 'двадцать ', 'тридцать ', 'сорок ', 'пятьдесят ', 'шестьдесят ', 'семьдесят ',
                        'восемьдесят ', 'девяносто '];

        $this->arrHundreds =  ['сто ', 'двести ', 'триста ', 'четыреста ', 'пятьсот ',
                            'шестьсот ', 'семьсот ', 'восемьсот ', 'девятьсот '];

        $this->arrRegisters = ['рубль', 'рубля', 'рублей', 'тысяча ', 'тысячи ', 'тысяч '];
//@formatter:on
    }

    /**
     * @return int - number of elements + 1 in Exponents array.
     */
    public function getExpSize(): int
    {
        return count($this->arrExponents) + 1;
    }
}
