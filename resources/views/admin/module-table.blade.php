@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <section class="rounded-[26px] bg-white shadow-[0_14px_28px_rgba(15,23,42,0.08)]">
            <div class="flex flex-col gap-4 border-b border-[#e2e8f0] px-5 py-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-pm-blue">Modul Administrasi</p>
                    <h2 class="mt-1 font-display text-3xl leading-none text-[#1e293b]">{{ $page['title'] }}</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-[#64748b]">{{ $page['description'] }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($page['actions'] as $action)
                        <button type="button" class="rounded-full border border-[#d9e4ef] bg-[#f8fafc] px-4 py-2 text-sm font-medium text-[#334155]">
                            {{ $action }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="overflow-x-auto px-5 py-5">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-[#64748b]">
                        <tr class="border-b border-[#e9eff6]">
                            @foreach ($page['columns'] as $column)
                                <th class="pb-3 font-semibold">{{ $column }}</th>
                            @endforeach
                            <th class="pb-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($page['rows'] as $row)
                            <tr class="border-b border-[#f1f5f9] last:border-b-0">
                                @foreach ($row as $cell)
                                    <td class="py-3 text-[#1e293b]">{{ $cell }}</td>
                                @endforeach
                                <td class="py-3 text-right">
                                    <div class="inline-flex gap-2">
                                        <button type="button" class="rounded-full border border-[#e2e8f0] px-3 py-1.5 text-xs font-medium text-[#334155]">Edit</button>
                                        <button type="button" class="rounded-full bg-pm-blue px-3 py-1.5 text-xs font-medium text-white">Lihat</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
