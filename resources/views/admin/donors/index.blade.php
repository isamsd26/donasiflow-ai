@extends('layouts.admin')

@section('content')
    <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
        <div class="border-b border-[#e2e8f0] px-5 py-5">
            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Modul Administrasi</p>
            <h2 class="mt-1 font-display text-3xl leading-none text-[#1e293b]">{{ $page['title'] }}</h2>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-[#64748b]">{{ $page['description'] }}</p>
        </div>

        <div class="overflow-x-auto px-5 py-5">
            <table class="min-w-full text-left text-sm">
                <thead class="text-[#64748b]">
                    <tr class="border-b border-[#e9eff6]">
                        <th class="pb-3 font-semibold">Nama</th>
                        <th class="pb-3 font-semibold">Email</th>
                        <th class="pb-3 font-semibold">Telepon</th>
                        <th class="pb-3 font-semibold">Total Donasi</th>
                        <th class="pb-3 font-semibold">Jumlah Donasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donors as $donor)
                        <tr class="border-b border-[#f1f5f9] last:border-b-0">
                            <td class="py-3 font-medium text-[#1e293b]">{{ $donor->name }}</td>
                            <td class="py-3 text-[#1e293b]">{{ $donor->email ?: '-' }}</td>
                            <td class="py-3 text-[#1e293b]">{{ $donor->phone ?: '-' }}</td>
                            <td class="py-3 text-[#1e293b]">Rp {{ number_format((float) ($donor->donations_sum_amount ?? 0), 0, ',', '.') }}</td>
                            <td class="py-3 text-[#1e293b]">{{ $donor->donations_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-[#64748b]">Belum ada donor yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-[#e2e8f0] px-5 py-4">
            {{ $donors->links() }}
        </div>
    </section>
@endsection
