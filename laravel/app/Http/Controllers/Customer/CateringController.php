<?php

namespace App\Http\Controllers\Customer;

use App\Data\CateringMenu;
use App\Http\Controllers\Controller;
use App\Services\Toast\ToastConfiguration;
use App\Support\CateringCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CateringController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($url = ToastConfiguration::cateringUrl()) {
            return redirect()->away($url);
        }

        $tab = $request->query('tab', 'per-person');
        if (! in_array($tab, ['per-person', 'trays'], true)) {
            $tab = 'per-person';
        }

        return view('customer.catering.create', [
            'tab' => $tab,
            'perPerson' => CateringMenu::perPerson(),
            'trays' => CateringMenu::trays(),
            'minGuests' => CateringMenu::MIN_GUESTS,
            'perPersonPrice' => CateringMenu::PER_PERSON_PRICE,
            'cart' => CateringCart::all(),
        ]);
    }

    public function orderPerPerson(Request $request): RedirectResponse
    {
        $groups = collect(CateringMenu::perPerson()['groups'])->pluck('id')->all();

        $validated = $request->validate([
            'guest_count' => 'required|integer|min:'.CateringMenu::MIN_GUESTS,
            'selections' => 'required|array',
            'selections.*' => 'array',
            'selections.*.*' => 'string|max:120',
        ], [
            'guest_count.min' => 'Catering requires a minimum of '.CateringMenu::MIN_GUESTS.' people.',
        ]);

        $selections = [];
        $hasSelection = false;

        foreach ($groups as $groupId) {
            $allowed = array_keys(CateringMenu::optionIndex()[$groupId] ?? []);
            $picked = array_values(array_filter(
                $validated['selections'][$groupId] ?? [],
                fn ($name) => in_array($name, $allowed, true)
            ));
            if ($picked) {
                $hasSelection = true;
            }
            $selections[$groupId] = $picked;
        }

        if (! $hasSelection) {
            return back()
                ->withInput()
                ->withErrors(['selections' => 'Choose at least one dish for your catering menu.']);
        }

        CateringCart::setPerPerson((int) $validated['guest_count'], $selections);

        return redirect()
            ->route('checkout')
            ->with('success', 'Catering menu added — proceed to checkout.');
    }

    public function addTray(Request $request, string $slug): RedirectResponse
    {
        $tray = CateringMenu::tray($slug);

        if (! $tray) {
            return back()->with('error', 'Tray item not found.');
        }

        $qty = max(1, (int) $request->input('qty', 1));
        CateringCart::addTray($slug, $qty);

        return back()->with('success', $tray['name'].' added to your order.');
    }
}
