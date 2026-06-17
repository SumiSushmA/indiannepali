<?php

namespace App\Services\Toast;

use App\Models\MenuItem;
use App\Models\ToastSyncLog;

class ToastSyncService
{
    public function __construct(private ToastApiClient $api) {}

    public function sync(): ToastSyncLog
    {
        if (! ToastConfiguration::isLive()) {
            return ToastSyncLog::create([
                'logged_at' => now(),
                'message' => 'Mock sync completed — add Toast API keys to .env for live sync.',
                'is_success' => true,
            ]);
        }

        try {
            $menus = $this->api->fetchMenus();
            $synced = $this->syncMenuItems($menus);

            return ToastSyncLog::create([
                'logged_at' => now(),
                'message' => "Live Toast sync completed — {$synced} menu items mapped.",
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

    private function syncMenuItems(array $menus): int
    {
        $synced = 0;

        foreach ($menus as $menu) {
            foreach ($menu['menuGroups'] ?? [] as $group) {
                foreach ($group['menuItems'] ?? [] as $item) {
                    $guid = $item['guid'] ?? null;
                    $name = $item['name'] ?? null;

                    if (! filled($guid) || ! filled($name)) {
                        continue;
                    }

                    $updated = MenuItem::query()
                        ->where('name', $name)
                        ->update(['toast_pos_id' => $guid]);

                    $synced += $updated;
                }
            }
        }

        return $synced;
    }
}
