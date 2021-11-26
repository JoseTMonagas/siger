<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CierreEstadoPago implements WithMultipleSheets
{
    use Exportable;

    protected $estadoPago;
    protected $detViveres;
    protected $detGuia;

    public function __construct(array $estadoPago, array $detViveres, array $detGuia)
    {
        $this->estadoPago = $estadoPago;
        $this->detViveres = $detViveres;
        $this->detGuia = $detGuia;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new ArrayExport($this->estadoPago);
        $sheets[] = new ArrayExport($this->detViveres);
        $sheets[] = new ArrayExport($this->detGuia);

        return $sheets;
    }
}
