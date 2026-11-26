@extends('layouts.admin')

@section('content')
    <section class="rounded-xl border border-line bg-surface">
        <div class="border-b border-line px-5 py-5">
            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand">Modul Administrasi</p>
            <h2 class="mt-1 font-display text-3xl leading-none text-ink">{{ $page['title'] }}</h2>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-muted">{{ $page['description'] }}</p>
        </div>

        <div class="overflow-x-auto px-5 py-5">
            <table class="min-w-full text-left text-sm">
                <thead class="text-muted">
                    <tr class="border-b border-line">
                        <th class="pb-3 font-semibold">Nama</th>
                        <th class="pb-3 font-semibold">Email</th>
                        <th class="pb-3 font-semibold">Telepon</th>
                        <th class="pb-3 font-semibold">Total Donasi</th>
                        <th class="pb-3 font-semibold">Jumlah Donasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donors as $donor)
                        <tr class="border-b border-line last:border-b-0">
                            <td class="py-3 font-medium text-ink">{{ $donor->name }}</td>
                            <td class="py-3 text-ink">{{ $donor->email ?: '-' }}</td>
                            <td class="py-3 text-ink">{{ $donor->phone ?: '-' }}</td>
                            <td class="py-3 text-ink">Rp {{ number_format((float) ($donor->donations_sum_amount ?? 0), 0, ',', '.') }}</td>
                            <td class="py-3 text-ink">{{ $donor->donations_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-muted">Belum ada donor yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-line px-5 py-4">
            {{ $donors->links() }}
        </div>
    </section>
@endsection
