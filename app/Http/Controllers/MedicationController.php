<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Medication::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('low_stock')) {
            $query->whereColumn('quantity', '<=', 'low_stock_threshold');
        }
        if ($request->filled('expiring')) {
            $query->whereNotNull('expiry_date')
                  ->whereDate('expiry_date', '<=', now()->addDays(30))
                  ->whereDate('expiry_date', '>=', now());
        }
        if ($request->filled('expired')) {
            $query->whereNotNull('expiry_date')
                  ->whereDate('expiry_date', '<', now());
        }

        $medications = $query->orderBy('name')->paginate(15);
        $categories  = Category::orderBy('name')->get();

        // Counts for alert badges
        $expiringCount = Medication::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(30))
            ->whereDate('expiry_date', '>=', now())
            ->count();
        $expiredCount = Medication::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', now())
            ->count();

        $archivedCount = Medication::onlyTrashed()->count();

        return view('admin.medications.index', compact('medications', 'categories', 'expiringCount', 'expiredCount', 'archivedCount'));
    }

    public function create(): View|RedirectResponse
    {
        $categories = Category::orderBy('name')->get();
        if ($categories->isEmpty()) {
            return redirect()->route('categories.index')
                ->with('error', 'Please create at least one category before adding medications.');
        }
        return view('admin.medications.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'medications'                        => 'required|array|min:1',
            'medications.*.category_id'          => 'required|exists:categories,id',
            'medications.*.name'                 => 'required|string|max:255',
            'medications.*.description'          => 'nullable|string',
            'medications.*.quantity'             => 'required|integer|min:0',
            'medications.*.low_stock_threshold'  => 'required|integer|min:0',
            'medications.*.unit'                 => 'nullable|string|max:50',
            'medications.*.production_date'      => 'nullable|date',
            'medications.*.expiry_date'          => 'required|date|after:today',
        ]);

        foreach ($validated['medications'] as $med) {
            $med['unit'] = $med['unit'] ?? 'pcs';
            Medication::create($med);
        }

        return redirect()->route('medications.index')
            ->with('success', 'Medications added successfully.');
    }

    public function edit(Medication $medication): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.medications.edit', compact('medication', 'categories'));
    }

    public function update(Request $request, Medication $medication): RedirectResponse
    {
        $validated = $request->validate([
            'category_id'         => 'required|exists:categories,id',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'quantity'            => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'unit'                => 'nullable|string|max:50',
            'production_date'     => 'nullable|date',
            'expiry_date'         => 'required|date',
        ]);

        $validated['unit'] = $validated['unit'] ?? 'pcs';
        $medication->update($validated);

        return redirect()->route('medications.index')
            ->with('success', 'Medication updated successfully.');
    }

    /**
     * Soft-delete (archive) the medication.
     */
    public function destroy(Medication $medication): RedirectResponse
    {
        $medication->delete(); // soft delete — goes to archive
        return redirect()->route('medications.index')
            ->with('success', "\"{$medication->name}\" has been archived.");
    }

    /**
     * Show archived medications.
     */
    public function archive(Request $request): View
    {
        $query = Medication::onlyTrashed()->with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $medications = $query->orderBy('deleted_at', 'desc')->paginate(15);

        return view('admin.medications.archive', compact('medications'));
    }

    /**
     * Restore a soft-deleted medication back to active inventory.
     */
    public function restore(int $id): RedirectResponse
    {
        $medication = Medication::onlyTrashed()->findOrFail($id);
        $medication->restore();

        return redirect()->route('medications.archive')
            ->with('success', "\"{$medication->name}\" has been restored to inventory.");
    }

    /**
     * Permanently delete a medication from the archive.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $medication = Medication::onlyTrashed()->findOrFail($id);
        $name = $medication->name;
        $medication->forceDelete();

        return redirect()->route('medications.archive')
            ->with('success', "\"{$name}\" has been permanently deleted.");
    }
}
