@extends('errors.layout')

@section('title', '403 - Akses Dibatasi')
@section('code', '403')
@section('badge_text', 'Akses Tidak Diizinkan')
@section('badge_style', 'border-red-200 bg-red-50 text-red-800')
@section('dot_style', 'bg-red-500')
@section('code_style', 'bg-[linear-gradient(135deg,#dc2626,#991b1b,#1e293b)] bg-clip-text text-transparent')

@section('heading', 'Akses ke Halaman Ini Dibatasi')

@section('message')
    Anda tidak memiliki izin atau otorisasi untuk membuka halaman ini. Area ini khusus untuk pengelola resmi Yayasan atau memerlukan kredensial login yang sah.
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-pm-blue px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-600">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Kembali ke Beranda
    </a>

    <a href="{{ route('admin.login') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-white px-5 py-3.5 text-sm font-semibold text-[#334155] shadow-sm transition hover:bg-slate-50">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        Login Pengelola (Admin)
    </a>
@endsection
