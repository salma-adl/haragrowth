<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingReportExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $category;

    public function __construct(array $data, string $category)
    {
        $this->data = $data;
        $this->category = $category;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->data as $row) {
            if ($this->category === 'service') {
                $rows[] = [
                    $row['service'],
                    $row['booking_count'],
                    $row['client_count'],
                ];
            } elseif ($this->category === 'therapist') {
                $rows[] = [
                    $row['therapist'],
                    $row['service'],
                    $row['booking_count'],
                    $row['client_count'],
                ];
            }
        }
        return $rows;
    }

    public function headings(): array
    {
        if ($this->category === 'service') {
            return ['Nama Layanan', 'Total Booking', 'Total Klien Unik'];
        } elseif ($this->category === 'therapist') {
            return ['Nama Terapis', 'Nama Layanan', 'Jumlah Booking', 'Jumlah Klien Unik'];
        }
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
