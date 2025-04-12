<?php

declare(strict_types=1);

namespace App\Services\Google\Parser\Handlers;

use App\Services\Google\Parser\GoogleSheetProcessingDTO;
use Closure;
use Google_Service_Sheets;

class ProcessGoogleSheetsHandler
{
    public function handle(GoogleSheetProcessingDTO $dto, Closure $next): GoogleSheetProcessingDTO
    {
        $client = $dto->getGoogleServiceSheets();
        $values = [];

        foreach ($dto->getGoogleSheets() as $sheet) {
            if (!isset($values[$sheet->sheet_id])) {
                $values[$sheet->sheet_id] = [];
            }

            if ($sheet->range !== null) {
                $firstSheetName = explode('!', $sheet->range)[0];
                $values[$sheet->sheet_id][$firstSheetName] = $this->getValues($client, $sheet->sheet_id, $sheet->range);
            } else {
                $spreadsheet = $client->spreadsheets->get($sheet->sheet_id);
                $sheets = $spreadsheet->getSheets();
                foreach ($sheets as $sheetTable) {
                    $firstSheetName = $sheetTable->getProperties()->getTitle();
                    $range = sprintf('%s!A:Z', $firstSheetName);
                    $values[$sheet->sheet_id][$firstSheetName] = $this->getValues($client, $sheet->sheet_id, $range);
                }
            }
        }

        $dto->setProcessedSheets($values);

        return $next($dto);
    }

    private function getValues(Google_Service_Sheets $client, string $sheetId, string $range): array
    {
        $response = $client->spreadsheets_values->get(
            $sheetId,
            $range,
        );

        return $response->getValues();
    }
}
