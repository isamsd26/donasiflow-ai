@extends('layouts.admin')

@section('content')
    <section class="rounded-xl border border-line bg-surface">
        <div class="flex flex-col gap-4 border-b border-line px-5 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-brand">Modul Administrasi</p>
                <h2 class="mt-1 font-display text-3xl leading-none text-ink">{{ $page['title'] }}</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-muted">{{ $page['description'] }}</p>
            </div>

            <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white">
                Tambah Campaign
            </a>
        </div>

        <div class="overflow-x-auto px-5 py-5">
            <table class="min-w-full text-left text-sm">
                <thead class="text-muted">
                    <tr class="border-b border-line">
                        <th class="pb-3 font-semibold">Judul</th>
                        <th class="pb-3 font-semibold">Kategori</th>
                        <th class="pb-3 font-semibold">Publish</th>
                        <th class="pb-3 font-semibold">Publik</th>
                        <th class="pb-3 font-semibold">Target</th>
                        <th class="pb-3 font-semibold">Terkumpul</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($campaigns as $campaign)
                        <tr class="border-b border-line last:border-b-0">
                            <td class="py-3">
                                <p class="font-medium text-ink">{{ $campaign->title }}</p>
                                <p class="mt-1 text-xs text-muted">{{ $campaign->slug }}</p>
                            </td>
                            <td class="py-3 text-ink">{{ $campaign->category?->name ?? '-' }}</td>
                            <td class="py-3"><span class="rounded-full bg-brand-soft px-3 py-1 text-xs font-semibold text-brand">{{ str($campaign->publish_status)->headline() }}</span></td>
                            <td class="py-3"><span class="rounded-full bg-[#f8f1ff] px-3 py-1 text-xs font-semibold text-[#6941c6]">{{ str($campaign->public_status)->headline() }}</span></td>
                            <td class="py-3 text-ink">Rp {{ number_format((float) $campaign->target_amount, 0, ',', '.') }}</td>
                            <td class="py-3 text-ink">Rp {{ number_format((float) $campaign->current_amount, 0, ',', '.') }}</td>
                            <td class="py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="rounded-full border border-line px-3 py-1.5 text-xs font-medium text-ink-soft">Edit</a>
                                    <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" onsubmit="return confirm('Hapus campaign ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-[#fff2f2] px-3 py-1.5 text-xs font-medium text-[#b42318]">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-sm text-muted">Belum ada campaign yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-line px-5 py-4">
            {{ $campaigns->links() }}
        </div>
    </section>
@endsection
