<?php
declare(strict_types = 1);

namespace ConverterTest;

use PHPUnit\Framework\TestCase;
use Converter\Core\Number2Text;

class FunctionalTests extends TestCase
{
    public function testIfAutoloadFileExists()
    {
        $autoldFile = VENDOR_DIR . '/autoload.php';
        $this->assertFileExists($autoldFile);
    }

    public function testSimpleNumber()
    {
        $number = new Number2Text('120');
        $expected = "сто двадцать ";
        $this->assertEquals($expected, $number->convert());
    }

    public function testSimpleNegativeNumber()
    {
        $num = '-100';
        $number = new Number2Text($num);
        $expected = "минус сто ";
        $this->assertEquals($expected, $number->convert());
    }

    public function testSimpleNegativeNumberWithCurrency()
    {
        $num = '-905';
        $number = new Number2Text($num);
        $number->currency(true);
        $expected = "минус девятьсот пять рублей";
        $this->assertEquals($expected, $number->convert());
    }

    public function testZeroNumber()
    {
        $number = new Number2Text('0');
        $expected = "ноль ";
        $this->assertEquals($expected, $number->convert());
    }

    public function testNegaitiveZeroNumberCheck()
    {
        $number = new Number2Text('-0');
        $expected = "ноль ";
        $this->assertEquals($expected, $number->convert());
    }

    public function testZeroNumberWithCurrency()
    {
        $number = new Number2Text('0');
        $number->currency(true);
        $expected = "ноль рублей";
        $this->assertEquals($expected, $number->convert());
    }

    public function testNegativeZeroNumberWithCurrency()
    {
        $number = new Number2Text('-0');
        $number->currency(true);
        $expected = "ноль рублей";
        $this->assertEquals($expected, $number->convert());
    }

    public function testSimpleRegistersInThousandsAndUnits()
    {
        $number = new Number2Text('12014');
        $expected = "двенадцать тысяч четырнадцать ";
        $this->assertEquals($expected, $number->convert());
    }

    public function testBigShortNumberWithConditionForMillionsCheck()
    {
        $number = new Number2Text('100000000000000032000012');
        $expected = "сто секстиллионов тридцать два миллиона двенадцать ";
        $this->assertEquals($expected, $number->convert());
    }

    public function testBoundaryErraneousNumberCheck()
    {
        $number = new Number2Text('1101111003010');
        $expected = "один триллион сто один миллиард сто одиннадцать миллионов три тысячи десять ";
        $this->assertEquals($expected, $number->convert());
    }

    public function testReturnsNumberWithCurrency1()
    {
        $num = '108451';
        $number = new Number2Text($num);
        $number->currency(true);
        $expected = "сто восемь тысяч четыреста пятьдесят один рубль";
        $this->assertEquals($expected, $number->convert());
    }

    public function testReturnsNumberWithCurrency2()
    {
        $num = '111002';
        $number = new Number2Text($num);
        $number->currency(true);
        $expected = "сто одиннадцать тысяч два рубля";
        $this->assertEquals($expected, $number->convert());
    }

    public function testReturnsNumberWithCurrency()
    {
        $num = '101235';
        $number = new Number2Text($num);
        $number->currency(true);
        $expected = "сто одна тысяча двести тридцать пять рублей";
        $this->assertEquals($expected, $number->convert());
    }

