<?php
declare(strict_types = 1);
namespace Converter\Demo;

use Converter\Init\Data;

class Generator
{
    public $exponent;
    public $sign;
    public $data;

    public function __construct()
    {
        $this->data = new Data();
    }

    /**
     * @param int|null $exponent - amount of gigits in resulting number
     * @param bool     $negative - set to true if you want negative number
     * @return string - generated number (can be safely passed to Number2Text class)
     */
    public function generate(int $exponent = null, bool $negative = false): string
    {
        $max = $this->data->getExpSize() * 3;
        $this->exponent = $exponent ?? mt_rand(1, $max);
        $this->sign = $negative ? '-' : '';
        $finalNumber = '';

        if ($this->exponent <= 0) {
            return "0";
        }

        for ($i = 1; $i <= $this->exponent; $i++) {
                $finalNumber .= mt_rand(1, 9);
        }

        return $this->sign . $finalNumber;
    }
}
