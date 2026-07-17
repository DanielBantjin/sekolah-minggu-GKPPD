<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardPerRoleController extends Controller
{
    public function __invoke(Request $request): View
    {
        $role = Auth::user()?->role()->first();


        $roleName = $role?->name;

        return match ($roleName) {
            'admin' => view('admin.overview'),
            'guru' => view('teacher.overview'),
            'murid' => view('student.overview'),
            default => view('dashboard'),
        };
    }
}

