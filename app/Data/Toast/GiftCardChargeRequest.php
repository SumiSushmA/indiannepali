<?php

namespace App\Data\Toast;

readonly class GiftCardChargeRequest
{
    public function __construct(
        public float $amount,
        public string $senderName,
        public string $recipientName,
        public ?string $recipientEmail,
        public string $cardNumber,
        public ?string $cardExpiry,
        public ?string $cardCvc,
        public string $clientIp,
    ) {}
}
