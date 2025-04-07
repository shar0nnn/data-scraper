<?php

namespace App\Services;

use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;

class SpreadsheetService implements WithEvents
{
    use RegistersEventListeners;

    public function __construct(
        protected int   $memoryUsage = 0,
        protected float $executionTime = 0,
        protected int   $rowNumber = 0,
        protected int   $skipRows = 0
    )
    {
    }

    protected function beforeProcessing(): void
    {
        $this->executionTime = microtime(true);
    }

    protected function afterProcessing(): void
    {
        $this->memoryUsage = memory_get_peak_usage();
        $this->executionTime = microtime(true) - $this->executionTime;
    }

    public function getMemoryUsage(): string
    {
        $memoryUsage = round($this->memoryUsage / 1024, 2);

        return "$memoryUsage KB";
    }

    public function getExecutionTime(): string
    {
        $executionTime = round($this->executionTime, 2);

        return "$executionTime seconds";
    }

    public function getRowNumber(): int
    {
        return $this->rowNumber;
    }
}
