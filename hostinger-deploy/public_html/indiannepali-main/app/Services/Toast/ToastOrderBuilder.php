<?php

namespace App\Services\Toast;

use App\Data\Toast\GiftCardChargeRequest;
use App\Data\Toast\OrderChargeRequest;
use App\Exceptions\ToastPaymentException;
use Illuminate\Support\Str;

class ToastOrderBuilder
{
    public function buildFoodOrder(OrderChargeRequest $request, string $checkGuid, string $paymentGuid): array
    {
        $selections = [];

        foreach ($request->items as $item) {
            if (! filled($item['toast_pos_id'] ?? null)) {
                throw new ToastPaymentException(
                    "Menu item \"{$item['name']}\" is not mapped to Toast. Set toast_pos_id in admin before taking live payments."
                );
            }

            $selections[] = [
                'item' => ['guid' => $item['toast_pos_id']],
                'itemGroup' => null,
                'quantity' => $item['qty'],
                'modifiers' => [],
            ];
        }

        $diningGuid = $request->fulfillmentType === 'delivery'
            ? config('toast.dining_option_delivery_guid')
            : config('toast.dining_option_pickup_guid');

        if (! filled($diningGuid)) {
            throw new ToastPaymentException('Toast dining option GUID is not configured in .env.');
        }

        $revenueCenter = config('toast.revenue_center_guid');

        $check = [
            'guid' => $checkGuid,
            'customer' => [
                'firstName' => $this->firstName($request->customerName),
                'lastName' => $this->lastName($request->customerName),
                'email' => $request->customerEmail,
                'phone' => $request->customerPhone,
            ],
            'selections' => $selections,
            'payments' => [[
                'guid' => $paymentGuid,
                'type' => 'CREDIT',
                'amount' => round($request->subtotal + $request->tax + $request->deliveryFee, 2),
                'tipAmount' => round($request->tip, 2),
            ]],
        ];

        if (filled($revenueCenter)) {
            $check['revenueCenter'] = ['guid' => $revenueCenter];
        }

        $order = [
            'guid' => (string) Str::uuid(),
            'restaurantGuid' => config('toast.restaurant_guid'),
            'diningOption' => ['guid' => $diningGuid],
            'checks' => [$check],
        ];

        if ($request->fulfillmentType === 'delivery' && filled($request->address)) {
            $order['deliveryInfo'] = [
                'address1' => $request->address,
                'notes' => $request->notes,
            ];
        }

        return $order;
    }

    public function buildGiftCardOrder(GiftCardChargeRequest $request, string $checkGuid, string $paymentGuid): array
    {
        $menuItemGuid = config('toast.gift_card_menu_item_guid');

        if (! filled($menuItemGuid)) {
            throw new ToastPaymentException('Set TOAST_GIFT_CARD_MENU_ITEM_GUID in .env for live gift card payments.');
        }

        $diningGuid = config('toast.dining_option_pickup_guid') ?: config('toast.dining_option_delivery_guid');

        if (! filled($diningGuid)) {
            throw new ToastPaymentException('Toast dining option GUID is not configured in .env.');
        }

        $check = [
            'guid' => $checkGuid,
            'customer' => [
                'firstName' => $this->firstName($request->senderName),
                'lastName' => $this->lastName($request->senderName),
                'email' => $request->recipientEmail ?? 'giftcard@indiannepali.kitchen',
                'phone' => '0000000000',
            ],
            'selections' => [[
                'item' => ['guid' => $menuItemGuid],
                'itemGroup' => null,
                'quantity' => 1,
                'modifiers' => [],
            ]],
            'payments' => [[
                'guid' => $paymentGuid,
                'type' => 'CREDIT',
                'amount' => round($request->amount, 2),
                'tipAmount' => 0,
            ]],
        ];

        $revenueCenter = config('toast.revenue_center_guid');
        if (filled($revenueCenter)) {
            $check['revenueCenter'] = ['guid' => $revenueCenter];
        }

        return [
            'guid' => (string) Str::uuid(),
            'restaurantGuid' => config('toast.restaurant_guid'),
            'diningOption' => ['guid' => $diningGuid],
            'checks' => [$check],
        ];
    }

    public function paymentMetadata(string $name, string $email, string $phone, string $cardNumber, string $clientIp, ?string $address = null): array
    {
        $metadata = [
            'localTransactionDate' => now()->format('Y-m-d\TH:i:s.vP'),
            'originIPAddr' => $clientIp,
            'partnerServiceInfo' => [
                'instanceId' => config('app.name', 'Indian Nepali Kitchen'),
            ],
            'cardFirst6' => substr($cardNumber, 0, 6),
            'cardLast4' => substr($cardNumber, -4),
            'guestIdentifier' => $email,
            'guestEmail' => $email,
            'billingAddress' => [
                'name' => $name,
                'phone' => $phone,
                'address1' => $address ?? 'Online order',
                'address2' => '',
                'city' => 'Online',
                'region' => 'CA',
                'postalCode' => '00000',
                'country' => 'USA',
            ],
        ];

        if (filled($address)) {
            $metadata['deliveryAddress'] = [
                'name' => $name,
                'phone' => $phone,
                'address1' => $address,
                'address2' => '',
                'city' => 'Online',
                'region' => 'CA',
                'postalCode' => '00000',
                'country' => 'USA',
            ];
        }

        return $metadata;
    }

    private function firstName(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return $parts[0] ?? 'Guest';
    }

    private function lastName(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return $parts[1] ?? 'Customer';
    }
}
