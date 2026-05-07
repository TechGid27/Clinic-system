<?php

namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Medication;
use App\Models\MedicationRequest;
use App\Models\MedicationRequestItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RequestController extends Controller
{
    // Status constants
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_DISBURSED = 'disbursed';
    const STATUS_REJECTED  = 'rejected';

    public function index(Request $request): View
    {
        $query = MedicationRequest::with(['user', 'items.medication', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15);

        $counts = [
            'pending'   => MedicationRequest::where('status', self::STATUS_PENDING)->count(),
            'approved'  => MedicationRequest::where('status', self::STATUS_APPROVED)->count(),
            'disbursed' => MedicationRequest::where('status', self::STATUS_DISBURSED)->count(),
            'rejected'  => MedicationRequest::where('status', self::STATUS_REJECTED)->count(),
        ];

        return view('admin.requests.index', compact('requests', 'counts'));
    }

    public function create(): View
    {
        $medications = Medication::with('category')
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($m) => $m->category?->name ?? 'Uncategorized');

        return view('admin.requests.create', compact('medications'));
    }

    /**
     * Store a new PENDING request (no stock deducted yet).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_name'           => 'required|string|max:255',
            'course'                 => 'required|string|max:255',
            'year_level'             => 'required|string|max:50',
            'reason'                 => 'required|string',
            'items'                  => 'required|array|min:1',
            'items.*.medication_id'  => 'required|exists:medications,id',
            'items.*.quantity'       => 'required|integer|min:1',
        ]);

        $items = array_values(array_filter(
            $validated['items'],
            fn ($i) => !empty($i['medication_id']) && !empty($i['quantity'])
        ));

        if (empty($items)) {
            return back()->withErrors(['items' => 'Please select at least one medication with quantity.'])->withInput();
        }

        // Validate quantities against current stock
        foreach ($items as $item) {
            $med = Medication::findOrFail($item['medication_id']);
            if ($item['quantity'] > $med->quantity) {
                return back()->withErrors([
                    'items' => "Quantity for {$med->name} exceeds available stock ({$med->quantity}).",
                ])->withInput();
            }
        }

        DB::transaction(function () use ($validated, $items) {
            $req = MedicationRequest::create([
                'student_name' => $validated['student_name'],
                'course'       => $validated['course'],
                'year_level'   => $validated['year_level'],
                'reason'       => $validated['reason'],
                'status'       => self::STATUS_PENDING,
            ]);

            foreach ($items as $item) {
                MedicationRequestItem::create([
                    'medication_request_id' => $req->id,
                    'medication_id'         => $item['medication_id'],
                    'quantity'              => $item['quantity'],
                    'quantity_disbursed'    => 0,
                ]);
            }
        });

        return redirect()->route('requests.index')
            ->with('success', 'Request submitted and is pending approval.');
    }

    /**
     * Approve a pending request (still no stock deducted).
     */
    public function approve(MedicationRequest $medicationRequest): RedirectResponse
    {
        if ($medicationRequest->status !== self::STATUS_PENDING) {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $medicationRequest->update([
            'status'      => self::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Request approved.');
    }

    /**
     * Reject a pending request.
     */
    public function reject(MedicationRequest $medicationRequest): RedirectResponse
    {
        if ($medicationRequest->status !== self::STATUS_PENDING) {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $medicationRequest->update(['status' => self::STATUS_REJECTED]);

        return redirect()->route('requests.index')
            ->with('success', 'Request rejected.');
    }

    /**
     * Disburse an approved request — deducts stock and records disbursements.
     */
    public function disburse(MedicationRequest $medicationRequest): RedirectResponse
    {
        if ($medicationRequest->status !== self::STATUS_APPROVED) {
            return back()->with('error', 'Only approved requests can be disbursed.');
        }

        // Re-validate stock before disbursing
        foreach ($medicationRequest->items as $item) {
            $med = Medication::findOrFail($item->medication_id);
            if ($item->quantity > $med->quantity) {
                return back()->with('error',
                    "Insufficient stock for {$med->name}. Available: {$med->quantity} {$med->unit}."
                );
            }
        }

        try {
            DB::transaction(function () use ($medicationRequest) {
                foreach ($medicationRequest->items as $item) {
                    $medication = Medication::findOrFail($item->medication_id);
                    $medication->decrement('quantity', $item->quantity);

                    $item->update(['quantity_disbursed' => $item->quantity]);

                    Disbursement::create([
                        'user_id'                => $medicationRequest->user_id ?? auth()->id(),
                        'medication_id'          => $medication->id,
                        'medication_request_id'  => $medicationRequest->id,
                        'quantity'               => $item->quantity,
                        'disbursed_by'           => auth()->id(),
                    ]);
                }

                $medicationRequest->update(['status' => self::STATUS_DISBURSED]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('requests.index')
            ->with('success', 'Medications disbursed and stock updated.');
    }
}
