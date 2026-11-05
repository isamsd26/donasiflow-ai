<?php

namespace App\Http\Controllers\Admin;

use App\Models\CampaignCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CampaignCategoryController extends AdminController
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('categories'),
            'categories' => CampaignCategory::query()
                ->withCount('campaigns')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('categories', 'Tambah Kategori Campaign', 'Buat kategori baru untuk mengelompokkan campaign di area publik dan CMS.'),
            'category' => new CampaignCategory(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:campaign_categories,slug'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        CampaignCategory::query()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug'] ?: $validated['name']),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('admin.categories')->with('success', 'Kategori campaign berhasil ditambahkan.');
    }

    public function edit(CampaignCategory $category): View
    {
        return view('admin.categories.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('categories', 'Edit Kategori Campaign', 'Perbarui nama, slug, urutan, dan status aktif kategori campaign.'),
            'category' => $category,
        ]);
    }

    public function update(Request $request, CampaignCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('campaign_categories', 'slug')->ignore($category->id)],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug'] ?: $validated['name']),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('admin.categories')->with('success', 'Kategori campaign berhasil diperbarui.');
    }

    public function destroy(CampaignCategory $category): RedirectResponse
    {
        if ($category->campaigns()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh campaign.');
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Kategori campaign berhasil dihapus.');
    }
}
