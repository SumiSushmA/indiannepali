<?php

namespace App\Services\Toast;

use App\Contracts\ToastPaymentGateway;
use App\Data\Toast\GiftCardChargeRequest;
use App\Data\Toast\OrderChargeRequest;
use App\Data\Toast\PaymentResult;
use App\Exceptions\ToastPaymentException;
use Illuminate\Support\Str;

class LiveToastPaymentGateway implements ToastPaymentGateway
{
    public function __construct(
        private ToastApiClient $api,
        private ToastCardEncryption $encryption,
        private ToastOrderBuilder $orders,
    ) {}

    public function mode(): string
    {
        return 'live';
    }

    public function isLive(): bool
    {
        return true;
    }

    public function chargeOrder(OrderChargeRequest $request): PaymentResult
    {
        $cardNumber = preg_replace('/\D/', '', $request->cardNumber);
        $paymentGuid = $this->api->newPaymentUuid();
        $checkGuid = (string) Str::uuid();

        $orderPayload = $this->orders->buildFoodOrder($request, $checkGuid, $paymentGuid);
        $pricedOrder = $this->api->priceOrder($orderPayload);

        $chargeAmount = (float) ($pricedOrder['checks'][0]['totalAmount'] ?? $request->total);
        $tipAmount = round($request->tip, 2);

        $encrypted = $this->encryption->encrypt($cardNumber, $request->cardExpiry, $request->cardCvc);

        $this->api->authorizePayment(
            paymentUuid: $paymentGuid,
            amount: $chargeAmount - $tipAmount,
            tipAmount: $tipAmount,
            encryptedCardData: $encrypted,
            keyId: $this->encryption->keyId(),
            metadata: $this->orders->paymentMetadata(
                $request->customerName,
                $request->customerEmail,
                $request->customerPhone,
                $cardNumber,
                $request->clientIp,
                $request->address,
            ),
        );

        $pricedOrder = $this->attachPaymentToOrder($pricedOrder, $paymentGuid, $chargeAmount - $tipAmount, $tipAmount);

        $submitted = $this->api->submitOrder($pricedOrder);

        return new PaymentResult(
            success: true,
            status: 'paid',
            provider: 'toast',
            toastOrderGuid: $submitted['guid'] ?? ($pricedOrder['guid'] ?? null),
            toastPaymentGuid: $paymentGuid,
            paymentReference: $paymentGuid,
            cardLast4: substr($cardNumber, -4),
            cardBrand: $this->detectCardBrand($cardNumber),
        );
    }

    public function chargeGiftCard(GiftCardChargeRequest $request): PaymentResult
    {
        $cardNumber = preg_replace('/\D/', '', $request->cardNumber);
        $paymentGuid = $this->api->newPaymentUuid();
        $checkGuid = (string) Str::uuid();

        $orderPayload = $this->orders->buildGiftCardOrder($request, $checkGuid, $paymentGuid);
        $pricedOrder = $this->api->priceOrder($orderPayload);

        $chargeAmount = (float) ($pricedOrder['checks'][0]['totalAmount'] ?? $request->amount);

        $encrypted = $this->encryption->encrypt($cardNumber, $request->cardExpiry, $request->cardCvc);

        $this->api->authorizePayment(
            paymentUuid: $paymentGuid,
            amount: $chargeAmount,
            tipAmount: 0,
            encryptedCardData: $encrypted,
            keyId: $this->encryption->keyId(),
            metadata: $this->orders->paymentMetadata(
                $request->senderName,
                $request->recipientEmail ?? 'giftcard@indiannepali.kitchen',
                '0000000000',
                $cardNumber,
                $request->clientIp,
            ),
        );

        $pricedOrder = $this->attachPaymentToOrder($pricedOrder, $paymentGuid, $chargeAmount, 0);

        $submitted = $this->api->submitOrder($pricedOrder);

        return new PaymentResult(
            success: true,
            status: 'paid',
            provider: 'toast',
            toastOrderGuid: $submitted['guid'] ?? ($pricedOrder['guid'] ?? null),
            toastPaymentGuid: $paymentGuid,
            paymentReference: $paymentGuid,
            cardLast4: substr($cardNumber, -4),
            cardBrand: $this->detectCardBrand($cardNumber),
        );
    }

    private function attachPaymentToOrder(array $order, string $paymentGuid, float $amount, float $tipAmount): array
    {
        if (! empty($order['checks'][0])) {
            $order['checks'][0]['payments'] = [[
                'guid' => $paymentGuid,
                'type' => 'CREDIT',
                'amount' => round($amount, 2),
                'tipAmount' => round($tipAmount, 2),
            ]];
        }

        return $order;
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
