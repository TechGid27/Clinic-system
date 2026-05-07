<?php

namespace App\Http\Middleware;

use App\Models\ModuleSetting;
use Closure;
use Illuminate\Http\Request;

class CheckModule
{
    public function handle(Request $request, Closure $next, string $module): mixed
    {
        // Admin always bypasses module restrictions
        if ($request->user()?->isAdmin()) {
            return $next($request);
        }

        if (!ModuleSetting::isActive($module)) {
            abort(403, "The '{$module}' module is currently disabled.");
        }

        return $next($request);
    }
}
