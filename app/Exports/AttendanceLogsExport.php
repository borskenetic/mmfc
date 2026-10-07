<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AttendanceLogsExport implements FromCollection, WithHeadings
{
    protected $logs;

    // Accept filtered logs from controller
    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function collection()
    {
        return $this->logs->map(function ($log) {
            return [
                'lastname'    => $log->student?->lastname ?? $log->employee?->lastname ?? 'Unknown',
                'firstname'   => $log->student?->firstname ?? $log->employee?->firstname ?? 'Unknown',
                'course'      => $log->student?->course ?? $log->employee?->department ?? 'Unknown',
                'status'      => strtoupper($log->status),
                'scanned_at'  => $log->scanned_at
                    ? Carbon::parse($log->scanned_at)->format('Y-m-d h:i A')
                    : '—',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Last Name',
            'First Name',
            'Course / Dept',
            'Status',
            'Scanned At',
        ];
    }
}
