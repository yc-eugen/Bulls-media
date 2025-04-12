<?php

declare(strict_types=1);

namespace App\Services\Google\Parser;

use Google_Service_Sheets;
use Illuminate\Database\Eloquent\Collection;

class GoogleSheetProcessingDTO
{
    private int $accountId;

    private Google_Service_Sheets $googleServiceSheets;

    private Collection $googleSheets;

    private array $processedSheets;

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function setAccountId(int $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getGoogleServiceSheets(): Google_Service_Sheets
    {
        return $this->googleServiceSheets;
    }

    public function setGoogleServiceSheets(Google_Service_Sheets $googleServiceSheets): GoogleSheetProcessingDTO
    {
        $this->googleServiceSheets = $googleServiceSheets;

        return $this;
    }

    public function getGoogleSheets(): Collection
    {
        return $this->googleSheets;
    }

    public function setGoogleSheets(Collection $googleSheets): void
    {
        $this->googleSheets = $googleSheets;
    }

    public function getProcessedSheets(): array
    {
        return $this->processedSheets;
    }

    public function setProcessedSheets(array $processedSheets): self
    {
        $this->processedSheets = $processedSheets;

        return $this;
    }
}
