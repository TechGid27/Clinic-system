<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use App\Models\MedicationRequest;
use App\Models\ModuleSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $lowStockMedications = Medication::whereColumn('quantity', '<=', 'low_stock_threshold')
            ->with('category')
            ->get();

        $expiringSoon = Medication::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(30))
            ->whereDate('expiry_date', '>=', now())
            ->with('category')
            ->orderBy('expiry_date')
            ->get();

        $expiredMedications = Medication::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', now())
            ->with('category')
            ->get();

        $pendingRequests = MedicationRequest::where('status', 'pending')->count();

        $recentRequests = MedicationRequest::with(['items.medication'])
            ->latest()
            ->take(5)
            ->get();

        $categories = Category::with('medications')->orderBy('name')->get();

        $modules = ModuleSetting::allModules();

        return view('admin.dashboard', compact(
            'lowStockMedications',
            'expiringSoon',
            'expiredMedications',
            'pendingRequests',
            'recentRequests',
            'categories',
            'modules'
        ));
    }
}
