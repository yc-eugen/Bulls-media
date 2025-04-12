<?php

declare(strict_types=1);

namespace App\Services\Google\Parser\Handlers;

use App\Models\GoogleAccount;
use App\Services\Google\Parser\GoogleSheetProcessingDTO;
use Closure;
use Google\Exception;
use Google_Client;
use Google_Service_Sheets;
use JsonException;
use RuntimeException;

class GoogleSheetSetupClientHandler
{
    /**
     * @throws Exception
     * @throws JsonException
     */
    public function handle(GoogleSheetProcessingDTO $dto, Closure $next): GoogleSheetProcessingDTO
    {
        $account = GoogleAccount::where('id', $dto->getAccountId())->first();

        if ($account === null) {
            throw new RuntimeException('Google account not found');
        }

        $client = new Google_Client();
        $client->setAuthConfig($account->credentials);
        $client->setAccessType('offline');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setPrompt('select_account consent');
        $client->setAccessToken($account->access_token);

        $dto->setGoogleServiceSheets(new Google_Service_Sheets($client));

        return $next($dto);
    }
}
