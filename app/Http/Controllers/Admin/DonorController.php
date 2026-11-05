<?php

namespace App\Http\Controllers\Admin;

use App\Models\Donor;
use Illuminate\Contracts\View\View;

class DonorController extends AdminController
{
    public function index(): View
    {
        return view('admin.donors.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('donors'),
            'donors' => Donor::query()
                ->withCount('donations')
                ->withSum('donations', 'amount')
                ->latest()
                ->paginate(12),
        ]);
    }
}
