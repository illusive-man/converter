<?php
declare(strict_types = 1);

namespace Converter\Profiler;

class Profiler
{
    private $start;
    private $end;

    public function Start()
    {
        $this->start = microtime(true);
    }

    public function Stop()
    {
        $this->end = microtime(true) - $this->start;

        echo sprintf("%f\n", $this->end);
    }

}
