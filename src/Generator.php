<?php
declare(strict_types = 1);
namespace Converter\Demo;

use Converter\Init\Data;

class Generator
{
    public $exponent;
    public $data;

    public function __construct()
    {
        $this->data = new Data();
    }

    public function generate(int $exponent = null): string
    {
        $max = $this->data->getExpSize() * 3;
        $this->exponent = $exponent ?? mt_rand(1, $max);

        if ($this->exponent <= 0) {
            return "0";
        }

        $digits = [];
        for ($i = 1; $i <= $this->exponent; $i++) {
            $digits[] = mt_rand(0, 9);
        }

        $digits[0] == 0 ? mt_rand(1, 9) : $digits[0];
        return implode('', $digits);
    }
}
