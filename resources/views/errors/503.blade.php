@extends('errors.layout')

@section('title', '503 - Sedang Dalam Pemeliharaan')
@section('code', '503')
@section('badge_text', 'Pemeliharaan Sistem')
@section('badge_style', 'border-sky-200 bg-sky-50 text-sky-800')
@section('dot_style', 'bg-sky-500')
@section('code_style', 'bg-[linear-gradient(135deg,#0284c7,#0369a1,#1e293b)] bg-clip-text text-transparent')

@section('heading', 'Platform Sedang Dalam Pemeliharaan Berkala')

@section('message')
    Kami sedang melakukan peningkatan performa dan keamanan sistem secara berkala untuk memberikan pengalaman donasi yang lebih aman, nyaman, dan transparan. Layanan akan segera aktif kembali beberapa saat lagi.
@endsection

@section('actions')
    <button type="button" onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 rounded-full bg-pm-blue px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-600">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Cek Ulang Status Sistem
    </button>
@endsection
