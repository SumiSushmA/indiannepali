<?php

namespace App\Data\Toast;

readonly class PaymentResult
{
    public function __construct(
        public bool $success,
        public string $status,
        public string $provider,
        public ?string $toastOrderGuid = null,
        public ?string $toastPaymentGuid = null,
        public ?string $paymentReference = null,
        public ?string $cardLast4 = null,
        public ?string $cardBrand = null,
        public ?string $error = null,
    ) {}

    public static function failed(string $provider, string $error): self
    {
        return new self(
            success: false,
            status: 'failed',
            provider: $provider,
            error: $error,
        );
    }
}
