<?php
declare(strict_types = 1);

namespace Converter\Number2Text;

use PHP\Math\BigInteger\BigInteger as BigInteger;

/**
 * Converts any numbers (up to 1e+303!) to their text representation e.g. 12 -> twelve (Russian only at the moment)
 * @author    Sergey Kanashin <goujon@mail.ru>
 * @copyright 2003-2017
 * @package   Converter v.1.1.3
 * @require   PHP 7.0+
 */
class Number2Text
{
    private $iNumber;
    private $currency;
    private $allArrays;
    private $arrUnits;
    private $arrTens;
    private $arrHundreds;
    private $arrExponents;
    private $arrRegisters;
    private $fullResult;
    private $zero = 'ноль ';
    //private $minus = 'минус ';

    public function __construct(string $number)
    {
        $this->iNumber = (string) new BigInteger($number);
        $this->allArrays = $this->loadArrays();

        list(
            $this->arrUnits, $this->arrTens, $this->arrHundreds, $this->arrExponents, $this->arrRegisters
            ) = $this->allArrays;
    }

    private function loadArrays(): array
    {
        $jsonFile = __DIR__ . DIRECTORY_SEPARATOR . "data.json";

        if (file_exists($jsonFile) && is_file($jsonFile)) {
            $data = file_get_contents($jsonFile);
            $this->allArrays = json_decode($data, true);
        } else {
            //TODO: Make error handling methods or class
            //throw new Exception('File data.json doesn\'t exist in the directory!');
        }

        return $this->allArrays;
    }

    public static function makeBignumber(int $value = 0, bool $generator = true): string
    {
        $mantissa = '';

        if ($generator && $value === 0) {
            $mantissa = mt_rand(1, 100);
            $value = mt_rand(1, 303);
        } elseif (!$generator) {
            $mantissa = '1';
        }

        $num = str_repeat('0', $value);

        return $mantissa . $num;
    }

    public function withCurrency(bool $show = true): bool
    {
        return $this->currency = $show;
    }

    public function num2txt(): string
    {
        $this->fullResult = '';
        $arrChunks = $this->makeChunks();
        $numGroups = count($arrChunks);

        if ($this->iNumber === '0') {
            $this->fullResult = $this->zero;
        }

        for ($i = $numGroups; $i >= 1; $i--) {
            $currChunk = $arrChunks[$i - 1];
            $this->fixArray($i);
            $preResult = null;
            $centum = (int)($currChunk / 100);
            $decem = $currChunk - $centum * 100;

            if ($centum >= 1) {
                $preResult .= $this->arrHundreds[$centum - 1];
            }
            if ($decem >= 1 && $decem <= 19) {
                $preResult .= $this->arrUnits[$decem - 1];
                $decem = 0;
            }
            if ($decem !== 0) {
                $preResult .= $this->arrTens[$decem / 10 - 1];
            }
            if ($decem % 10 !== 0) {
                $preResult .= $this->arrUnits[$decem % 10 - 1];
            }
            if ($currChunk != 0 || $i === 1) {
                $preResult .= $this->getRegister($i, $currChunk);
            }

            $this->fullResult .= $preResult;
        }

        return $this->fullResult;
    }

    private function makeChunks(): array
    {
        $arrCh = [];
        $rvrsValue = strrev((string)$this->iNumber);
        $rvrsSize = strlen($rvrsValue);

        for ($i = 0; $i < $rvrsSize; $i += 3) {
            $rvrsString = strrev(substr($rvrsValue, $i, 3));
            $arrCh[] = $rvrsString;
        }

        return $arrCh;
    }

    private function fixArray(int $fem): void
    {
        if ($fem === 2) {
            $this->arrUnits[0] = 'одна ';
            $this->arrUnits[1] = 'две ';
        } else {
            $this->arrUnits[0] = 'один ';
            $this->arrUnits[1] = 'два ';
        }
    }

    private function getRegister(int $chunkPos, string $chunkData): string
    {
        $subResult = '';
        $chunkLength = strlen($chunkData);
        $chunkUnits = substr($chunkData, -2);
        $lastDigit = (int)substr($chunkData, -1);
        $offset = abs($chunkPos - 3);
        $exponent = $this->arrExponents[$offset];

        if (!$this->currency && $chunkPos === 1) {
            return $subResult;
        }

        if ($chunkLength >= 2 && $chunkUnits >= 11 && $chunkUnits <= 14) {
            if ($chunkPos === 1 || $chunkPos === 2) {
                $subResult = $this->arrRegisters[$chunkPos ** 2 + 1];
            } else {
                $subResult = $exponent . 'ов '; //2
            }

            return $subResult;
        }

        if ($lastDigit === 1) {
            $subResult = $exponent . ' '; //0
        } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
            $subResult = $exponent . 'а '; //1
        } else {
            $subResult = $exponent . 'ов '; //2
        }

        if ($chunkPos === 1 || $chunkPos === 2) {
            $subResult = $this->getCase($chunkPos, $lastDigit);
        }

        return $subResult;
    }

    private function getCase(int $group, int $cond): string
    {
        $result = null;
        switch ($group) {
            case 1:
                if ($cond === 1) {
                    $result = 0;
                } elseif ($cond >= 2 && $cond <= 4) {
                    $result = 1;
                } else {
                    $result = 2;
                }
                break;
            case 2:
                if ($cond === 1) {
                    $result = 3;
                } elseif ($cond >= 2 && $cond <= 4) {
                    $result = 4;
                } else {
                    $result = 5;
                }
                break;
        }

        return $this->arrRegisters[$result];
    }
}
