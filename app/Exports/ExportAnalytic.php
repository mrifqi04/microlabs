<?php

namespace App\Exports;

use App\Models\Analytic;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ExportAnalytic implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    public function headings(): array
    {
        return  [
            ['MICROLABS SYSTEM'],
            ['Print Date : ' . Carbon::now()],
            [''],
            ['No', 'ID Sample', 'Jenis Sample', 'Deskripsi', 'No.Batch/QC/Titik Sampling/Lokasi', 'Instrument', 'Suhu', 'Status', 'Jam', 'PIC']
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 10,
            'D' => 15,
            'E' => 10,
            'F' => 10,
            'G' => 5,
            'H' => 10,
            'I' => 20,
            'J' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set the page orientation to landscape
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getParent()->getDefaultStyle()->getFont()->setSize(10);
        $sheet->getStyle('A1')->getFont()->setSize(24); // Set font size to 24
        $sheet->getStyle('A1')->getFont()->setBold(true); // Make the font bold
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center'); // Center align the text

        // Merge cells for the big header
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A2:J2')->getFont()->setBold(true);
        $sheet->getStyle('A4:J4')->getFont()->setBold(true);
        return [];

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('analytics as an')
            ->select([
                'sam.no_sample',
                'tp.type',
                'sam.deskripsi_sample',
                'sam.no_batch',
                'ins.nama_instrument',
                'an.temperature',
                'an.status',
                'an.created_at',
                'us.name',
                'an.scan_in',
                'an.scan_out',
                'an.scan_done'
            ])
            ->join('samples as sam', 'sam.id', 'an.sample_id')
            ->join('type_testings as tp', 'tp.id', 'sam.type_id')
            ->join('instruments as ins', 'ins.id', 'an.instrument_id')
            ->join('users as us', 'us.id', 'an.pic')
            ->orderBy('an.created_at', 'ASC')
            ->get();

        return $data;
    }

    public function map($row): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $row->no_sample,
            $row->type,
            $row->deskripsi_sample,
            $row->no_batch,
            $row->nama_instrument,
            $row->temperature,
            $row->scan_in ? 'Loading' : ($row->scan_out ? 'Unloading' : 'Done'),
            $row->created_at,
            $row->name
        ];
    }
}
