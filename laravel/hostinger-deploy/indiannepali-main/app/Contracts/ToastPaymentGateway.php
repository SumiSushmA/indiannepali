<?php

namespace App\Contracts;

use App\Data\Toast\GiftCardChargeRequest;
use App\Data\Toast\OrderChargeRequest;
use App\Data\Toast\PaymentResult;

interface ToastPaymentGateway
{
    public function mode(): string;

    public function isLive(): bool;

    public function chargeOrder(OrderChargeRequest $request): PaymentResult;

    public function chargeGiftCard(GiftCardChargeRequest $request): PaymentResult;
}
