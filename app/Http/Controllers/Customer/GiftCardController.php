<?php

namespace App\Http\Controllers\Customer;

use App\Contracts\ToastPaymentGateway;
use App\Data\Toast\GiftCardChargeRequest;
use App\Exceptions\ToastPaymentException;
use App\Http\Controllers\Controller;
use App\Mail\GiftCardPurchaseMail;
use App\Models\GiftAmount;
use App\Models\GiftCard;
use App\Models\GiftCardDesign;
use App\Services\RestaurantData;
use App\Services\Toast\ToastConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GiftCardController extends Controller
{
    public function __construct(private ToastPaymentGateway $payments) {}

    public function create(): View|RedirectResponse
    {
        if ($url = ToastConfiguration::giftCardsUrl()) {
            return redirect()->away($url);
        }

        return view('customer.gift-cards.create', [
            'giftDesigns' => RestaurantData::giftDesigns(),
            'giftAmounts' => GiftAmount::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('amount')
                ->map(fn ($amount) => (int) $amount)
                ->all(),
            'purchase' => session('gift_purchase'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'design' => 'required|string|max:30',
            'amount' => 'required|numeric|min:10|max:500',
            'delivery' => 'required|in:email,print,mail',
            'recipient' => 'required|string|max:120',
            'sender' => 'required|string|max:120',
            'message' => 'nullable|string|max:500',
            'card_number' => 'required|string|max:30',
            'card_expiry' => 'nullable|string|max:10',
            'card_cvc' => 'nullable|string|max:4',
        ]);

        $design = GiftCardDesign::where('slug', $request->input('design'))->firstOrFail();
        $amount = (float) $request->input('amount');
        $recipient = $request->input('recipient');
        $recipientEmail = str_contains($recipient, '@') ? $recipient : null;

        try {
            $payment = $this->payments->chargeGiftCard(new GiftCardChargeRequest(
                amount: $amount,
                senderName: $request->input('sender'),
                recipientName: $recipientEmail ? $recipient : $recipient,
                recipientEmail: $recipientEmail,
                cardNumber: $request->input('card_number'),
                cardExpiry: $request->input('card_expiry'),
                cardCvc: $request->input('card_cvc'),
                clientIp: $request->ip() ?? '127.0.0.1',
            ));
        } catch (ToastPaymentException $e) {
            return back()->withInput()->withErrors(['payment' => $e->getMessage()]);
        }

        if (! $payment->success) {
            return back()->withInput()->withErrors(['payment' => $payment->error ?? 'Payment could not be processed.']);
        }

        $code = 'NK-'.strtoupper(Str::random(4)).'-'.random_int(1000, 9999);

        $card = GiftCard::create([
            'code' => $code,
            'gift_card_design_id' => $design->id,
            'face_value' => $amount,
            'balance' => $amount,
            'status' => 'Active',
            'recipient_name' => $recipient,
            'sender_name' => $request->input('sender'),
            'message' => $request->input('message'),
            'delivery_method' => $request->input('delivery'),
            'channel' => 'Online',
            'payment_status' => $payment->status,
            'payment_provider' => $payment->provider,
            'toast_order_guid' => $payment->toastOrderGuid,
            'toast_payment_guid' => $payment->toastPaymentGuid,
            'payment_reference' => $payment->paymentReference,
            'card_last4' => $payment->cardLast4,
            'card_brand' => $payment->cardBrand,
            'issued_at' => now(),
        ]);

        session(['gift_purchase' => [
            'code' => $card->code,
            'amount' => (float) $card->face_value,
            'design' => $design->name,
            'delivery' => $card->delivery_method,
            'recipient' => $card->recipient_name,
            'sender' => $card->sender_name,
            'payment_provider' => $payment->provider,
            'payment_reference' => $payment->paymentReference,
        ]]);

        if ($recipientEmail && $request->input('delivery') === 'email') {
            Mail::to($recipientEmail)->send(new GiftCardPurchaseMail($card, $design, $recipientEmail));
        }

        return redirect()->route('giftcards')->with('gift_sent', true);
    }

    public function balance(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string|max:30']);

        $code = strtoupper(trim($request->input('code')));
        $card = GiftCard::query()
            ->where('code', $code)
            ->where('status', 'Active')
            ->first();

        if (! $card) {
            return response()->json([
                'ok' => false,
                'message' => 'Gift card not found. Check the code and try again.',
            ]);
        }

        return response()->json([
            'ok' => true,
            'code' => $card->code,
            'balance' => (float) $card->balance,
        ]);
    }
}
