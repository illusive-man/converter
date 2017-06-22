<?php
declare(strict_types = 1);

namespace Converter;

use Converter\Tools\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneratorTest
 * @package Converter
 */
class GeneratorTest extends TestCase
{
    public function testGeneratePositiveNumberMethod()
    {
        $number = new Generator(9, 5);
        $bnum = $number->generate();
        $expected = "900000";
        $this->assertEquals($expected, $bnum);
    }

    public function testGenerateNegativeNumberMethod()
    {
        $number = new Generator(1, 10, true);
        $bnum = $number->generate();
        $expected = "-10000000000";
        $this->assertEquals($expected, $bnum);
    }

    public function testBigRandomNumberGeneratorReturnsNotNull()
    {
        $bnum = null;
        $number = new Generator();
        $bnum = $number->generate();
        $this->assertNotNull($bnum);
    }

    public function testGeneratorReturnsZero()
    {
        $number = new Generator(0);
        $bnum = $number->generate();
        $expected = "0";
        $this->assertEquals($expected, $bnum);
    }

    public function testCheckLengthOfGeneratedAgainstPropertyValue()
    {
        $number = new Generator();
        $bnum = $number->generate(true);
        $conc = strlen((string)$number->mantissa) + $number->exponent;
        $expected = strlen($bnum);
        $this->assertEquals($expected, $conc);
    }

    public function testZeroFirstArgumentAndIgnoreOtherCheck()
    {
        $number = new Generator(0, 15, true);
        $bnum = $number->generate(false);
        $expected = "0";
        $this->assertEquals($expected, $bnum);
    }
}
