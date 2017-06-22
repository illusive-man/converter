<?php
declare(strict_types = 1);

namespace Converter\Tools;

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

        echo 'Execution time (including PSR-4 files autoload): '. sprintf("%01.3f", $this->end) . ' sec.';
    }

}
