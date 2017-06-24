<?php
declare(strict_types = 1);

namespace Converter\Demo;

//use Converter\Init\Data;

/**
 * Class Generator - Creates demo numbers (max = arrExponent array length * 3) for testing Number2Text class
 * @package Converter\Generator
 */
class Generator
{
    public static $mantissa;
    public static $exponent;
    public static $sign;

    /**
     * Method that creates big number itself, according to options set in constructor method.
     * @param int  $mantissa
     * @param int  $exponent
     * @param bool $negative
     * @param bool $zeroFill - If set to true, generates random number with trailing zeroes (e.g. 50000000),
     *                       otherwise generates random natural number (e.g. 863154525).
     * @return string - Contains given or random signed number from X•e-333 to X•e+333
     */
    public static function generate(
        int $mantissa = null,
        int $exponent = null,
        bool $negative = false,
        bool $zeroFill = true
    ): string {

        self::$mantissa = $mantissa ?? mt_rand(0, 99);
        self::$exponent = $exponent ?? mt_rand(1, 333);  //Number2Text::$expSize * 3

        if ($negative) {
            self::$sign = '-';
        } else {
            self::$sign = '';
        }

        if (self::$mantissa === 0) {
            return '0';
        }

        $finalNumber = '';
        $base = self::$mantissa;
        if ($zeroFill) {
            $finalNumber = str_repeat('0', self::$exponent);
        } else {
            for ($i = 0; $i <= self::$exponent; $i++) {
                if ($i == 0) {
                    $finalNumber .= mt_rand(1, 9);
                } else {
                    $finalNumber .= mt_rand(0, 9);
                }
            }
            $base = '';
        }
        return self::$sign . $base . $finalNumber;
    }
}
