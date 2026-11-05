<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $donation->reference }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #1e293b; text-transform: uppercase; }
        .info-grid { width: 100%; margin-bottom: 30px; }
        .info-grid td { padding: 5px; vertical-align: top; }
        .info-label { font-weight: bold; color: #64748b; width: 150px; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; text-transform: uppercase; font-size: 12px; }
        .status-paid { background-color: #d1fae5; color: #065f46; border: 2px solid #059669; }
        .status-pending { background-color: #fef3c7; color: #92400e; border: 2px solid #d97706; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-table th, .details-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .details-table th { background-color: #f8fafc; color: #1e293b; }
        .footer { text-align: center; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">{{ $donation->status === 'paid' ? 'KUITANSI DONASI' : 'TAGIHAN DONASI' }}</div>
            <p>Referensi: <strong>{{ $donation->reference }}</strong></p>
        </div>

        <table class="info-grid">
            <tr>
                <td class="info-label">Tanggal</td>
                <td>: {{ $donation->created_at->format('d F Y H:i') }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Donatur</td>
                <td>: {{ $donation->is_anonymous ? 'Anonim' : $donation->donor_name }}</td>
            </tr>
            <tr>
                <td class="info-label">Status</td>
                <td>: 
                    @if($donation->status === 'paid')
                        <span class="status-badge status-paid">LUNAS PADA {{ $donation->paid_at ? $donation->paid_at->format('d/m/Y') : '-' }}</span>
                    @else
                        <span class="status-badge status-pending">MENUNGGU PEMBAYARAN</span>
                    @endif
                </td>
            </tr>
        </table>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th style="text-align: right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Donasi Program:</strong><br>
                        {{ $donation->campaign?->title ?? '-' }}
                    </td>
                    <td style="text-align: right">
                        Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th style="text-align: right">Total Akhir</th>
                    <th style="text-align: right; font-size: 18px;">Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        @if($donation->status === 'pending')
        <div style="background-color: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
            <h4 style="margin-top: 0; color: #1e293b;">Instruksi Pembayaran</h4>
            <p>Silakan transfer tepat sebesar <strong>Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}</strong> ke rekening berikut:</p>
            <ul>
                <li>Bank: <strong>{{ $donation->payment_bank ?? 'Bank Syariah Nasional' }}</strong></li>
                <li>No. Rekening: <strong>{{ $donation->payment_account_number ?? '-' }}</strong></li>
                <li>Atas Nama: <strong>{{ $donation->payment_account_name ?? '-' }}</strong></li>
            </ul>
            <p style="color: #d97706; font-size: 14px;">Mohon transfer sebelum {{ $donation->expires_at ? $donation->expires_at->format('d M Y H:i') : '-' }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Terima kasih atas donasi Anda. Dokumen ini sah dan di-generate otomatis oleh sistem.</p>
        </div>
    </div>
</body>
</html>
