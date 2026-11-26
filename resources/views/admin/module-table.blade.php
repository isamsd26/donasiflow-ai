@extends('layouts.admin')

@section('content')
    <section class="overflow-hidden rounded-xl border border-line bg-surface">
        <div class="flex flex-col gap-4 border-b border-line px-5 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-none text-ink">{{ $page['title'] }}</h2>
                <p class="mt-1.5 max-w-2xl text-xs leading-6 text-muted sm:text-sm">{{ $page['description'] }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach ($page['actions'] as $action)
                    <button type="button" class="rounded-lg border border-line-strong bg-canvas px-3.5 py-2 text-xs font-medium text-ink hover:bg-surface-subtle">
                        {{ $action }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-line bg-canvas text-xs text-muted">
                        @foreach ($page['columns'] as $column)
                            <th class="px-5 py-3 font-medium">{{ $column }}</th>
                        @endforeach
                        <th class="px-5 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($page['rows'] as $row)
                        <tr class="hover:bg-canvas/60">
                            @foreach ($row as $cell)
                                <td class="px-5 py-3.5 text-ink">{{ $cell }}</td>
                            @endforeach
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex gap-1.5">
                                    <button type="button" class="rounded-md border border-line-strong px-2.5 py-1 text-xs font-medium text-ink-soft hover:bg-surface-subtle">Edit</button>
                                    <button type="button" class="rounded-md bg-brand px-2.5 py-1 text-xs font-medium text-white hover:bg-brand-deep">Lihat</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
