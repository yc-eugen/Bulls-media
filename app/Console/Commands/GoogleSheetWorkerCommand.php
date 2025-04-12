<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Google\Parser\GoogleSheetProcessingService;
use Illuminate\Console\Command;

class GoogleSheetWorkerCommand extends Command
{
    protected $signature = 'app:google:worker {accountId}';

    protected $description = 'Fetch google sheet data';

    public function handle(GoogleSheetProcessingService $googleSheetProcessingService): void
    {
        $accountId = $this->argument('accountId');
        if ($accountId === null) {
            $this->error('Account id is required');
            exit;
        }

        $googleSheetProcessingService->processGoogleSheets((int) $accountId);
    }
}
