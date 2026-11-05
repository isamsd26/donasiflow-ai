@extends('errors.layout')

@section('title', '500 - Terjadi Kendala Internal Sistem')
@section('code', '500')
@section('badge_text', 'Kesalahan Server Internal')
@section('badge_style', 'border-rose-200 bg-rose-50 text-rose-800')
@section('dot_style', 'bg-rose-500')
@section('code_style', 'bg-[linear-gradient(135deg,#e11d48,#9f1239,#1e293b)] bg-clip-text text-transparent')

@section('heading', 'Terjadi Kendala pada Sistem Kami')

@section('message')
    Mohon maaf atas ketidaknyamanan ini. Terjadi kendala teknis saat memproses permintaan Anda di server. Tim teknis DonasiFlow Nusantara telah mencatat kejadian ini untuk segera diperiksa.
@endsection

@section('extra_content')
    <div class="mt-4 w-full rounded-2xl border border-slate-200 bg-slate-50/80 p-4 text-left text-xs sm:text-sm text-slate-700">
        <p class="font-semibold text-slate-900 mb-1">Apa yang bisa Anda lakukan saat ini?</p>
        <ul class="list-disc pl-5 space-y-1 text-slate-600">
            <li>Tunggu beberapa saat dan klik tombol <strong>Muat Ulang</strong> di bawah.</li>
            <li>Jika Anda baru saja mengirim formulir donasi, silakan periksa nominal dan pastikan format nomor WhatsApp Anda sudah sesuai.</li>
            <li>Bila Anda telah mentransfer dana, donasi Anda aman. Gunakan menu <strong>Cek Status Donasi</strong> atau hubungi WhatsApp kami.</li>
        </ul>
    </div>
@endsection

@section('actions')
    <button type="button" onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 rounded-full bg-pm-blue px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-600">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Muat Ulang Halaman
    </button>

    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-white px-5 py-3.5 text-sm font-semibold text-[#334155] shadow-sm transition hover:bg-slate-50">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Kembali ke Beranda
    </a>

    <a href="{{ url('/donation/check') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-[#f8fafc] px-5 py-3.5 text-sm font-semibold text-[#334155] transition hover:bg-slate-100">
        Cek Status Donasi
    </a>
@endsection
