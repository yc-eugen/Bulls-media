<?php

declare(strict_types=1);

namespace App\Services\Google\Parser\Handlers;

use App\Services\Google\Parser\GoogleSheetProcessingDTO;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSchemaByConfigHandler
{
    private const IGNORED_FIELDS = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function handle(GoogleSheetProcessingDTO $dto, Closure $next): GoogleSheetProcessingDTO
    {
        foreach ($dto->getProcessedSheets() as $tableName => $data) {
            if (!Schema::hasTable($tableName)) {
                throw new \RuntimeException("Table $tableName does not exist");
            }

            $diff = array_unique(array_merge(
                Schema::getColumnListing($tableName),
                array_values($data['config'])
            ));

            Schema::table($tableName, static function (Blueprint $table) use ($diff, $tableName) {
                foreach ($diff as $column) {
                    if (!Schema::hasColumn($tableName, $column) && !in_array($column, self::IGNORED_FIELDS)) {
                        $table->text($column)->nullable();
                    }
                }
            });
        }

        return $next($dto);
    }
}
