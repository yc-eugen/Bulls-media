<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\GoogleAccount;
use Google\Exception;
use Google_Client;
use Google_Service_Sheets;
use Illuminate\Console\Command;
use JsonException;

class GoogleAuthCommand extends Command
{
    protected $signature = 'app:google:auth {accountId}';

    protected $description = 'Fetch google auth data';

    /**
     * @throws Exception|JsonException
     */
    public function handle(): void
    {
        $accountId = $this->argument('accountId');
        if ($accountId === null) {
            $this->error('Account id is required');
            exit;
        }

        $account = GoogleAccount::where('id', $accountId)->first();

        if ($account === null) {
            $this->error('Account not found');
            exit;
        }

        $client = new Google_Client();
        $client->setAuthConfig($account->credentials);
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        if ($account->access_token !== null) {
            $accessToken = $account->access_token;
        } else {
            $authUrl = $client->createAuthUrl();
            $this->info("Перейдите по следующей ссылке, чтобы авторизоваться: ");
            $this->info($authUrl);

            $authCode = $this->ask('Введите код из браузера');

            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $account->access_token = $accessToken;
            $account->save();
            $this->info('Токен успешно сохранён!');
        }

        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            $this->error('Токен истёк. Пожалуйста, получите новый токен.');
            return;
        }

        $this->info('Авторизация успешна! Теперь вы можете работать с Google Sheets API.');

    }
}
