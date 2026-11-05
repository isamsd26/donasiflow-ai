<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TestimonialController extends AdminController
{
    public function index(): View
    {
        return view('admin.testimonials.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('testimonials'),
            'testimonials' => Testimonial::query()
                ->orderBy('sort_order')
                ->latest('id')
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('testimonials', 'Tambah Testimoni', 'Tambahkan testimoni publik agar homepage memiliki blok trust yang bisa dikelola dari CMS.'),
            'testimonial' => new Testimonial(['rating' => 5, 'is_active' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Testimonial::query()->create($this->validateTestimonial($request));

        return redirect()->route('admin.testimonials')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('testimonials', 'Edit Testimoni', 'Perbarui testimoni publik, rating, urutan, dan status tampilnya.'),
            'testimonial' => $testimonial,
        ]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->validateTestimonial($request));

        return redirect()->route('admin.testimonials')->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials')->with('success', 'Testimoni berhasil dihapus.');
    }

    protected function validateTestimonial(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return [
            'name' => $validated['name'],
            'role' => $validated['role'] ?? null,
            'location' => $validated['location'] ?? null,
            'quote' => $validated['quote'],
            'rating' => $validated['rating'] ?? 5,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];
    }
}
