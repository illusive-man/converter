<?php
declare(strict_types = 1);

namespace Converter\Tools;

class Profiler
{
    private $start;
    private $end;

    public function proStart()
    {
        return $this->start = microtime(true);
    }

    public function proStop()
    {
        return $this->end = microtime(true) - $this->start;
    }

}
