<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use Illuminate\Http\Request;
use App\Services\PorfolioManager;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        PorfolioManager::refresh();

        return inertia('Dashboard', [
            'holdings' => Holding::all(),
        ]);
    }
}
