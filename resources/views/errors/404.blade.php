@extends('errors.layout')

@section('title', '404 - Halaman Tidak Ditemukan')
@section('code', '404')
@section('badge_text', 'Halaman Tidak Ditemukan')
@section('badge_style', 'border-amber-200 bg-amber-50 text-amber-800')
@section('dot_style', 'bg-amber-500')
@section('code_style', 'bg-[linear-gradient(135deg,#059669,#047857,#0284c7)] bg-clip-text text-transparent')

@section('heading', 'Halaman yang Anda Cari Tidak Ditemukan')

@section('message')
    Tautan yang Anda tuju mungkin sudah kedaluwarsa, telah dipindahkan, atau program campaign telah selesai. Mari lanjutkan kebaikan melalui campaign aktif lainnya.
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-pm-blue px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-600">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Kembali ke Beranda
    </a>

    <a href="{{ url('/campaign') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-white px-5 py-3.5 text-sm font-semibold text-[#334155] shadow-sm transition hover:bg-slate-50">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        Pilih Campaign Lain
    </a>

    <a href="{{ url('/donation/check') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-[#f8fafc] px-5 py-3.5 text-sm font-semibold text-[#334155] transition hover:bg-slate-100">
        Cek Status Donasi
    </a>
@endsection
