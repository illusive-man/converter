<?php
declare(strict_types = 1);

namespace Converter;

use PHPUnit\Framework\TestCase;
use Converter\Core\Number2Text;

class FunctionalTests extends TestCase
{
    public function testIfJsonFileExists()
    {
        $jsonFile = CONV_CLASS_DIR . '/data.json';
        $this->assertFileExists($jsonFile);
    }

    public function testIfAutoloadFileExists()
    {
        $autoldFile = VENDOR_DIR . '/autoload.php';
        $this->assertFileExists($autoldFile);
    }

//    public function testReturnsLoadedArraysCountIs5()
//    {
//        $number = new Number2Text('2');
//        $this->assertTrue((count($number->allArrays)) == 5);
//    }

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
        $number->withCurrency();
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
        $number->withCurrency();
        $expected = "ноль рублей";
        $this->assertEquals($expected, $number->convert());
    }

    public function testNegativeZeroNumberWithCurrency()
    {
        $number = new Number2Text('-0');
        $number->withCurrency();
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
        $number->withCurrency();
        $expected = "сто восемь тысяч четыреста пятьдесят один рубль";
        $this->assertEquals($expected, $number->convert());
    }

    public function testReturnsNumberWithCurrency2()
    {
        $num = '111002';
        $number = new Number2Text($num);
        $number->withCurrency();
        $expected = "сто одиннадцать тысяч два рубля";
        $this->assertEquals($expected, $number->convert());
    }

    public function testReturnsNumberWithCurrency()
    {
        $num = '101235';
        $number = new Number2Text($num);
        $number->withCurrency();
        $expected = "сто одна тысяча двести тридцать пять рублей";
        $this->assertEquals($expected, $number->convert());
    }

    public function testMaxNumberConversion()
    {
        $huge = '974580005510369432002001222020215165416516206516516206505749846541631650650124588745510369432002001222
        020215165416516206516516206505749846541631651065012458874551036943124325987941651620651651620650574984654163165
        000515054855106501245887455103694320020012220202151654165162065165162065057498465416316510650';
        $number = new Number2Text($huge);
        $expected = <<<EOD
девятьсот семьдесят четыре центиллиона пятьсот восемьдесят новемнонагинтиллионов пять 
октононагинтиллионов пятьсот десять септеннонагинтиллионов триста шестьдесят девять секснонагинтиллионов 
четыреста тридцать два квиннонагинтиллиона два кваторнонагинтиллиона один тренонагинтиллион двести двадцать 
два дуононагинтиллиона двадцать уннонагинтиллионов двести пятнадцать нонагинтиллионов сто шестьдесят пять 
новемоктогинтиллионов четыреста шестнадцать октооктогинтиллионов пятьсот шестнадцать септоктогинтиллионов 
двести шесть сексоктогинтиллионов пятьсот шестнадцать квиноктогинтиллионов пятьсот шестнадцать кватороктогинтиллионов 
двести шесть треоктогинтиллионов пятьсот пять дуооктогинтиллионов семьсот сорок девять уноктогинтиллионов восемьсот 
сорок шесть октогинтиллионов пятьсот сорок один новемсептагинтиллион шестьсот тридцать один октосептагинтиллион 
шестьсот пятьдесят септенсептагинтиллионов шестьсот пятьдесят секссептагинтиллионов сто двадцать четыре 
квинсептагинтиллиона пятьсот восемьдесят восемь кваторсептагинтиллионов семьсот сорок пять тресептагинтиллионов пятьсот 
десять дуосептагинтиллионов триста шестьдесят девять унсептагинтиллионов четыреста тридцать два септагинтиллиона два 
новемсексагинтиллиона один октосексагинтиллион двести двадцать два септенсексагинтиллиона двадцать 
секссексагинтиллионов двести пятнадцать квинсексагинтиллионов сто шестьдесят пять кваторсексагинтиллионов четыреста 
шестнадцать тресексагинтиллионов пятьсот шестнадцать дуосексагинтиллионов двести шесть унсексагинтиллионов пятьсот 
шестнадцать сексагинтиллионов пятьсот шестнадцать новемквинкагинтиллионов двести шесть октоквинкагинтиллионов пятьсот 
пять септенквинкагинтиллионов семьсот сорок девять сексквинкагинтиллионов восемьсот сорок шесть квинквинкагинтиллионов 
пятьсот сорок один кваторквинкагинтиллион шестьсот тридцать один треквинкагинтиллион шестьсот пятьдесят один 
дуоквинкагинтиллион шестьдесят пять унквинкагинтиллионов двенадцать квинквагинтиллионов четыреста пятьдесят восемь 
новемквадрагинтиллионов восемьсот семьдесят четыре октоквадрагинтиллиона пятьсот пятьдесят один септенквадрагинтиллион 
тридцать шесть сексквадрагинтиллионов девятьсот сорок три квинквадрагинтиллиона сто двадцать четыре 
кваторквадрагинтиллиона триста двадцать пять треквадрагинтиллионов девятьсот восемьдесят семь дуоквадрагинтиллионов 
девятьсот сорок один унквадрагинтиллион шестьсот пятьдесят один квадрагинтиллион шестьсот двадцать новемтригинтиллионов 
шестьсот пятьдесят один октотригинтиллион шестьсот пятьдесят один септентригинтиллион шестьсот двадцать 
секстригинтиллионов шестьсот пятьдесят квинтригинтиллионов пятьсот семьдесят четыре кватортригинтиллиона девятьсот 
восемьдесят четыре третригинтиллиона шестьсот пятьдесят четыре дуотригинтиллиона сто шестьдесят три унтригинтиллиона 
сто шестьдесят пять тригинтиллионов пятьсот пятнадцать октовигинтиллионов пятьдесят четыре септенвигинтиллиона 
восемьсот пятьдесят пять сексвигинтиллионов сто шесть квинвигинтиллионов пятьсот один кватуорвигинтиллион двести 
сорок пять тревигинтиллионов восемьсот восемьдесят семь дуовигинтиллионов четыреста пятьдесят пять унвигинтиллионов 
сто три вигинтиллиона шестьсот девяносто четыре новемдециллиона триста двадцать октодециллионов двадцать 
септендециллионов двенадцать сексдециллионов двести двадцать квиндециллионов двести два кватуордециллиона сто пятьдесят 
один тредециллион шестьсот пятьдесят четыре дуодециллиона сто шестьдесят пять ундециллионов сто шестьдесят два 
дециллиона шестьдесят пять нониллионов сто шестьдесят пять октиллионов сто шестьдесят два септиллиона шестьдесят 
пять секстиллионов пятьдесят семь квинтиллионов четыреста девяносто восемь квадриллионов четыреста шестьдесят пять 
триллионов четыреста шестнадцать миллиардов триста шестнадцать миллионов пятьсот десять тысяч шестьсот пятьдесят 
EOD;
        $this->assertEquals(strtr($expected, ["\r\n" => ""]), $number->convert());
    }

    public function testCurrencyMethodReturnsTrue()
    {
        $number = new Number2Text('10');
        $this->assertTrue($number->withCurrency());
    }

    public function testCurrencyMethodReturnsFalse()
    {
        $number = new Number2Text('154');
        $this->assertFalse($number->withCurrency(false));
    }
}
