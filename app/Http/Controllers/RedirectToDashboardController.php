<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectToDashboardController
{
    /**
     * Redirect user to the correct role dashboard.
     */
    public function __invoke(Request $request): Response
    {
        $roleName = Auth::user()?->role?->name;

        return match ($roleName) {
            'admin' => redirect()->route('dashboard'),

            'guru' => redirect()->route('teacher.reading_report.today'),
            'murid' => redirect()->route('student.reading.today'),
            default => redirect()->route('dashboard'),
        };
    }
}