    public function testHugeNumberConversion()
    {
        $huge = '974580005510369432002001222020215165416516206516516206505749846541631650650124588745510369432002001222
        020215165416516206516516206505749846541631651065012458874551036943124325987941651620651651620650574984654163165
        000515054855106501245887455103694320020012220202151654165162065165162065057498465416316510650';
        $number = new Number2Text($huge);
        $expected = <<<EOD
девяносто семь септемцентиллионов четыреста пятьдесят восемь сексцентиллионов пятьсот пятьдесят один кватторцентиллион 
тридцать шесть трецентиллионов девятьсот сорок три дуоцентиллиона двести анцентиллионов двести центиллионов сто 
двадцать два новемнонагинтиллиона двести два октононагинтиллиона двадцать один септеннонагинтиллион пятьсот шестнадцать 
секснонагинтиллионов пятьсот сорок один квиннонагинтиллион шестьсот пятьдесят один кваторнонагинтиллион шестьсот 
двадцать тренонагинтиллионов шестьсот пятьдесят один дуононагинтиллион шестьсот пятьдесят один уннонагинтиллион 
шестьсот двадцать нонагинтиллионов шестьсот пятьдесят новемоктогинтиллионов пятьсот семьдесят четыре 
октаоктогинтиллиона девятьсот восемьдесят четыре септоктогинтиллиона шестьсот пятьдесят четыре сексоктогинтиллиона сто 
шестьдесят три квиноктогинтиллиона сто шестьдесят пять кватороктогинтиллионов шестьдесят пять треоктогинтиллионов 
двенадцать дуооктогинтиллионов четыреста пятьдесят восемь уноктогинтиллионов восемьсот семьдесят четыре октогинтиллиона 
пятьсот пятьдесят один новемсептагинтиллион тридцать шесть октосептагинтиллионов девятьсот сорок три 
септенсептагинтиллиона двести секссептагинтиллионов двести квинсептагинтиллионов сто двадцать два 
кваторсептагинтиллиона два тресептагинтиллиона двести два новемсексагинтиллиона сто пятьдесят один октосексагинтиллион 
шестьсот пятьдесят четыре септенсексагинтиллиона сто шестьдесят пять секссексагинтиллионов сто шестьдесят два 
квинсексагинтиллиона шестьдесят пять кваторсексагинтиллионов сто шестьдесят пять тресексагинтиллионов сто шестьдесят 
два дуосексагинтиллиона шестьдесят пять унсексагинтиллионов пятьдесят семь сексагинтиллионов четыреста девяносто восемь 
новемквинкагинтиллионов четыреста шестьдесят пять октоквинкагинтиллионов четыреста шестнадцать септенквинкагинтиллионов 
триста шестнадцать сексквинкагинтиллионов пятьсот десять квинквинкагинтиллионов шестьсот пятьдесят 
кваторквинкагинтиллионов сто двадцать четыре треквинкагинтиллиона пятьсот восемьдесят восемь дуоквинкагинтиллионов 
семьсот сорок пять унквинкагинтиллионов пятьсот десять квинквагинтиллионов триста шестьдесят девять 
новемквадрагинтиллионов четыреста тридцать один октоквадрагинтиллион двести сорок три септенквадрагинтиллиона двести 
пятьдесят девять сексквадрагинтиллионов восемьсот семьдесят девять квинквадрагинтиллионов четыреста шестнадцать 
кваторквадрагинтиллионов пятьсот шестнадцать треквадрагинтиллионов двести шесть дуоквадрагинтиллионов пятьсот 
шестнадцать унквадрагинтиллионов пятьсот шестнадцать квадрагинтиллионов двести шесть новемтригинтиллионов пятьсот пять 
октотригинтиллионов семьсот сорок девять септентригинтиллионов восемьсот сорок шесть секстригинтиллионов пятьсот сорок 
один квинтригинтиллион шестьсот тридцать один кватортригинтиллион шестьдесят пять третригинтиллионов пятьсот пятнадцать 
октовигинтиллионов пятьдесят четыре септенвигинтиллиона восемьсот пятьдесят пять сексвигинтиллионов сто шесть 
квинвигинтиллионов пятьсот один кватторвигинтиллион двести сорок пять тревигинтиллионов восемьсот восемьдесят семь 
дуовигинтиллионов четыреста пятьдесят пять унвигинтиллионов сто три вигинтиллиона шестьсот девяносто четыре 
новемдециллиона триста двадцать октодециллионов двадцать септендециллионов двенадцать сексдециллионов двести двадцать 
квиндециллионов двести два кваттордециллиона сто пятьдесят один тредециллион шестьсот пятьдесят четыре дуодециллиона 
сто шестьдесят пять ундециллионов сто шестьдесят два дециллиона шестьдесят пять нониллионов сто шестьдесят пять 
октиллионов сто шестьдесят два септиллиона шестьдесят пять секстиллионов пятьдесят семь квинтиллионов четыреста 
девяносто восемь квадриллионов четыреста шестьдесят пять триллионов четыреста шестнадцать миллиардов триста шестнадцать 
миллионов пятьсот десять тысяч шестьсот пятьдесят 
EOD;
        $this->assertEquals(strtr($expected, ["\r\n" => ""]), $number->convert());
    }

    public function testCurrencyMethodReturnsTrue()
    {
        $number = new Number2Text('10');
        $this->assertTrue($number->currency(true));
    }

    public function testCurrencyMethodReturnsFalse()
    {
        $number = new Number2Text('154');
        $this->assertFalse($number->currency());
    }
}
