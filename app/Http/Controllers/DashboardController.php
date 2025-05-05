<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Holding;
use Illuminate\Http\Request;
use App\Services\PorfolioManager;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {

        return inertia('Dashboard', [
            'holdings' => Inertia::defer(function () {
                PorfolioManager::refresh();

                return Holding::query()
                    ->orderBy('ticker')
                    ->get();
            }),
        ]);
    }
}
