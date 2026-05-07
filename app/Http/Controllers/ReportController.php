<?php

namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Medication;
use App\Models\MedicationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function restockReport(): View
    {
        [$lowStock, $mostUsed, $expiringSoon, $expired] = $this->restockData();
        return view('admin.reports.restock', compact('lowStock', 'mostUsed', 'expiringSoon', 'expired'));
    }

    public function restockPdf()
    {
        [$lowStock, $mostUsed, $expiringSoon, $expired] = $this->restockData();
        $pdf = Pdf::loadView('admin.reports.pdf.restock', compact('lowStock', 'mostUsed', 'expiringSoon', 'expired'))
                  ->setPaper('a4', 'portrait');
        return $pdf->download('restock-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function restockData(): array
    {
        $lowStock = Medication::with('category')
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->orderBy('quantity')->get();

        $mostUsed = Disbursement::selectRaw('medication_id, SUM(quantity) as total_used')
            ->groupBy('medication_id')->orderByDesc('total_used')
            ->with('medication.category')->whereHas('medication')->take(20)->get();

        $expiringSoon = Medication::with('category')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(30))
            ->whereDate('expiry_date', '>=', now())
            ->orderBy('expiry_date')->get();

        $expired = Medication::with('category')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', now())
            ->orderBy('expiry_date')->get();

        return [$lowStock, $mostUsed, $expiringSoon, $expired];
    }

    public function visitsReport(Request $request): View
    {
        [$visits, $totalVisits, $todayVisits, $thisMonth] = $this->visitsData($request);
        return view('admin.reports.visits', compact('visits', 'totalVisits', 'todayVisits', 'thisMonth'));
    }

    public function visitsPdf(Request $request)
    {
        $query = MedicationRequest::with(['items.medication', 'approver'])->latest();
        $this->applyVisitsFilters($query, $request);

        $visits      = $query->get();
        $totalVisits = MedicationRequest::count();
        $todayVisits = MedicationRequest::whereDate('created_at', today())->count();
        $thisMonth   = MedicationRequest::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)->count();

        $pdf = Pdf::loadView('admin.reports.pdf.visits', compact('visits', 'totalVisits', 'todayVisits', 'thisMonth'))
                  ->setPaper('a4', 'landscape');
        return $pdf->download('visit-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function visitsData(Request $request): array
    {
        $query = MedicationRequest::with(['items.medication', 'approver'])->latest();
        $this->applyVisitsFilters($query, $request);

        $visits      = $query->paginate(20)->withQueryString();
        $totalVisits = MedicationRequest::count();
        $todayVisits = MedicationRequest::whereDate('created_at', today())->count();
        $thisMonth   = MedicationRequest::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)->count();

        return [$visits, $totalVisits, $todayVisits, $thisMonth];
    }

    private function applyVisitsFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('student_name', 'like', "%{$s}%")
                  ->orWhere('course', 'like', "%{$s}%")
                  ->orWhere('reason', 'like', "%{$s}%");
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    }
}
