<?php

namespace App\Services\Toast;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;

class ToastMenuCatalog
{
    private const CACHE_KEY = 'toast_menu_catalog_v1';

    private const CACHE_TTL_SECONDS = 1800;

    public function __construct(
        private ToastApiClient $api,
        private ToastMenuParser $parser,
    ) {}

    /**
     * @return array<string, array{guid: string, name: string, price: ?float, description: ?string, available: bool, image_url: ?string}>
     */
    public function itemsByGuid(): array
    {
        return $this->load();
    }

    public function findForMenuItem(MenuItem $item): ?array
    {
        $catalog = $this->load();

        if (filled($item->toast_pos_id) && isset($catalog[$item->toast_pos_id])) {
            return $catalog[$item->toast_pos_id];
        }

        $needle = ToastMenuParser::normalizeName($item->name);

        if (isset($catalog['__by_name__'][$needle])) {
            return $catalog['__by_name__'][$needle];
        }

        return null;
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return array<string, array{guid: string, name: string, price: ?float, description: ?string, available: bool, image_url: ?string}>
     */
    private function load(): array
    {
        if (! ToastConfiguration::canFetchMenus()) {
            return [];
        }

        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            try {
                return $this->indexByGuid(
                    $this->parser->flattenMenus($this->api->fetchMenus())
                );
            } catch (\Throwable) {
                return [];
            }
        });
    }

    /**
     * @param  array<int, array{guid: string, name: string, price: ?float, description: ?string, available: bool, image_url: ?string}>  $items
     * @return array<string, array{guid: string, name: string, price: ?float, description: ?string, available: bool, image_url: ?string}>
     */
    private function indexByGuid(array $items): array
    {
        $indexed = [
            '__by_name__' => [],
        ];

        foreach ($items as $item) {
            $indexed[$item['guid']] = $item;

            $normalizedName = ToastMenuParser::normalizeName($item['name']);

            if ($normalizedName !== '' && ! isset($indexed['__by_name__'][$normalizedName])) {
                $indexed['__by_name__'][$normalizedName] = $item;
            }
        }

        return $indexed;
    }
}
