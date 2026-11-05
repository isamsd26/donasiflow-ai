<?php

namespace App\Http\Controllers\Admin;

use App\Models\WaReplyTemplate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WaReplyController extends AdminController
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
        ]);

        $query = \App\Models\Donation::query()
            ->with('campaign')
            ->where('status', 'paid')
            ->latest('paid_at');

        if (! empty($filters['q'])) {
            $keyword = trim((string) $filters['q']);
            $query->where(function ($q) use ($keyword) {
                $q->where('donor_name', 'like', "%{$keyword}%")
                  ->orWhere('donor_phone', 'like', "%{$keyword}%")
                  ->orWhere('reference', 'like', "%{$keyword}%")
                  ->orWhereHas('campaign', fn ($cq) => $cq->where('title', 'like', "%{$keyword}%"));
            });
        }

        $donations = $query->paginate(10)->withQueryString();
        $templates = WaReplyTemplate::query()->latest()->get();

        return view('admin.wa-reply.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('wa-reply', 'Kirim Pesan WA', 'Balas pesan WA donatur secara cepat menggunakan template.'),
            'donations' => $donations,
            'templates' => $templates,
            'filters' => $filters,
        ]);
    }

    public function templates(): View
    {
        $templates = WaReplyTemplate::query()->latest()->get();

        return view('admin.wa-reply.templates', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('wa-templates', 'Kelola Template WA', 'Kelola template pesan WhatsApp untuk membalas donatur dengan cepat.'),
            'templates' => $templates,
        ]);
    }

    public function storeTemplate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        WaReplyTemplate::query()->create($validated);

        return back()->with('success', 'Template berhasil ditambahkan.');
    }

    public function updateTemplate(Request $request, WaReplyTemplate $template): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $template->update($validated);

        return back()->with('success', 'Template berhasil diperbarui.');
    }

    public function destroyTemplate(WaReplyTemplate $template): RedirectResponse
    {
        $template->delete();

        return back()->with('success', 'Template berhasil dihapus.');
    }
}
