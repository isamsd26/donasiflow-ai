<?php

namespace App\Exports;

use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DonationsExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithMapping, WithCustomValueBinder, WithEvents, WithColumnFormatting, WithCustomStartCell, ShouldAutoSize
{
    use Exportable;

    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function query()
    {
        $query = Donation::query()
            ->with('campaign')
            ->orderBy('created_at', 'asc');

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (! empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }

        if (! empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        if (! empty($this->filters['q'])) {
            $keyword = trim((string) $this->filters['q']);
            $digits = preg_replace('/[^\d]/', '', $keyword);

            $query->where(function (Builder $q) use ($keyword, $digits) {
                $q->where('reference', 'like', "%{$keyword}%")
                    ->orWhere('donor_name', 'like', "%{$keyword}%")
                    ->orWhere('donor_email', 'like', "%{$keyword}%")
                    ->orWhere('donor_phone', 'like', "%{$keyword}%")
                    ->orWhereHas('campaign', fn ($campaignQuery) => $campaignQuery->where('title', 'like', "%{$keyword}%"));

                if ($digits !== '') {
                    $q->orWhere('amount', (int) $digits);
                }
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Tanggal Dibuat',
            'Tanggal Lunas',
            'No. Referensi',
            'Nama Donatur',
            'No. Telepon',
            'Nama Campaign',
            'Nominal Dasar',
            'Kode Unik',
            'Total Transfer',
            'Status',
            'Catatan Admin',
        ];
    }

    public function map($donation): array
    {
        return [
            $donation->created_at->format('Y-m-d H:i:s'),
            $donation->paid_at ? $donation->paid_at->format('Y-m-d H:i:s') : '-',
            $donation->reference,
            $donation->is_anonymous ? 'Anonim (' . $donation->donor_name . ')' : $donation->donor_name,
            $donation->donor_phone ?: '-',
            $donation->campaign ? $donation->campaign->title : '-',
            $donation->base_amount ?: $donation->amount,
            $donation->unique_code ?: 0,
            $donation->amount,
            str($donation->status)->headline(),
            $donation->admin_note ?: '-',
        ];
    }

    public function columnFormats(): array
    {
        $rupiahFormat = '"Rp" #,##0_-';

        return [
            'G' => $rupiahFormat,
            'H' => $rupiahFormat,
            'I' => $rupiahFormat,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) && in_array($cell->getColumn(), ['G', 'H', 'I']) && $cell->getRow() >= 8) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getDelegate()->getHighestRow();

                // Format Header Laporan
                $sheet->setCellValue('A1', 'LAPORAN DATA TRANSAKSI DONASI');
                $sheet->mergeCells('A1:K1');
                $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->setColor(new Color('FF0F5CA8'));
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getRowDimension(1)->setRowHeight(28);

                $sheet->setCellValue('A2', 'Yayasan DonasiFlow Nusantara');
                $sheet->mergeCells('A2:K2');
                $sheet->getStyle('A2')->getFont()->setSize(11)->setItalic(true)->setColor(new Color('FF475569'));
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getRowDimension(2)->setRowHeight(20);

                $sheet->getRowDimension(3)->setRowHeight(10);

                // Keterangan Filter Laporan
                if (! empty($this->filters['start_date']) && ! empty($this->filters['end_date'])) {
                    $startDate = Carbon::parse($this->filters['start_date'])->translatedFormat('d F Y');
                    $endDate = Carbon::parse($this->filters['end_date'])->translatedFormat('d F Y');
                    $periodeText = "{$startDate} s/d {$endDate}";
                } elseif (! empty($this->filters['start_date'])) {
                    $startDate = Carbon::parse($this->filters['start_date'])->translatedFormat('d F Y');
                    $periodeText = "Mulai {$startDate}";
                } elseif (! empty($this->filters['end_date'])) {
                    $endDate = Carbon::parse($this->filters['end_date'])->translatedFormat('d F Y');
                    $periodeText = "Sampai {$endDate}";
                } else {
                    $periodeText = 'Semua Periode (Seluruh Waktu)';
                }

                $statusText = ! empty($this->filters['status']) ? strtoupper($this->filters['status']) : 'Semua Status';
                $qText = ! empty($this->filters['q']) ? (string) $this->filters['q'] : '-';
                $exportTime = now()->translatedFormat('d F Y, H:i') . ' WIB';

                // Baris 4: Periode & Status
                $sheet->setCellValue('A4', 'Periode Donasi:');
                $sheet->setCellValue('B4', $periodeText);
                $sheet->setCellValue('H4', 'Status Transaksi:');
                $sheet->setCellValue('I4', $statusText);
                $sheet->getStyle('A4')->getFont()->setBold(true);
                $sheet->getStyle('H4')->getFont()->setBold(true);

                // Baris 5: Kata Kunci & Waktu Unduh
                $sheet->setCellValue('A5', 'Pencarian:');
                $sheet->setCellValue('B5', $qText);
                $sheet->setCellValue('H5', 'Waktu Unduh:');
                $sheet->setCellValue('I5', $exportTime);
                $sheet->getStyle('A5')->getFont()->setBold(true);
                $sheet->getStyle('H5')->getFont()->setBold(true);

                $sheet->getRowDimension(6)->setRowHeight(10);

                // Styling Header Tabel (Baris 7)
                $sheet->getRowDimension(7)->setRowHeight(26);
                $sheet->getStyle('A7:K7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF0F5CA8'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Menentukan baris data & total
                $hasData = $highestRow >= 8;
                $totalRow = $hasData ? ($highestRow + 1) : 8;

                // Set Alignment Center untuk kolom Tanggal, Referensi, Status
                if ($hasData) {
                    $sheet->getStyle("A8:C{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("J8:J{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Baris TOTAL
                $sheet->setCellValue('A' . $totalRow, 'TOTAL');
                $sheet->mergeCells("A{$totalRow}:F{$totalRow}");
                $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($hasData) {
                    $sheet->setCellValue('G' . $totalRow, "=SUM(G8:G{$highestRow})");
                    $sheet->setCellValue('H' . $totalRow, "=SUM(H8:H{$highestRow})");
                    $sheet->setCellValue('I' . $totalRow, "=SUM(I8:I{$highestRow})");
                } else {
                    $sheet->setCellValue('G' . $totalRow, 0);
                    $sheet->setCellValue('H' . $totalRow, 0);
                    $sheet->setCellValue('I' . $totalRow, 0);
                }

                $sheet->getStyle("A{$totalRow}:K{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF1F5F9'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Border untuk seluruh tabel (Baris 7 sampai Baris Total)
                $sheet->getStyle("A7:K{$totalRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCBD5E1'],
                        ],
                    ],
                ]);

                // Format Rupiah untuk baris TOTAL
                $rupiahFormat = '"Rp" #,##0_-';
                $sheet->getStyle("G{$totalRow}:I{$totalRow}")->getNumberFormat()->setFormatCode($rupiahFormat);
            },
        ];
    }
}
