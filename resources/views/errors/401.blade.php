@extends('errors.layout')

@section('title', '401 - Diperlukan Autentikasi')
@section('code', '401')
@section('badge_text', 'Autentikasi Diperlukan')
@section('badge_style', 'border-indigo-200 bg-indigo-50 text-indigo-800')
@section('dot_style', 'bg-indigo-500')
@section('code_style', 'bg-[linear-gradient(135deg,#4f46e5,#3730a3,#1e293b)] bg-clip-text text-transparent')

@section('heading', 'Silakan Masuk Terlebih Dahulu')

@section('message')
    Untuk mengakses informasi atau fitur ini, Anda harus masuk (login) menggunakan akun resmi yang terdaftar.
@endsection

@section('actions')
    <a href="{{ route('admin.login') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-pm-blue px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-600">
        Masuk (Login)
    </a>

    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#cbd5e1] bg-white px-5 py-3.5 text-sm font-semibold text-[#334155] shadow-sm transition hover:bg-slate-50">
        Kembali ke Beranda
    </a>
@endsection
