<?php

namespace App\Traits;

trait HasImportStats
{
    private int $fileRows = 0;
    private int $dbRows = 0;
    private int $memoryUsage = 0;
    private float $executionTime = 0;

    public function beforeImport(): void
    {
        $this->executionTime = microtime(true);
    }

    public function afterImport(): void
    {
        $this->memoryUsage = memory_get_peak_usage();
        $this->executionTime = microtime(true) - $this->executionTime;
    }

    public function getFileRows(): int
    {
        return $this->fileRows;
    }

    public function getDBRows(): int
    {
        return $this->dbRows;
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
