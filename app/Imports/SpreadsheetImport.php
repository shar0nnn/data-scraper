<?php

namespace App\Imports;

use App\Services\SpreadsheetService;
use Maatwebsite\Excel\Concerns\WithEvents;

class SpreadsheetImport extends SpreadsheetService implements WithEvents
{
    public function beforeImport(): void
    {
        parent::beforeProcessing();
    }

    public function afterImport(): void
    {
        parent::afterProcessing();
    }
}
