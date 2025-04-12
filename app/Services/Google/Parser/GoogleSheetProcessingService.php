<?php

declare(strict_types=1);

namespace App\Services\Google\Parser;

use App\Services\Google\Parser\Handlers\MatchColumnsHandler;
use App\Services\Google\Parser\Handlers\SaveGoogleSheetDataHandler;
use App\Services\Google\Parser\Handlers\UpdateSchemaByConfigHandler;
use App\Services\Google\Parser\Handlers\GoogleSheetSetupClientHandler;
use App\Services\Google\Parser\Handlers\PrepareGoogleSheetsHandler;
use App\Services\Google\Parser\Handlers\ProcessGoogleSheetsHandler;
use Illuminate\Pipeline\Pipeline;

class GoogleSheetProcessingService
{
    private const HANDLERS = [
        GoogleSheetSetupClientHandler::class,
        PrepareGoogleSheetsHandler::class,
        ProcessGoogleSheetsHandler::class,
        MatchColumnsHandler::class,
        UpdateSchemaByConfigHandler::class,
        SaveGoogleSheetDataHandler::class,
    ];

    public function __construct(
        private readonly Pipeline $pipeline
    ) {
    }

    public function processGoogleSheets(int $accountId): void
    {
        $googleSheetDto = new GoogleSheetProcessingDTO();
        $googleSheetDto->setAccountId($accountId);

        $this->pipeline->send($googleSheetDto)->through(self::HANDLERS)->then(function ($finalOutput) {
            return $finalOutput;
        });
    }
}
