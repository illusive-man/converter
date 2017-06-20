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
    public $Sign;

    /**
     * Generator constructor. Use or generate arguments to prepare mantissa and exponent for generator() method.
     * Working formula: A•10^B.
     * @param int  $mantissa -> A - If A = 0, output is 0 as well (no matter what other arguments are).
     * @param int  $exponent -> B - If A = 0, this param will be ignored
     * @param bool $negative -> Creates negative number if set to true. (If A = 0, this param will be ignored).
     * @throws \Exception
     */
    public function __construct(int $mantissa = -1, int $exponent = -1, bool $negative = false)
    {
        Number2Text::loadAllData();
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
     * @param bool $zeroFill - If set to true, generates random number with trailing zeroes (e.g. 50000000),
     *        otherwise generates random natural number (e.g. 863154525).
     * @return string - Contains given or random signed number from X•e-333 to X•e+333
     */
    public function generate(bool $zeroFill = true): string
    {
        if ($this->mantissa === 0) {
            return '0';
        }

        $finalNumber = '';
        $base = $this->mantissa;
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
            $base = '';
        }
        return $this->Sign . $base . $finalNumber;
    }

    private function printExponent(): void
    {

    }
}
