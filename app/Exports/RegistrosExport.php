<?php

namespace App\Exports;

use App\Models\Registro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// class RegistrosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
class RegistrosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $sede_id;
    protected $sede_nombre;
    protected $sede_fecha;
    
    public function __construct($sede_id = null)
    {
        $this->sede_id = $sede_id;
        
        // Asignar fecha según la sede
        $fechas = [
            'Xalapa' => '12-mayo-2026',
            'Papantla' => '19-mayo-2026',
            'Orizaba' => '08-mayo-2026',
            'Santiago Tuxtla' => '06-mayo-2026'
        ];
        
        if ($this->sede_id) {
            $sede = \App\Models\Sede::find($this->sede_id);
            $this->sede_nombre = $sede ? $sede->nombre : 'SIN SEDE';
            $this->sede_fecha = $fechas[$this->sede_nombre] ?? date('d-m-Y');
        } else {
            $this->sede_nombre = 'TODAS LAS SEDES';
            $this->sede_fecha = date('d-m-Y');
        }
    }
    
    public function collection()
    {
        $query = Registro::with(['ayuntamiento', 'cargo']);
        
        if ($this->sede_id) {
            $query->where('sede_id', $this->sede_id);
        }
        
        return $query->get();
    }
    
    public function headings(): array
    {
        return [
            'No.',
            'AYUNTAMIENTO',
            'NOMBRE COMPLETO',
            'CARGO EN EL AYUNTAMIENTO',
            'No. TELÉFONO CELULAR',
            'No. TELÉFONO DE OFICINA',
            'CORREO ELECTRÓNICO PERSONAL E INSTITUCIONAL',
            'FIRMA'
        ];
    }
    
    public function map($registro): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        // Combinar correos
        $correos = $registro->correo_personal;
        if ($registro->correo_institucional) {
            $correos .= ' / ' . $registro->correo_institucional;
        }
        
        return [
            $rowNumber,
            $registro->ayuntamiento->nombre ?? 'N/A',
            $registro->nombre_completo,
            $registro->cargo->nombre ?? 'N/A',
            $registro->telefono,
            $registro->telefono_oficina ?? '',
            $correos,
            ''
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate(); // Obtener el objeto nativo de PhpSpreadsheet
                
                // Insertar 5 filas para la cabecera
                $event->sheet->insertNewRowBefore(1, 5);
                
                // ==========================================
                // LOGOS
                // ==========================================
                
                // Logo 1: Poder Legislativo (izquierda)
                $drawing1 = new Drawing();
                $drawing1->setName('Poder Legislativo');
                $drawing1->setDescription('Logo Poder Legislativo');
                $drawing1->setPath(public_path('images/logo PODER LEGISLATIVO.png'));
                $drawing1->setHeight(60);
                $drawing1->setCoordinates('A1');
                $drawing1->setOffsetX(10);
                $drawing1->setOffsetY(5);
                $drawing1->setWorksheet($sheet);
                
                // Logo 2: Congreso Legislatura (derecha)
                $drawing2 = new Drawing();
                $drawing2->setName('Congreso Legislatura');
                $drawing2->setDescription('Logo Congreso Legislatura');
                $drawing2->setPath(public_path('images/LOGO LXVII SLOGAN.png'));
                $drawing2->setHeight(60);
                $drawing2->setCoordinates('H1');
                $drawing2->setOffsetX(-60);
                $drawing2->setOffsetY(5);
                $drawing2->setWorksheet($sheet);
                
                // ==========================================
                // TEXTO INSTITUCIONAL (centrado)
                // ==========================================
                
                // Fila 1: Texto principal (entre los logos)
                $event->sheet->mergeCells('B1:G1');
                $event->sheet->setCellValue('B1', 'H. CONGRESO DEL ESTADO.');
                $event->sheet->getStyle('B1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
                ]);
                
                // Fila 2: Secretaría
                $event->sheet->mergeCells('B2:G2');
                $event->sheet->setCellValue('B2', 'SECRETARÍA DE FISCALIZACIÓN.');
                $event->sheet->getStyle('B2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
                ]);
                
                // Fila 3: Título del evento
                $event->sheet->mergeCells('A3:H3');
                $event->sheet->setCellValue('A3', '"Veracruz y la Fiscalización: Capacitación por una Gestión Responsable 2026"');
                $event->sheet->getStyle('A3')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
                ]);
                
                // Fila 4: Sede y fecha
                $event->sheet->mergeCells('A4:H4');
                $event->sheet->setCellValue('A4', "SEDE: {$this->sede_nombre}, VER.  {$this->sede_fecha}");
                $event->sheet->getStyle('A4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
                ]);
                
                // Ajustar altura de filas
                $sheet->getRowDimension(1)->setRowHeight(70);
                $sheet->getRowDimension(2)->setRowHeight(25);
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(4)->setRowHeight(25);
                
                // Aplicar estilos a los encabezados de la tabla (fila 6)
                $event->sheet->getStyle('A6:H6')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E79']
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
                ]);
                
                // Aplicar bordes a toda la tabla
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle('A6:H' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
                
                // Columna de firma con fondo gris
                $event->sheet->getStyle('H7:H' . $lastRow)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2']
                    ]
                ]);
                
                // Centrar números en columna A
                $event->sheet->getStyle('A7:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}