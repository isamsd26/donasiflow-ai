<?php

namespace App\Http\Controllers\Admin;

use App\Models\StaticPage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaticPageController extends AdminController
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('pages'),
            'pages' => StaticPage::query()
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('pages', 'Tambah Halaman Statis', 'Buat halaman informasi yang akan tampil di website publik.'),
            'staticPage' => new StaticPage(['is_published' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        StaticPage::query()->create($this->validatePage($request));

        return redirect()->route('admin.pages')->with('success', 'Halaman statis berhasil ditambahkan.');
    }

    public function edit(StaticPage $page): View
    {
        return view('admin.pages.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('pages', 'Edit Halaman Statis', 'Perbarui konten informasi dan SEO halaman publik.'),
            'staticPage' => $page,
        ]);
    }

    public function update(Request $request, StaticPage $page): RedirectResponse
    {
        $page->update($this->validatePage($request, $page));

        return redirect()->route('admin.pages')->with('success', 'Halaman statis berhasil diperbarui.');
    }

    public function destroy(StaticPage $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages')->with('success', 'Halaman statis berhasil dihapus.');
    }

    protected function validatePage(Request $request, ?StaticPage $page = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('static_pages', 'slug')->ignore($page?->id)],
            'intro' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        return [
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug'] ?: $validated['title']),
            'intro' => $validated['intro'] ?? null,
            'content' => $validated['content'] ?? null,
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'published_at' => $validated['published_at'] ?? null,
        ];
    }
}
