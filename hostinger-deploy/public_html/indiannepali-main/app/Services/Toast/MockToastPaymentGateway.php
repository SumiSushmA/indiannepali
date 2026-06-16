<?php

namespace App\Services\Toast;

use App\Contracts\ToastPaymentGateway;
use App\Data\Toast\GiftCardChargeRequest;
use App\Data\Toast\OrderChargeRequest;
use App\Data\Toast\PaymentResult;
use Illuminate\Support\Str;

class MockToastPaymentGateway implements ToastPaymentGateway
{
    public function mode(): string
    {
        return 'mock';
    }

    public function isLive(): bool
    {
        return false;
    }

    public function chargeOrder(OrderChargeRequest $request): PaymentResult
    {
        $cardNumber = preg_replace('/\D/', '', $request->cardNumber);

        if (strlen($cardNumber) < 13) {
            return PaymentResult::failed('mock', 'Enter a valid card number to continue.');
        }

        if (str_ends_with($cardNumber, '0002')) {
            return PaymentResult::failed('mock', 'Card declined (demo). Try a different card number.');
        }

        return new PaymentResult(
            success: true,
            status: 'paid',
            provider: 'mock',
            toastOrderGuid: null,
            toastPaymentGuid: null,
            paymentReference: 'MOCK-'.strtoupper(Str::random(10)),
            cardLast4: substr($cardNumber, -4),
            cardBrand: $this->detectCardBrand($cardNumber),
        );
    }

    public function chargeGiftCard(GiftCardChargeRequest $request): PaymentResult
    {
        $cardNumber = preg_replace('/\D/', '', $request->cardNumber);

        if (strlen($cardNumber) < 13) {
            return PaymentResult::failed('mock', 'Enter a valid card number to continue.');
        }

        if (str_ends_with($cardNumber, '0002')) {
            return PaymentResult::failed('mock', 'Card declined (demo). Try a different card number.');
        }

        return new PaymentResult(
            success: true,
            status: 'paid',
            provider: 'mock',
            paymentReference: 'MOCK-GC-'.strtoupper(Str::random(8)),
            cardLast4: substr($cardNumber, -4),
            cardBrand: $this->detectCardBrand($cardNumber),
        );
    }

    private function detectCardBrand(string $cardNumber): string
    {
        return match (substr($cardNumber, 0, 1)) {
            '4' => 'Visa',
            '5' => 'Mastercard',
            '3' => 'Amex',
            '6' => 'Discover',
            default => 'Unknown',
        };
    }
}
