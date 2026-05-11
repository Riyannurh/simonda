<?php

namespace App\Http\Controllers\Pendamping;

use App\Http\Controllers\Controller;
use App\Models\KategoriUsaha;
use App\Models\MasterKelasUsaha;
use App\Models\Usaha;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    private function buildQuery(Request $request)
    {
        $query = Usaha::with(['pemilik', 'kelasUsaha', 'kategoriUsaha', 'legalitas'])
            ->where('id_pendata', auth()->id());

        if ($request->kelas_usaha)    $query->where('id_kelas_usaha', $request->kelas_usaha);
        if ($request->kategori_usaha) $query->where('id_kategori_usaha', $request->kategori_usaha);
        if ($request->kecamatan)      $query->where('kecamatan_usaha', $request->kecamatan);
        if ($request->jenis_kelamin)  $query->whereHas('pemilik', fn($q) => $q->where('jenis_kelamin', $request->jenis_kelamin));
        if ($request->tgl_dari)       $query->whereDate('created_at', '>=', $request->tgl_dari);
        if ($request->tgl_sampai)     $query->whereDate('created_at', '<=', $request->tgl_sampai);

        return $query;
    }

    public function index(Request $request)
    {
        $dataUmkm = $this->buildQuery($request)->get();

        $totalUmkm    = $dataUmkm->count();
        $totalKaryawan = $dataUmkm->sum('karyawan');
        $totalOmset    = $dataUmkm->sum('omset_bulanan_rp');

        $chartKelas = $dataUmkm->groupBy('id_kelas_usaha')->map(fn($g) => [
            'nama'  => $g->first()->kelasUsaha->nama ?? 'Belum Ditentukan',
            'total' => $g->count(),
        ])->values();

        $chartKecamatan = $dataUmkm->groupBy('kecamatan_usaha')->map(fn($g, $kec) => [
            'kecamatan' => $kec ?: '-', 'total' => $g->count(),
        ])->sortByDesc('total')->take(10)->values();

        $chartGender = [
            'laki'      => $dataUmkm->filter(fn($u) => optional($u->pemilik)->jenis_kelamin === 'L')->count(),
            'perempuan' => $dataUmkm->filter(fn($u) => optional($u->pemilik)->jenis_kelamin === 'P')->count(),
        ];

        $chartKategori = $dataUmkm->groupBy('id_kategori_usaha')->map(fn($g) => [
            'nama'  => $g->first()->kategoriUsaha->nama ?? 'Lainnya',
            'total' => $g->count(),
        ])->sortByDesc('total')->take(5)->values();

        $kelasUsaha    = MasterKelasUsaha::where('is_active', true)->get();
        $kategoriUsaha = KategoriUsaha::all();
        $kecamatanList = Usaha::where('id_pendata', auth()->id())
            ->distinct()->pluck('kecamatan_usaha')->filter()->sort()->values();

        return view('back-end.pendamping.laporan.index', compact(
            'dataUmkm', 'totalUmkm', 'totalKaryawan', 'totalOmset',
            'chartKelas', 'chartKecamatan', 'chartGender', 'chartKategori',
            'kelasUsaha', 'kategoriUsaha', 'kecamatanList'
        ));
    }

    public function exportExcel(Request $request)
    {
        $dataUmkm = $this->buildQuery($request)->with('sosialMedia')->get();
        $pendata  = auth()->user();
        $filename = 'laporan-umkm-' . now()->format('Ymd-His') . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle(mb_substr($pendata->name, 0, 31));

        $titleStyle = [
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF1F3864']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $groupStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF34495E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF888888']]],
        ];
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2C3E50']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF888888']]],
        ];
        $borderThin = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCCCCCC']]]];
        $evenFill   = ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF0F4F8']]];

        // Row 1: Judul
        $sheet->mergeCells('A1:V1');
        $sheet->setCellValue('A1', 'LAPORAN DATA UMKM - ' . strtoupper($pendata->name));
        $sheet->getStyle('A1')->applyFromArray($titleStyle);
        $sheet->getRowDimension(1)->setRowHeight(24);

        // Row 2-4: Info
        $sheet->setCellValue('A2', 'Pendamping');  $sheet->setCellValue('B2', $pendata->name);
        $sheet->setCellValue('A3', 'Total Data');  $sheet->setCellValue('B3', $dataUmkm->count() . ' UMKM');
        $sheet->setCellValue('A4', 'Dicetak');     $sheet->setCellValue('B4', now()->format('d/m/Y H:i') . ' WIB');
        $sheet->getStyle('A2:A4')->getFont()->setBold(true);

        // Row 6: Group header
        $groups = [
            'A6:H6' => 'DATA PEMILIK',
            'I6:R6' => 'DATA USAHA',
            'S6:T6' => 'LEGALITAS',
            'U6:V6' => 'SOSIAL MEDIA',
        ];
        foreach ($groups as $range => $label) {
            [$start] = explode(':', $range);
            $sheet->mergeCells($range);
            $sheet->setCellValue($start, $label);
            $sheet->getStyle($range)->applyFromArray($groupStyle);
        }
        $sheet->getRowDimension(6)->setRowHeight(20);

        // Row 7: Header kolom
        $columns = [
            ['A', 'No', 5],   ['B', 'NIK', 18],          ['C', 'Nama Pemilik', 22],
            ['D', 'JK', 10],  ['E', 'Tempat Lahir', 16],  ['F', 'Tgl Lahir', 13],
            ['G', 'No. HP', 14], ['H', 'Alamat Pemilik', 35],
            ['I', 'Nama Usaha', 25], ['J', 'Merek', 16],  ['K', 'Kategori', 16],
            ['L', 'Kelas', 14], ['M', 'Kecamatan', 18],   ['N', 'Desa', 18],
            ['O', 'Alamat Usaha', 30], ['P', 'Karyawan', 10],
            ['Q', 'Omset/Bulan (Rp)', 18], ['R', 'Total Aset (Rp)', 18],
            ['S', 'Jenis Legalitas', 20], ['T', 'Nomor Legalitas', 22],
            ['U', 'Platform Sosmed', 18], ['V', 'URL/Username', 25],
        ];
        foreach ($columns as [$col, $label, $width]) {
            $sheet->setCellValue("{$col}7", $label);
            $sheet->getColumnDimension($col)->setWidth($width);
        }
        $sheet->getStyle('A7:V7')->applyFromArray($headerStyle);
        $sheet->getRowDimension(7)->setRowHeight(32);

        // Data rows
        $row = 8;
        $no  = 1;
        foreach ($dataUmkm as $item) {
            $pemilik   = $item->pemilik;
            $legalitas = $item->legalitas;
            $sosmed    = $item->sosialMedia;
            $maxRows   = max(1, $legalitas->count(), $sosmed->count());

            for ($r = 0; $r < $maxRows; $r++) {
                $cur = $row + $r;
                if ($r === 0) {
                    $tglLahir = $pemilik?->tanggal_lahir ? Carbon::parse($pemilik->tanggal_lahir)->format('d/m/Y') : '-';
                    $alamat   = implode(', ', array_filter([$pemilik?->alamat_pemilik, $pemilik?->desa_pemilik, $pemilik?->kecamatan_pemilik, $pemilik?->kabupaten_pemilik])) ?: '-';

                    $sheet->setCellValue("A{$cur}", $no);
                    $sheet->setCellValueExplicit("B{$cur}", $pemilik?->nik ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("C{$cur}", $pemilik?->nama ?? '-');
                    $sheet->setCellValue("D{$cur}", ($pemilik?->jenis_kelamin ?? '') === 'L' ? 'Laki-laki' : 'Perempuan');
                    $sheet->setCellValue("E{$cur}", $pemilik?->tempat_lahir ?? '-');
                    $sheet->setCellValue("F{$cur}", $tglLahir);
                    $sheet->setCellValueExplicit("G{$cur}", $pemilik?->hp ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("H{$cur}", $alamat);
                    $sheet->setCellValue("I{$cur}", $item->nama_usaha);
                    $sheet->setCellValue("J{$cur}", $item->merek ?? '-');
                    $sheet->setCellValue("K{$cur}", $item->kategoriUsaha?->nama ?? '-');
                    $sheet->setCellValue("L{$cur}", $item->kelasUsaha?->nama ?? '-');
                    $sheet->setCellValue("M{$cur}", $item->kecamatan_usaha ?? '-');
                    $sheet->setCellValue("N{$cur}", $item->desa_usaha ?? '-');
                    $sheet->setCellValue("O{$cur}", $item->alamat_usaha ?? '-');
                    $sheet->setCellValue("P{$cur}", (int)($item->karyawan ?? 0));
                    $sheet->setCellValue("Q{$cur}", (float)($item->omset_bulanan_rp ?? 0));
                    $sheet->setCellValue("R{$cur}", (float)($item->aset_rp ?? 0));
                    $sheet->getStyle("P{$cur}")->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle("Q{$cur}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getStyle("R{$cur}")->getNumberFormat()->setFormatCode('"Rp "#,##0');

                    if ($maxRows > 1) {
                        $end = $row + $maxRows - 1;
                        foreach (['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R'] as $mc) {
                            $sheet->mergeCells("{$mc}{$row}:{$mc}{$end}");
                        }
                        $sheet->getStyle("A{$row}:V{$end}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                    }
                }

                $leg = $legalitas->get($r);
                if ($leg) {
                    $sheet->setCellValue("S{$cur}", $leg->jenis ?? '-');
                    $sheet->setCellValue("T{$cur}", $leg->nomor ?? '-');
                }
                $sm = $sosmed->get($r);
                if ($sm) {
                    $sheet->setCellValue("U{$cur}", $sm->platform ?? '-');
                    $sheet->setCellValue("V{$cur}", $sm->url ?? '-');
                }

                if ($no % 2 === 0) $sheet->getStyle("A{$cur}:V{$cur}")->applyFromArray($evenFill);
                $sheet->getStyle("A{$cur}:V{$cur}")->applyFromArray($borderThin);
                $sheet->getStyle("A{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("P{$cur}:R{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }

            $row += $maxRows;
            $no++;
        }

        $sheet->freezePane('A8');
        $sheet->setAutoFilter('A7:V7');

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $dataUmkm = $this->buildQuery($request)->get();
        $pendata  = auth()->user();
        $filename = 'laporan-umkm-' . now()->format('Ymd-His') . '.pdf';

        $pdf = Pdf::loadView('back-end.pendamping.laporan.pdf', compact('dataUmkm', 'pendata'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
