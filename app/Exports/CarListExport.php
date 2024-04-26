<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;

class CarListExport implements  
    FromCollection, 
    WithHeadings,
    WithMapping,
    WithStyles,
    WithProperties,
   // WithDrawings,
    WithCustomStartCell,
    WithTitle
{
    use Exportable;
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
        //dd($this->data);
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($data): array
    {
        if(isset($data->in_or_out) && $data->in_or_out == 0){
            $in_or_out = 'IN YARD';
        }else{
            $in_or_out = 'YARD NOT RECEIVED';
        }

        $price = 'ASK';
        if ($data->fob_price > 0) {
            $price = $data->fob_price;
        }

        $shipment_date = ($data->shipment_date) ? date('d-M-Y', strtotime($data->shipment_date)) : 'N/A';
        $eta_date = (!empty($data->eta_date)) ? date('d-M-Y', strtotime($data->eta_date)) : 'N/A';
       
        $eta_crossed = 'N/A';
        if (!empty($data->eta_date)) {
            $today = date('Y-m-d');
            $datetime1 = date_create($data->eta_date);
            $datetime2 = date_create($today);
            $interval = date_diff($datetime1, $datetime2);
           $eta_crossed = $interval->days . ' Day(s)';
        }

        $pd_date = (!empty($data->salable_registered_day) && $data->salable_registered_day != '0000-00-00' && $data->salable_registered_day != '1970-01-01') ? date('d-M-Y', strtotime($data->salable_registered_day)) : 'N/A';
        $pdDays =  'N/A';
        if ($pd_date != 'N/A') {
            $today = date('Y-m-d');
            $pd1 = date_create($data->salable_registered_day);
            $pd2 = date_create($today);
            $pdinterval = date_diff($pd1, $pd2);
           $pdDays =  $pdinterval->days . ' Day(s)';
        }


        return [
            $data->car_id,
            $data->chassis_no,
            $price,
            $data->maker_name.' / '.$data->model_name,
            $data->color_name,
            $data->grouped_accessories,
            $data->registration_year.' / '.$data->registration_month,
            $data->engine_size.' CC',
            $data->steering_name,
            number_format($data->mileage).' KM',
            $data->country_name,
            $data->city_name,
            $shipment_date,
            $eta_date,
            $eta_crossed,
            $pd_date,
            $pdDays,
            ($data->bl_no) ? $data->bl_no : 'N/A',
            $data->auction_company_name,
            ($data->auction_date) ? $data->auction_date : 'N/A',
            ($data->territory_yard) ? str_replace('City 1', 'Kobe', $data->territory_yard) : 'N/A',
            $in_or_out

        ];
    }

    public function headings(): array
    {
        return [
            'Stock #',
            'Chassis No',
            'Price',
            'Make/Model',
            'Color',
            'Accessories',
            'Year/Month',
            'Engine',
            'Steering',
            'Mileage',
            'Country',
            'Location',
            'Shipment Date',
            'ETA Date',
            'ETA Crossed',
            'Purchase Date',
            'PD Days',
            'BL No',
            'Auction',
            'Auc. Date',
            'Territory',
            'Yard Location'
        ];
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function styles(Worksheet $sheet)
    {


        $sheet->mergeCells('A1:P2');
        $sheet->setCellValue('A1',  "JAN'S GROUP ");
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('fccf3a');  // text color
        
        $sheet->getStyle('A1')->getFont()->setSize(28);
        $sheet->getStyle('A1:P2')
         ->getFill()
         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
         ->getStartColor()
         ->setARGB('000000');

         $sheet->getStyle('A1:P2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        

         $sheet->mergeCells('A3:P4');
         $sheet->setCellValue('A3',  ' A Name Of Trust & Reliability ');
        $sheet->getStyle('A3')->getFont()->setSize(25);
         $sheet->getStyle('A3:P4')
          ->getFill()
          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
          ->getStartColor()
          ->setARGB('ffcb05');
 
          $sheet->getStyle('A3:P4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
 
          
        // end header
       // $sheet->setAutoFilter('B7:N7');

       $sheet->getStyle(6)->getFont()->setBold(true);
        $sheet->getStyle(7)->getFont()->setSize(12);
        $sheet->getStyle('A6:V6')
        ->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('ffcb05');
        
        //$sheet->getStyle('B7:N7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
       // $sheet->getStyle('B7:N7')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A6:V6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->getRowDimension('7')->setRowHeight(48);
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getStyle('F')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(10);
        $sheet->getColumnDimension('K')->setWidth(10);
        $sheet->getColumnDimension('L')->setWidth(20);
        //if (isset($GLOBALS['user_id']) && in_array($GLOBALS['user_id'], $GLOBALS['user_priviledge']) && $GLOBALS['user_id'] != 3) {
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(10);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(15);
        $sheet->getColumnDimension('Q')->setWidth(10);
        $sheet->getColumnDimension('R')->setWidth(15);
        $sheet->getStyle('R')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('S')->setWidth(18);
        $sheet->getColumnDimension('T')->setWidth(10);
        $sheet->getColumnDimension('U')->setWidth(15);
        $sheet->getColumnDimension('V')->setWidth(20);

        $sheet->setShowGridlines(true);

        $data = $this->data;
        $currentRow = 7;
        $lastRow = 0;

        foreach ($data as $car) {
            $sheet->getRowDimension($currentRow)->setRowHeight(48);
        //     $sheet->setCellValue("B{$currentRow}", $car->car_id);
        //     $sheet->setCellValue("C{$currentRow}", $car->drive_name);
        //     $sheet->setCellValue("D{$currentRow}", $car->maker_name);
        
        //     $sheet->getStyle("B{$currentRow}:N{$currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        //     $sheet->getStyle("B{$currentRow}:N{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
        //    // $lastRow = $currentRow - 1;
        //     //$sheet->getStyle("B{$lastRow}:N{$lastRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
            
             $currentRow++;
        }

    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/uploads/logo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        $drawing->setWidth(500);
        $drawing->setHeight(80);

        return $drawing;
    }

    public function properties(): array
    {
        return [
            'title' => 'Car List Export',
            'description' => 'Export of car list data',
        ];
    }

    public function title(): string
    {
        return 'jans Group';
    }
}
