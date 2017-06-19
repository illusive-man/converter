<?php
declare(strict_types = 1);

namespace Converter\Generator;

use Converter\Core\Number2Text;

/**
 * Class Generator - Creates demo numbers (max = arrExponent array length * 3) for testing Number2Text class
 * @package Converter\Generator
 */
class Generator
{
    public $mantissa;
    public $exponent;
    private $Sign;

    /**
     * Generator constructor. Use or generate arguments to prepare mantissa and exponent for generator() method.
     * Working formula: A•10^B. If A = 0, output is 0 as well (no matter what other arguments are).
     * @param int  $mantissa -> A
     * @param int  $exponent -> B
     * @param bool $negative -> Creates negative number if set to true
     * @throws \Exception
     */
    public function __construct(int $mantissa = -1, int $exponent = -1, bool $negative = false)
    {
        Number2Text::initConfig();
        if ($mantissa === -1 && $exponent === -1) {
            $this->mantissa = mt_rand(0, 99);
            $this->exponent = mt_rand(1, Number2Text::$expSize * 3);
        } else {
            $this->mantissa = $mantissa;
            $this->exponent = $exponent;
        }

        if ($negative) {
            $this->Sign = '-';
        } else {
            $this->Sign = '';
        }
    }

    /**
     * Method that creates big number itself, according to options set in constructor method.
     * @param bool $zeroFill - If true, generates number with trailing zeroes (e.g. 50000000),
     *        otherwise generates random natural number (e.g. 563154525) of given length.
     * @return string - Resulting signed number (Should/can serve as input number for Number2Text class.)
     */
    public function generate(bool $zeroFill = true): string
    {
        if ($this->mantissa === 0) {
            return '0';
        }

        $finalNumber = '';
        if ($zeroFill) {
            $filler = '0';
            $finalNumber = str_repeat($filler, $this->exponent);
        } else {
            for ($i = 0; $i <= $this->exponent; $i++) {
                if ($i == 0) {
                    $finalNumber .= mt_rand(1, 9); //TODO: $finalNumber = implode('', $arr)
                } else {
                    $finalNumber .= mt_rand(0, 9);
                }
            }
        }
        return $this->Sign . $this->mantissa . $finalNumber;
    }
}
