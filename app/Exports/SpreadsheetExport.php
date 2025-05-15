<?php

namespace App\Exports;

use App\Services\SpreadsheetService;
use Carbon\CarbonImmutable;
use Maatwebsite\Excel\Concerns\WithEvents;

class SpreadsheetExport extends SpreadsheetService implements WithEvents
{
    protected string $fileName;

    protected CarbonImmutable $deletionDelay;

    public function __construct()
    {
        parent::__construct();
        $this->deletionDelay = now()->addHour();
    }

    public function beforeExport(): void
    {
        parent::beforeProcessing();
    }

    public function afterSheet(): void
    {
        parent::afterProcessing();
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getDeletionDelay(): CarbonImmutable
    {
        return $this->deletionDelay;
    }
}
