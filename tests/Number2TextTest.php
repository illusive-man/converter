<?php
declare(strict_types = 1);

namespace Converter\Unit;

use Converter\Core\Number2Text;
use PHPUnit\Framework\TestCase;

class Number2TextTest extends TestCase
{
    /**
     * Instance of Number2Text class
     *
     * @var object
     */
    public $handle;

    protected function setUp()
    {
        $this->handle = new Number2Text();
    }

    public function testIfAutoloadFileExists()
    {
        $autoldFile = VENDOR_DIR . '/autoload.php';
        $this->assertFileExists($autoldFile);
    }

    public function testBundleForUnits()
    {
        $units = [
            0 => 'ноль ',
            1 => 'один ',
            2 => 'два ',
            3 => 'три ',
            4 => 'четыре ',
            5 => 'пять ',
            6 => 'шесть ',
            7 => 'семь ',
            8 => 'восемь ',
            9 => 'девять '
        ];
        foreach ($units as $number => $word) {
            $this->assertEquals($word, $this->handle->convert((string)$number));
        }
    }

    public function testBundleForTens()
    {
        $tens = [
            10 => 'десять ',
            11 => 'одиннадцать ',
            12 => 'двенадцать ',
            13 => 'тринадцать ',
            14 => 'четырнадцать ',
            15 => 'пятнадцать ',
            16 => 'шестнадцать ',
            17 => 'семнадцать ',
            18 => 'восемнадцать ',
            19 => 'девятнадцать ',
            20 => 'двадцать ',
            21 => 'двадцать один ',
            24 => 'двадцать четыре ',
            39 => 'тридцать девять ',
            45 => 'сорок пять ',
            52 => 'пятьдесят два ',
            61 => 'шестьдесят один ',
            77 => 'семьдесят семь ',
            80 => 'восемьдесят ',
            93 => 'девяносто три ',
        ];
        foreach ($tens as $number => $word) {
            $this->assertEquals($word, $this->handle->convert((string)$number));
        }
    }

    public function testBundleForHundreds()
    {
        $hundreds = [
            100 => 'сто ',
            101 => 'сто один ',
            199 => 'сто девяносто девять ',
            203 => 'двести три ',
            287 => 'двести восемьдесят семь ',
            300 => 'триста ',
            356 => 'триста пятьдесят шесть ',
            410 => 'четыреста десять ',
            434 => 'четыреста тридцать четыре ',
            578 => 'пятьсот семьдесят восемь ',
            689 => 'шестьсот восемьдесят девять ',
            729 => 'семьсот двадцать девять ',
            894 => 'восемьсот девяносто четыре ',
            999 => 'девятьсот девяносто девять '
        ];
        foreach ($hundreds as $number => $word) {
            $this->assertEquals($word, $this->handle->convert((string)$number));
        }
    }

    public function testBundleForNumbersWithCurrency()
    {
        $testarr = [
            '0' => "ноль рублей",
            '905'=> "девятьсот пять рублей",
            '12014' => "двенадцать тысяч четырнадцать рублей",
            '108451'=> "сто восемь тысяч четыреста пятьдесят один рубль",
            '111002' => "сто одиннадцать тысяч два рубля",
            '801235' => "восемьсот одна тысяча двести тридцать пять рублей"
        ];
        $this->handle->currency();
        foreach ($testarr as $number => $word) {
            $this->assertEquals($word, $this->handle->convert((string)$number));
        }
    }

    public function testNumberWithMillionsCheck()
    {
        $actual = $this->handle->convert('100000000000000032001012');
        $expected = "сто секстиллионов тридцать два миллиона одна тысяча двенадцать ";
        $this->assertEquals($expected, $actual);
    }

    public function testBoundaryGroupErraneousNumberCheck()
    {
        $actual = $this->handle->convert('1101111003010');
        $expected = "один триллион сто один миллиард сто одиннадцать миллионов три тысячи десять ";
        $this->assertEquals($expected, $actual);
    }

