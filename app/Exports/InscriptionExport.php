<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Class InscriptionExport
 * @package App\Exports
 */
class InscriptionExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting
{
    use Exportable;

    protected $eventId;
    /**
     * @var string
     */
    private $fileName = 'Reporte.xlsx';

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;

    /**
     * Optional headers
     */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    /**
     * InscriptionExport constructor.
     * @param $eventId
     */
    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Código inscripción',
            'Evento',
            'Nombres',
            'Apellidos',
            'Email',
            "# Celular",
            "Género",
            "Fecha de Nacimiento",
            'Estado',
            'Fecha inscripción',
            'Estado del pago',
        ];
    }

    /**
     * @param mixed $item
     * @return array
     */
    public function map($item): array
    {
        $birthDate= $item->customer->birth_date ? $item->customer->birth_date->format('Y-m-d') : '' ;
        return [
            $item->id,
            $item->event->title,
            $item->customer->name,
            $item->customer->last_name,
            $item->customer->email,
            $item->customer->phone,
            Customer::translate_gender[$item->customer->gender],
            $birthDate,
            Inscription::translate_status[$item->status],
            $item->created_at->format('Y-m-d H:i'),
            Inscription::translate_payment_status[$item->payment_status],
        ];
    }
    public function columnFormats(): array
    {
        return [
            'E' =>NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $query = Inscription::query()
            ->with('event', 'customer');
        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }
        return $query;
    }
}
