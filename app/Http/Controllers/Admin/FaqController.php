<?php

namespace App\Http\Controllers\Admin;

use App\Models\FaqItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaqController extends AdminController
{
    public function index(): View
    {
        return view('admin.faqs.index', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('faqs'),
            'faqs' => FaqItem::query()
                ->orderBy('sort_order')
                ->latest('id')
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.faqs.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('faqs', 'Tambah FAQ', 'Tambahkan pertanyaan dan jawaban yang akan tampil terstruktur di halaman FAQ publik.'),
            'faq' => new FaqItem(['is_active' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        FaqItem::query()->create($this->validateFaq($request));

        return redirect()->route('admin.faqs')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(FaqItem $faq): View
    {
        return view('admin.faqs.form', [
            'admin' => $this->adminShell(),
            'page' => $this->adminPage('faqs', 'Edit FAQ', 'Perbarui pertanyaan, jawaban, urutan, dan status tampil FAQ publik.'),
            'faq' => $faq,
        ]);
    }

    public function update(Request $request, FaqItem $faq): RedirectResponse
    {
        $faq->update($this->validateFaq($request));

        return redirect()->route('admin.faqs')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(FaqItem $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs')->with('success', 'FAQ berhasil dihapus.');
    }

    protected function validateFaq(Request $request): array
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return [
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];
    }
}
