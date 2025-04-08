<?php

namespace App\Imports;

use App\Services\SpreadsheetService;
use Maatwebsite\Excel\Concerns\WithEvents;

class SpreadsheetImport extends SpreadsheetService implements WithEvents
{
    public function __construct(protected int $skipRows = 0)
    {
        parent::__construct();
    }

    public function beforeImport(): void
    {
        parent::beforeProcessing();
    }

    public function afterImport(): void
    {
        parent::afterProcessing();
    }
}
