<?php

namespace App\Services;

use App\DTOs\PamusPointApiResponseDTO;
use App\Services\Interfaces\PamusPointApiServiceInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PamusPointApiService implements PamusPointApiServiceInterface
{
    public function convert(
        string $mbId,
        int $amount,
        string $uniqueIdx,
        ?string $content = null
    ): PamusPointApiResponseDTO {
        try {
            $response = Http::pamus()
                ->post('/api/e_cash_point_update.php', [
                    'api_key' => config('services.pamus.point_api_key'),
                    'mb_id' => $mbId,
                    'point' => $amount,
                    'content' => $content ?? '마일리지 포인트 전환',
                    'unique_idx' => $uniqueIdx,
                ]);

            return PamusPointApiResponseDTO::createFromResponse($response);

        } catch (Exception $e) {
            Log::error('Pamus Point API Error', [
                'mb_id' => $mbId,
                'amount' => $amount,
                'unique_idx' => $uniqueIdx,
                'error' => $e->getMessage(),
            ]);

            return PamusPointApiResponseDTO::createFromError($e->getMessage());
        }
    }
}