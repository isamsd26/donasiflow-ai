@extends('errors.layout')

@section('title', '419 - Sesi Halaman Berakhir')
@section('code', '419')
@section('badge_text', 'Sesi Form Telah Kedaluwarsa')
@section('badge_style', 'border-amber-200 bg-amber-50 text-amber-800')
@section('dot_style', 'bg-amber-500')
@section('code_style', 'bg-[linear-gradient(135deg,#d97706,#b45309,#1e293b)] bg-clip-text text-transparent')

@section('heading', 'Sesi Keamanan Halaman Telah Berakhir')

@section('message')
    Untuk menjaga keamanan data transaksi donasi Anda, sistem secara otomatis mengakhiri sesi form yang tidak aktif dalam waktu lama. Silakan muat ulang halaman untuk memperbarui sesi dan melanjutkan donasi.
@endsection

@section('actions')
    <button type="button" onclick="window.history.back(); setTimeout(function(){ window.location.reload(); }, 300);" class="inline-flex items-center justify-center gap-2 rounded-full bg-pm-blue px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-600">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Kembali & Muat Ulang Form
    </button>

    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-white px-5 py-3.5 text-sm font-semibold text-[#334155] shadow-sm transition hover:bg-slate-50">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Ke Beranda
    </a>
@endsection
