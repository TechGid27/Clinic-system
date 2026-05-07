<?php

namespace App\Http\Controllers;

use App\Models\ModuleSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staffList = User::where('role', User::ROLE_STAFF)->orderBy('name')->get();
        $modules   = ModuleSetting::orderBy('module')->get();

        return view('admin.staff.index', compact('staffList', 'modules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => 'required|string|min:2|max:100',
            'email'                 => 'required|email|max:255|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => User::ROLE_STAFF,
            'is_active' => true,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff account created.');
    }

    public function toggleActive(User $staff): RedirectResponse
    {
        if ($staff->role !== User::ROLE_STAFF) {
            abort(403);
        }

        $staff->update(['is_active' => !$staff->is_active]);

        $status = $staff->is_active ? 'activated' : 'deactivated';
        return redirect()->route('staff.index')->with('success', "Staff account {$status}.");
    }

    public function destroy(User $staff): RedirectResponse
    {
        if ($staff->role !== User::ROLE_STAFF) {
            abort(403);
        }

        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff account removed.');
    }

    public function toggleModule(string $module): RedirectResponse
    {
        $allowed = ['categories', 'medications', 'requests', 'reports'];

        if (!in_array($module, $allowed)) {
            abort(404);
        }

        ModuleSetting::toggle($module);

        return redirect()->route('staff.index')->with('success', "Module '{$module}' updated.");
    }
}
