<?php

namespace App\Services\Toast;

use App\Exceptions\ToastPaymentException;

class ToastCardEncryption
{
    public function encrypt(string $cardNumber, ?string $expiry, ?string $cvc): string
    {
        $key = config('toast.card_encryption_key');
        $keyId = config('toast.card_encryption_key_id');

        if (! filled($key) || ! filled($keyId)) {
            throw new ToastPaymentException('Toast card encryption is not configured. Set TOAST_CARD_ENCRYPTION_KEY and TOAST_CARD_ENCRYPTION_KEY_ID in .env.');
        }

        [$expMonth, $expYear] = $this->parseExpiry($expiry);

        $payload = json_encode([
            'cardNumber' => $cardNumber,
            'expMonth' => $expMonth,
            'expYear' => $expYear,
            'cvv' => $cvc ?? '',
        ], JSON_THROW_ON_ERROR);

        $publicKey = str_contains($key, 'BEGIN PUBLIC KEY')
            ? $key
            : base64_decode($key, true);

        if ($publicKey === false) {
            throw new ToastPaymentException('Invalid Toast card encryption key.');
        }

        $encrypted = '';
        $ok = openssl_public_encrypt(
            $payload,
            $encrypted,
            $publicKey,
            OPENSSL_PKCS1_OAEP_PADDING
        );

        if (! $ok) {
            throw new ToastPaymentException('Failed to encrypt card data for Toast.');
        }

        return base64_encode($encrypted);
    }

    public function keyId(): string
    {
        return (string) config('toast.card_encryption_key_id');
    }

    /** @return array{0: int, 1: int} */
    private function parseExpiry(?string $expiry): array
    {
        if (! filled($expiry)) {
            return [12, (int) date('Y') + 3];
        }

        $digits = preg_replace('/\D/', '', $expiry);

        if (strlen($digits) >= 4) {
            $month = (int) substr($digits, 0, 2);
            $yearPart = substr($digits, 2, 2);
            $year = (int) $yearPart;
            $year = $year < 100 ? 2000 + $year : $year;

            return [max(1, min(12, $month)), $year];
        }

        return [12, (int) date('Y') + 3];
    }
}
