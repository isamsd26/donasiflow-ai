@extends('errors.layout')

@section('title', ($exception->getStatusCode() ?? 'Error') . ' - Kendala Layanan')
@section('code', $exception->getStatusCode() ?? 'Error')
@section('badge_text', 'Pemberitahuan Sistem')
@section('badge_style', 'border-slate-200 bg-slate-50 text-slate-800')
@section('dot_style', 'bg-slate-500')
@section('code_style', 'bg-[linear-gradient(135deg,#059669,#047857,#1e293b)] bg-clip-text text-transparent')

@section('heading', 'Terjadi Kendala pada Permintaan Anda')

@section('message')
    {{ $exception->getMessage() ?: 'Permintaan Anda belum dapat diproses oleh sistem. Silakan kembali ke beranda atau hubungi tim bantuan kami.' }}
@endsection
