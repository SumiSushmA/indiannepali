<?php

namespace App\Data\Toast;

readonly class OrderChargeRequest
{
    /**
     * @param  array<int, array{id: string, name: string, price: float, qty: int, toast_pos_id: ?string}>  $items
     */
    public function __construct(
        public array $items,
        public string $customerName,
        public string $customerEmail,
        public string $customerPhone,
        public string $fulfillmentType,
        public ?string $address,
        public ?string $notes,
        public float $subtotal,
        public float $tax,
        public float $deliveryFee,
        public float $tip,
        public float $total,
        public string $cardNumber,
        public ?string $cardExpiry,
        public ?string $cardCvc,
        public string $clientIp,
    ) {}
}
