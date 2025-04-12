<?php

declare(strict_types=1);

namespace App\Services\Google\Parser\Handlers;

use App\Models\GoogleSheets;
use App\Services\Google\Parser\GoogleSheetProcessingDTO;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

class PrepareGoogleSheetsHandler
{
    public function handle(GoogleSheetProcessingDTO $dto, Closure $next): GoogleSheetProcessingDTO
    {
        /** @var Collection $googleSheets */
        $googleSheets = GoogleSheets::where('google_account_id', $dto->getAccountId())->get();

        if ($googleSheets->count() > 0) {
            $dto->setGoogleSheets($googleSheets);

            return $next($dto);
        }

        throw new RuntimeException('Google Sheet not found');
    }
}
