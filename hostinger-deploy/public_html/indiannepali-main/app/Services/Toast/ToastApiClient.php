<?php

namespace App\Services\Toast;

use App\Exceptions\ToastPaymentException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ToastApiClient
{
    public function accessToken(): string
    {
        return Cache::remember('toast_access_token', 60 * 60 * 20, function () {
            $response = Http::baseUrl(ToastConfiguration::apiBaseUrl())
                ->acceptJson()
                ->post('/authentication/v1/authentication/login', [
                    'clientId' => config('toast.client_id'),
                    'clientSecret' => config('toast.client_secret'),
                    'userAccessType' => 'TOAST_MACHINE_CLIENT',
                ]);

            if (! $response->successful()) {
                throw new ToastPaymentException('Toast authentication failed: '.$response->body());
            }

            $token = $response->json('token.accessToken');

            if (! filled($token)) {
                throw new ToastPaymentException('Toast authentication returned no access token.');
            }

            return $token;
        });
    }

    public function priceOrder(array $orderPayload): array
    {
        $response = $this->request()
            ->post('/orders/v2/prices', $orderPayload);

        if (! $response->successful()) {
            throw new ToastPaymentException('Toast price validation failed: '.$this->extractError($response->json(), $response->body()));
        }

        return $response->json();
    }

    public function submitOrder(array $orderPayload): array
    {
        $response = $this->request()
            ->post('/orders/v2/orders', $orderPayload);

        if (! $response->successful()) {
            throw new ToastPaymentException('Toast order submission failed: '.$this->extractError($response->json(), $response->body()));
        }

        return $response->json();
    }

    public function authorizePayment(
        string $paymentUuid,
        float $amount,
        float $tipAmount,
        string $encryptedCardData,
        string $keyId,
        array $metadata,
    ): array {
        $merchantUuid = config('toast.merchant_uuid');

        $response = $this->request()
            ->put("/ccpartner/v1/merchants/{$merchantUuid}/payments/{$paymentUuid}", [
                'encryptedCardData' => $encryptedCardData,
                'keyId' => $keyId,
                'amount' => round($amount, 2),
                'tipAmount' => round($tipAmount, 2),
                'willSaveCard' => false,
                'cardNumberOrigin' => 'END_USER',
                'requestMetadata' => $metadata,
            ]);

        if (! $response->successful()) {
            throw new ToastPaymentException('Toast payment authorization failed: '.$this->extractError($response->json(), $response->body()));
        }

        return $response->json();
    }

    public function fetchMenus(): array
    {
        $restaurantGuid = config('toast.restaurant_guid');

        $response = $this->request()
            ->get("/menus/v2/menus", [
                'restaurantGuid' => $restaurantGuid,
            ]);

        if (! $response->successful()) {
            throw new ToastPaymentException('Toast menu sync failed: '.$this->extractError($response->json(), $response->body()));
        }

        return $response->json();
    }

    public function newPaymentUuid(): string
    {
        return (string) Str::uuid();
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl(ToastConfiguration::apiBaseUrl())
            ->acceptJson()
            ->withToken($this->accessToken());
    }

    private function extractError(mixed $json, string $fallback): string
    {
        if (is_array($json)) {
            return $json['message']
                ?? $json['errorMessage']
                ?? $json['status']
                ?? $fallback;
        }

        return $fallback;
    }
}
