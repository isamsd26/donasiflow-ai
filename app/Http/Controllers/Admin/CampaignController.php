<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CampaignController extends AdminController
{
    public function index(): View
    {
        return view('admin.campaigns.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('campaigns'),
            'campaigns' => Campaign::query()
                ->with('category')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.campaigns.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('campaigns', 'Tambah Campaign', 'Lengkapi informasi utama campaign agar siap tampil di website publik.'),
            'campaign' => new Campaign([
                'publish_status' => 'draft',
                'public_status' => 'active',
                'cta_label' => 'Donasi Sekarang',
            ]),
            'categories' => CampaignCategory::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Campaign::query()->create($this->validateCampaign($request));

        return redirect()->route('admin.campaigns')->with('success', 'Campaign berhasil ditambahkan.');
    }

    public function edit(Campaign $campaign): View
    {
        return view('admin.campaigns.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('campaigns', 'Edit Campaign', 'Perbarui konten, status, target, dan informasi campaign yang tampil di halaman publik.'),
            'campaign' => $campaign,
            'categories' => CampaignCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $campaign->update($this->validateCampaign($request, $campaign));

        return redirect()->route('admin.campaigns')->with('success', 'Campaign berhasil diperbarui.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()->route('admin.campaigns')->with('success', 'Campaign berhasil dihapus.');
    }

    protected function validateCampaign(Request $request, ?Campaign $campaign = null): array
    {
        $validated = $request->validate([
            'campaign_category_id' => ['required', 'exists:campaign_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'organizer_name' => ['required', 'string', 'max:255'],
            'featured_badge' => ['nullable', 'string', 'max:255'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'cta_label' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'focus_area' => ['nullable', 'string', 'max:255'],
            'distribution_plan' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'target_amount' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'publish_status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'public_status' => ['required', Rule::in(['active', 'completed', 'hidden'])],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        return [
            'campaign_category_id' => $validated['campaign_category_id'],
            'title' => $validated['title'],
            'slug' => $campaign?->slug ?: Str::slug($validated['title']),
            'organizer_name' => $validated['organizer_name'],
            'featured_badge' => $validated['featured_badge'] ?? null,
            'featured_image' => $this->storeCampaignImage($request->file('featured_image'), $campaign),
            'cta_label' => $validated['cta_label'] ?: 'Donasi Sekarang',
            'location' => $validated['location'] ?? null,
            'focus_area' => $validated['focus_area'] ?? null,
            'distribution_plan' => $validated['distribution_plan'] ?? null,
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'] ?? null,
            'target_amount' => $validated['target_amount'] ?? 0,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'publish_status' => $validated['publish_status'],
            'public_status' => $validated['public_status'],
            'display_order' => $validated['display_order'] ?? 0,
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
        ];
    }

    protected function storeCampaignImage(?UploadedFile $file, ?Campaign $campaign = null): ?string
    {
        if (! $file) {
            return $campaign?->featured_image;
        }

        if ($campaign?->featured_image && ! Str::startsWith($campaign->featured_image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($campaign->featured_image);
        }

        return $file->store('campaigns', 'public');
    }
}
