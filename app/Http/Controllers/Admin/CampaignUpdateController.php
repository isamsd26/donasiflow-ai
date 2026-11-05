<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campaign;
use App\Models\CampaignUpdate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampaignUpdateController extends AdminController
{
    public function index(): View
    {
        return view('admin.updates.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('updates'),
            'updates' => CampaignUpdate::query()
                ->with('campaign')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.updates.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('updates', 'Tambah Update Campaign', 'Tulis kabar terbaru yang akan tampil di detail campaign publik.'),
            'update' => new CampaignUpdate(),
            'campaigns' => Campaign::query()->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        CampaignUpdate::query()->create($this->validateUpdate($request));

        return redirect()->route('admin.updates')->with('success', 'Update campaign berhasil ditambahkan.');
    }

    public function edit(CampaignUpdate $update): View
    {
        return view('admin.updates.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('updates', 'Edit Update Campaign', 'Perbarui isi kabar terbaru dan status publikasinya.'),
            'update' => $update,
            'campaigns' => Campaign::query()->orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, CampaignUpdate $update): RedirectResponse
    {
        $update->update($this->validateUpdate($request));

        return redirect()->route('admin.updates')->with('success', 'Update campaign berhasil diperbarui.');
    }

    public function destroy(CampaignUpdate $update): RedirectResponse
    {
        $update->delete();

        return redirect()->route('admin.updates')->with('success', 'Update campaign berhasil dihapus.');
    }

    protected function validateUpdate(Request $request): array
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'exists:campaigns,id'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        return [
            'campaign_id' => $validated['campaign_id'],
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'] ?? null,
            'featured_image' => $validated['featured_image'] ?? null,
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'published_at' => $validated['published_at'] ?? null,
        ];
    }
}
