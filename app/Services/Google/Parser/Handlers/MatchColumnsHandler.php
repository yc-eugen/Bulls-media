<?php

declare(strict_types=1);

namespace App\Services\Google\Parser\Handlers;

use App\Models\GoogleMapConfig;
use App\Services\Google\Parser\GoogleSheetProcessingDTO;
use Closure;
use RuntimeException;

class MatchColumnsHandler
{
    public function handle(GoogleSheetProcessingDTO $dto, Closure $next): GoogleSheetProcessingDTO
    {
        $tables = [];
        foreach ($dto->getProcessedSheets() as $sheetIndex => $sheets) {
            foreach ($sheets as $sheetList => $sheet) {
                $headersColumn = $sheet[0];
                $mapConfig = $this->getMapConfig($sheetIndex, $sheetList);
                $table = $this->matchSheetTableToConfig($headersColumn, $mapConfig);
                $tables[$table]['documents'][$sheetIndex][$sheetList] = $this->matchSheetColumnsToConfig(
                    $headersColumn,
                    $mapConfig[$table],
                    $sheet,
                );

                $tables[$table]['config'] = $mapConfig[$table];
            }
        }

        $dto->setProcessedSheets($tables);

        return $next($dto);
    }

    private function matchSheetColumnsToConfig(array $headers, array $mapping, array $sheets): array
    {
        unset($sheets[0]);
        $sheetList = [];
        foreach ($sheets as $sheetIndex => $sheet) {
            $mappedRow = [];
            foreach ($headers as $index => $header) {
                if (isset($mapping[$header])) {
                    $mappedKey = $mapping[$header];
                    $mappedRow[$mappedKey] = $sheet[$index];
                }
            }
            $sheetList[] = $mappedRow;
        }

        return $sheetList;
    }

    private function getMapConfig(string $sheetIndex, string $sheetList)
    {
        $mapConfig = GoogleMapConfig::where(['sheet_id' => $sheetIndex, 'sheet_list' => $sheetList])->first();

        if ($mapConfig === null) {
            $mapConfig = GoogleMapConfig::where(['sheet_id' => $sheetIndex])->first();
        }

        if ($mapConfig === null) {
            throw new RuntimeException('Map config not found');
        }

        return $mapConfig->config;
    }

    private function matchSheetTableToConfig($sheetHeaders, $tableConfigColumns): string
    {
        $maxMatches = 0;
        $selectedTable = null;

        foreach ($tableConfigColumns as $tableName => $columns) {
            $matches = 0;

            foreach ($columns as $sheetColumn => $tableColumn) {
                if (in_array($sheetColumn, $sheetHeaders)) {
                    $matches++;
                } else {
                    $matches--;
                }
            }

            if ($matches > $maxMatches) {
                $maxMatches = $matches;
                $selectedTable = $tableName;
            }
        }

        if ($selectedTable === null) {
            throw new RuntimeException('Table not found');
        }

        return $selectedTable;
    }
}
