<?php
declare(strict_types = 1);

namespace Converter;

use Converter\Demo\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneratorTest
 * @package Converter
 */
class GeneratorTest extends TestCase
{
    public function testGeneratePositiveNumberMethod()
    {
        $bnum = Generator::generate(9, 5);
        $expected = "900000";
        $this->assertEquals($expected, $bnum);
    }

    public function testGenerateNegativeNumberMethod()
    {
        $bnum = Generator::generate(1, 10, true);
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
        $bnum = Generator::generate(0);
        $expected = "0";
        $this->assertEquals($expected, $bnum);
    }

    public function testCheckLengthOfGeneratedAgainstPropertyValue()
    {
        $bnum = Generator::generate(null, null, false, true);
        $conc = strlen((string)Generator::$mantissa) + Generator::$exponent;
        $expected = strlen($bnum);
        $this->assertEquals($expected, $conc);
    }

    public function testZeroFirstArgumentAndIgnoreOtherCheck()
    {
        $bnum = Generator::generate(0, 15, true, false);
        $expected = "0";
        $this->assertEquals($expected, $bnum);
    }
}
