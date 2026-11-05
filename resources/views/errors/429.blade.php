@extends('errors.layout')

@section('title', '429 - Terlalu Banyak Permintaan')
@section('code', '429')
@section('badge_text', 'Batas Frekuensi Terlampaui')
@section('badge_style', 'border-yellow-200 bg-yellow-50 text-yellow-800')
@section('dot_style', 'bg-yellow-500')
@section('code_style', 'bg-[linear-gradient(135deg,#eab308,#ca8a04,#1e293b)] bg-clip-text text-transparent')

@section('heading', 'Terlalu Banyak Permintaan dalam Waktu Singkat')

@section('message')
    Sistem keamanan kami mendeteksi terlalu banyak aktivitas dalam rentang waktu yang sangat cepat dari perangkat Anda. Demi menjaga stabilitas dan kenyamanan seluruh pengguna, silakan tunggu beberapa saat sebelum mencoba kembali.
@endsection

@section('actions')
    <button type="button" onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 rounded-full bg-pm-blue px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-600">
        Coba Lagi Sekarang
    </button>

    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-white px-5 py-3.5 text-sm font-semibold text-[#334155] shadow-sm transition hover:bg-slate-50">
        Kembali ke Beranda
    </a>
@endsection
