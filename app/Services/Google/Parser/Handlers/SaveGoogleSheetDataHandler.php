<?php

declare(strict_types=1);

namespace App\Services\Google\Parser\Handlers;

use App\Services\Google\Parser\GoogleSheetProcessingDTO;
use Closure;
use Illuminate\Support\Facades\DB;

class SaveGoogleSheetDataHandler
{
    public function handle(GoogleSheetProcessingDTO $dto, Closure $next): GoogleSheetProcessingDTO
    {
        foreach ($dto->getProcessedSheets() as $tableName => $data) {
            foreach ($data['documents'] as $sheetIndex => $sheets) {
                foreach ($sheets as $sheet) {
                    foreach ($sheet as $row) {
                        $columns = array_keys($row);
                        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
                        $values = array_values($row);

                        $sql = 'INSERT INTO ' . $tableName . ' (' . implode(', ', $columns) . ')
                        VALUES (' . $placeholders . ')';

                        DB::insert($sql, $values);
                    }
                }

            }
        }

        return $next($dto);
    }
}
