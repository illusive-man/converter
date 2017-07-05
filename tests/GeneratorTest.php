<?php
declare(strict_types = 1);

namespace Converter;

use Converter\Demo\Generator;
use Converter\Init\Data;
use Converter\Tools\Profiler;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneratorTest
 * @package Converter
 */
class GeneratorTests extends TestCase
{
    public $handle;
    public function setUp()
    {
        $this->handle = new Generator();
    }

    public function testGeneratePositiveNumberMethod()
    {
        $bnum = strlen($this->handle->generate(958));
        $this->assertEquals(958, $bnum);
    }

    public function testGenerateNegativeNumberMethod()
    {
        $bnum = strlen($this->handle->generate(100));
        $this->assertEquals(100, $bnum);
    }

    public function testBigRandomNumberGeneratorReturnsNotNull()
    {
        $bnum = null;
        $bnum = $this->handle->generate();
        $this->assertNotNull($bnum);
    }

    public function testGeneratorReturnsZero()
    {
        $bnum = $this->handle->generate(0);
        $expected = "0";
        $this->assertEquals($expected, $bnum);
    }

    public function testCheckLengthOfGeneratedAgainstPropertyValue1()
    {
        $expected = strlen($this->handle->generate());
        $actual = $this->handle->exponent;
        $this->assertEquals($expected, $actual);
    }

    public function testZeroFirstArgumentAndIgnoreOtherCheck()
    {
        $bnum = $this->handle->generate(0, true);
        $expected = "0";
        $this->assertEquals($expected, $bnum);
    }

    public function testNegativeNumberAsFirstArgumentReturnsZero()
    {
        $bnum = $this->handle->generate(-758, true);
        $expected = "0";
        $this->assertEquals($expected, $bnum);
    }

    public function testDataClassReturnsExponentsSize()
    {
        $inst = new Data();
        $data = $inst->getExpSize();
        $expected = count($inst->arrExponents) - 2;
        $this->assertEquals($expected, $data);
    }

    public function testProfilerClassMethod1()
    {
        $inst = new Profiler();
        $start = $inst->proStart();
        $stop = $inst->proStop();
        $this->assertGreaterThan($stop, $start);
    }

}