    public function testHugeNumberConversion()
    {
        $huge = <<<EOD
974580005510369432002001222020215165416516206516516206505749846541631650650124588745510369432002001222
020215165416516206516516206505749846541631651065012458874551036943124325987941651620651651620650574984654163165
000515054855106501245887455103694320020012220202151654165162065165162065057498465416316510650
EOD;
        $huge = (strtr($huge, ["\r\n" => '']));
        $actual = $this->handle->convert($huge);
        $expected = <<<EOD
девятьсот семьдесят четыре центиллиона пятьсот восемьдесят новемнонагинтиллионов пять октононагинтиллионов пятьсот 
десять септеннонагинтиллионов триста шестьдесят девять секснонагинтиллионов четыреста тридцать два квиннонагинтиллиона 
два кваторнонагинтиллиона один тренонагинтиллион двести двадцать два дуононагинтиллиона двадцать уннонагинтиллионов 
двести пятнадцать нонагинтиллионов сто шестьдесят пять новемоктогинтиллионов четыреста шестнадцать октаоктогинтиллионов 
пятьсот шестнадцать септоктогинтиллионов двести шесть сексоктогинтиллионов пятьсот шестнадцать квиноктогинтиллионов 
пятьсот шестнадцать кватороктогинтиллионов двести шесть треоктогинтиллионов пятьсот пять дуооктогинтиллионов семьсот 
сорок девять уноктогинтиллионов восемьсот сорок шесть октогинтиллионов пятьсот сорок один новемсептагинтиллион шестьсот 
тридцать один октосептагинтиллион шестьсот пятьдесят септенсептагинтиллионов шестьсот пятьдесят секссептагинтиллионов 
сто двадцать четыре квинсептагинтиллиона пятьсот восемьдесят восемь кваторсептагинтиллионов семьсот сорок пять 
тресептагинтиллионов пятьсот десять дуосептагинтиллионов триста шестьдесят девять унсептагинтиллионов четыреста 
тридцать два септагинтиллиона два новемсексагинтиллиона один октосексагинтиллион двести двадцать два 
септенсексагинтиллиона двадцать секссексагинтиллионов двести пятнадцать квинсексагинтиллионов сто шестьдесят пять 
кваторсексагинтиллионов четыреста шестнадцать тресексагинтиллионов пятьсот шестнадцать дуосексагинтиллионов двести 
шесть унсексагинтиллионов пятьсот шестнадцать сексагинтиллионов пятьсот шестнадцать новемквинкагинтиллионов двести 
шесть октоквинкагинтиллионов пятьсот пять септенквинкагинтиллионов семьсот сорок девять сексквинкагинтиллионов 
восемьсот сорок шесть квинквинкагинтиллионов пятьсот сорок один кваторквинкагинтиллион шестьсот тридцать один 
треквинкагинтиллион шестьсот пятьдесят один дуоквинкагинтиллион шестьдесят пять унквинкагинтиллионов двенадцать 
квинквагинтиллионов четыреста пятьдесят восемь новемквадрагинтиллионов восемьсот семьдесят четыре октоквадрагинтиллиона 
пятьсот пятьдесят один септенквадрагинтиллион тридцать шесть сексквадрагинтиллионов девятьсот сорок три 
квинквадрагинтиллиона сто двадцать четыре кваторквадрагинтиллиона триста двадцать пять треквадрагинтиллионов девятьсот 
восемьдесят семь дуоквадрагинтиллионов девятьсот сорок один унквадрагинтиллион шестьсот пятьдесят один квадрагинтиллион 
шестьсот двадцать новемтригинтиллионов шестьсот пятьдесят один октотригинтиллион шестьсот пятьдесят один 
септентригинтиллион шестьсот двадцать секстригинтиллионов шестьсот пятьдесят квинтригинтиллионов пятьсот семьдесят 
четыре кватортригинтиллиона девятьсот восемьдесят четыре третригинтиллиона шестьсот пятьдесят четыре дуотригинтиллиона 
сто шестьдесят три унтригинтиллиона сто шестьдесят пять тригинтиллионов пятьсот пятнадцать октовигинтиллионов пятьдесят 
четыре септенвигинтиллиона восемьсот пятьдесят пять сексвигинтиллионов сто шесть квинвигинтиллионов пятьсот один 
кватторвигинтиллион двести сорок пять тревигинтиллионов восемьсот восемьдесят семь дуовигинтиллионов четыреста 
пятьдесят пять унвигинтиллионов сто три вигинтиллиона шестьсот девяносто четыре новемдециллиона триста двадцать 
октодециллионов двадцать септендециллионов двенадцать сексдециллионов двести двадцать квиндециллионов двести два 
кваттордециллиона сто пятьдесят один тредециллион шестьсот пятьдесят четыре дуодециллиона сто шестьдесят пять 
ундециллионов сто шестьдесят два дециллиона шестьдесят пять нониллионов сто шестьдесят пять октиллионов сто шестьдесят 
два септиллиона шестьдесят пять секстиллионов пятьдесят семь квинтиллионов четыреста девяносто восемь квадриллионов 
четыреста шестьдесят пять триллионов четыреста шестнадцать миллиардов триста шестнадцать миллионов пятьсот десять тысяч 
шестьсот пятьдесят 
EOD;
        $this->assertEquals(strtr($expected, ["\r\n" => ""]), $actual);
    }
}
