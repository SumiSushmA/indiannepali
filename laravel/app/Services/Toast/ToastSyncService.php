<?php

namespace App\Services\Toast;

use App\Models\MenuItem;
use App\Models\ToastSyncLog;

class ToastSyncService
{
    public function __construct(
        private ToastApiClient $api,
        private ToastMenuParser $parser,
        private ToastMenuCatalog $catalog,
    ) {}

    public function sync(): ToastSyncLog
    {
        if (! ToastConfiguration::canFetchMenus()) {
            return ToastSyncLog::create([
                'logged_at' => now(),
                'message' => 'Menu sync skipped — add TOAST_CLIENT_ID, TOAST_CLIENT_SECRET, and TOAST_RESTAURANT_GUID to .env.',
                'is_success' => false,
            ]);
        }

        try {
            $toastItems = $this->parser->flattenMenus($this->api->fetchMenus());
            $synced = $this->syncMenuItems($toastItems);
            $this->catalog->forget();

            return ToastSyncLog::create([
                'logged_at' => now(),
                'message' => "Live Toast sync completed — {$synced} menu items updated.",
                'is_success' => true,
            ]);
        } catch (\Throwable $e) {
            return ToastSyncLog::create([
                'logged_at' => now(),
                'message' => 'Toast sync failed: '.$e->getMessage(),
                'is_success' => false,
            ]);
        }
    }

    /**
     * @param  array<int, array{guid: string, name: string, price: ?float, description: ?string, available: bool, image_url: ?string}>  $toastItems
     */
    private function syncMenuItems(array $toastItems): int
    {
        $synced = 0;
        $existing = MenuItem::query()->get();

        foreach ($toastItems as $toastItem) {
            $menuItem = $existing->firstWhere('toast_pos_id', $toastItem['guid'])
                ?? $existing->first(fn (MenuItem $item) => ToastMenuParser::normalizeName($item->name) === ToastMenuParser::normalizeName($toastItem['name']));

            if (! $menuItem) {
                continue;
            }

            $updates = [
                'toast_pos_id' => $toastItem['guid'],
                'name' => $toastItem['name'],
                'is_available' => $toastItem['available'],
            ];

            if ($toastItem['price'] !== null) {
                $updates['price'] = $toastItem['price'];
            }

            if (filled($toastItem['description'])) {
                $updates['description'] = $toastItem['description'];
            }

            $updates['toast_image_url'] = $toastItem['image_url'];

            $menuItem->update($updates);
            $synced++;
        }

        return $synced;
    }
}
