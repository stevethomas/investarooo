<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use App\PorfolioManager;
use Illuminate\Http\Request;

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
