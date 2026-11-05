<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campaign;
use App\Models\HeroBanner;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HeroBannerController extends AdminController
{
    public function index(): View
    {
        return view('admin.banners.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('banners'),
            'banners' => HeroBanner::query()
                ->with('campaign')
                ->orderBy('sort_order')
                ->latest('id')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.banners.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('banners', 'Tambah Hero Banner', 'Siapkan konten hero unggulan untuk homepage publik.'),
            'banner' => new HeroBanner(['is_active' => true]),
            'campaigns' => Campaign::query()->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        HeroBanner::query()->create($this->validateBanner($request));

        return redirect()->route('admin.banners')->with('success', 'Hero banner berhasil ditambahkan.');
    }

    public function edit(HeroBanner $banner): View
    {
        return view('admin.banners.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('banners', 'Edit Hero Banner', 'Perbarui visual unggulan dan tautan campaign dari hero homepage.'),
            'banner' => $banner,
            'campaigns' => Campaign::query()->orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, HeroBanner $banner): RedirectResponse
    {
        $banner->update($this->validateBanner($request));

        return redirect()->route('admin.banners')->with('success', 'Hero banner berhasil diperbarui.');
    }

    public function destroy(HeroBanner $banner): RedirectResponse
    {
        $banner->delete();

        return redirect()->route('admin.banners')->with('success', 'Hero banner berhasil dihapus.');
    }

    protected function validateBanner(Request $request): array
    {
        $validated = $request->validate([
            'campaign_id' => ['nullable', 'exists:campaigns,id'],
            'title' => ['required', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'image_path' => ['nullable', 'image', 'max:4096'],
            'cta_label' => ['nullable', 'string', 'max:255'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return [
            'campaign_id' => $validated['campaign_id'] ?? null,
            'title' => $validated['title'],
            'eyebrow' => $validated['eyebrow'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'image_path' => $this->storeBannerImage($request->file('image_path'), $request->route('banner')),
            'cta_label' => $validated['cta_label'] ?? null,
            'cta_url' => $validated['cta_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];
    }

    protected function storeBannerImage(?UploadedFile $file, ?HeroBanner $banner = null): ?string
    {
        if (! $file) {
            return $banner?->image_path;
        }

        if ($banner?->image_path && ! Str::startsWith($banner->image_path, ['http://', 'https://'])) {
            Storage::disk('public')->delete($banner->image_path);
        }

        return $file->store('hero-banners', 'public');
    }
}
