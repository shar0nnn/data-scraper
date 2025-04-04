<?php

namespace App\Traits;

trait HasExportStats
{
    private int $fileRows = 0;
    private int $memoryUsage = 0;
    private float $executionTime = 0;

    public function beforeExport(): void
    {
        $this->executionTime = microtime(true);
    }

    public function afterSheet(): void
    {
        $this->memoryUsage = memory_get_peak_usage();
        $this->executionTime = microtime(true) - $this->executionTime;
    }

    public function getFileRows(): int
    {
        return $this->fileRows;
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
}
