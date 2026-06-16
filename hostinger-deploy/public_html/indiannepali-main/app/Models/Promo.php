<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promo extends Model
{
    public const TYPE_COMBO = 'combo_meal';

    public const TYPE_SPEND_SAVE = 'spend_save';

    public const TYPE_RESERVATION = 'reservation_perk';

    public const TYPE_LIMITED = 'limited_time';

    public const CTA_ORDER_ITEM = 'order_item';

    public const CTA_MENU = 'menu';

    public const CTA_RESERVE = 'reserve';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'slug',
        'badge',
        'title',
        'detail',
        'price_label',
        'accent',
        'offer_type',
        'cta_type',
        'cta_label',
        'menu_item_slug',
        'terms',
        'starts_at',
        'ends_at',
        'min_order_amount',
        'min_party_size',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'starts_at' => 'date',
            'ends_at' => 'date',
            'min_order_amount' => 'decimal:2',
            'min_party_size' => 'integer',
        ];
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_slug', 'slug');
    }

    public function scopeVisible(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', $today);
            })
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', $today);
            });
    }

    public static function offerTypes(): array
    {
        return [
            self::TYPE_COMBO => 'Combo meal deal',
            self::TYPE_SPEND_SAVE => 'Spend & save (e.g. free delivery)',
            self::TYPE_RESERVATION => 'Dine-in / reservation perk',
            self::TYPE_LIMITED => 'Limited-time special',
        ];
    }

    public static function ctaTypes(): array
    {
        return [
            self::CTA_MENU => 'Browse menu & order',
            self::CTA_ORDER_ITEM => 'Add menu item to cart',
            self::CTA_RESERVE => 'Book a table',
        ];
    }

    public function offerTypeLabel(): string
    {
        return self::offerTypes()[$this->offer_type] ?? 'Special offer';
    }

    /** @return array{label: string, href?: string, order_item?: string} */
    public function primaryAction(): array
    {
        $label = $this->cta_label ?: match ($this->cta_type) {
            self::CTA_ORDER_ITEM => 'Order now',
            self::CTA_MENU => 'Order now',
            self::CTA_RESERVE => 'Reserve now',
            default => 'Learn more',
        };

        return match ($this->cta_type) {
            self::CTA_ORDER_ITEM => [
                'label' => $label,
                'order_item' => $this->menu_item_slug,
            ],
            self::CTA_RESERVE => [
                'label' => $label,
                'href' => route('reserve', array_filter([
                    'party' => $this->min_party_size,
                ])),
            ],
            default => [
                'label' => $label,
                'href' => route('menu'),
            ],
        };
    }

    public function toCustomerArray(): array
    {
        $action = $this->primaryAction();

        return [
            'id' => $this->slug,
            'badge' => $this->badge,
            'title' => $this->title,
            'detail' => $this->detail,
            'price' => $this->price_label,
            'accent' => $this->accent,
            'offer_type' => $this->offer_type,
            'offer_type_label' => $this->offerTypeLabel(),
            'terms' => $this->terms,
            'starts_at' => $this->starts_at?->format('M j, Y'),
            'ends_at' => $this->ends_at?->format('M j, Y'),
            'min_order_amount' => $this->min_order_amount,
            'min_party_size' => $this->min_party_size,
            'cta_label' => $action['label'],
            'cta_href' => $action['href'] ?? null,
            'order_item' => $action['order_item'] ?? null,
            'image' => \App\Support\StockImages::forLabel($this->title),
        ];
    }

    public static function activeFreeDeliveryMinimum(): ?float
    {
        $min = static::query()
            ->visible()
            ->where('offer_type', self::TYPE_SPEND_SAVE)
            ->whereNotNull('min_order_amount')
            ->min('min_order_amount');

        return $min !== null ? (float) $min : null;
    }

    public static function activeFreeDeliveryOffer(): ?self
    {
        return static::query()
            ->visible()
            ->where('offer_type', self::TYPE_SPEND_SAVE)
            ->whereNotNull('min_order_amount')
            ->orderBy('min_order_amount')
            ->first();
    }
}
