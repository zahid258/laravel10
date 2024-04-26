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
use App\Traits\GeneralTrait;
class PurchaseReportExportTest implements  
    //FromCollection, 
    //WithHeadings,
    //WithMapping,
    WithStyles,
    WithProperties,
   // WithDrawings,
    WithCustomStartCell,
    WithTitle
{
    use Exportable;
    use GeneralTrait;
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        //dd($this->data);
    }

    public function collection()
    {
        //return $this->data;
    }

    


    public function startCell(): string
    {
        return 'A6';
    }

    public function styles(Worksheet $sheet)
    {
      
       
        $sheet->setCellValueByColumnAndRow(1, 4, 'NAME');
        $sheet->setCellValueByColumnAndRow(2, 4, 'CHASSIS');

        $sheet->setCellValueByColumnAndRow(3, 4, 'LAST Week Purchase');
        $row = 2;
         if($this->data['type'] =='inventory'){
            $inv_letter = "E";
            $demand_letter = "F";
            $letter = "H";
            $heading = '';
            }else{
            $inv_letter = "E";
            $demand_letter = "E";
            $letter = "E";
            $heading = 'Mukechi';
            }	
        $col = 4;
		
        
        $lastletter = 'D';
        // header start
        $sheet->mergeCells('A1:'.$lastletter.'3');
        $sheet->setCellValue('A1', $this->data['country']->hr_name. ' TOTAL STOCK ('. $heading .')');
        $sheet->getStyle('A1')->getFont()->setSize(40);
         $sheet->getStyle('A1:'.$lastletter.'3')
         ->getFill()
         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
         ->getStartColor()
         ->setARGB('92D050');

        

         $sheet->getStyle('A1:'.$lastletter.'3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // end header
    
         
         $sheet->getStyle('A4:'.$lastletter.'4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
         $sheet->getStyle('A4:'.$lastletter.'4')
         ->getFill()
         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
         ->getStartColor()
         ->setARGB('538DD5');
         
        $sheet->getStyle(4)->getFont()->setBold(true);
        $sheet->getStyle(4)->getFont()->setSize(15);

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(27);
       // $sheet->getStyle('C4:'.$lastletter.'4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension($lastletter)->setAutoSize(false);
                   
        if($this->data['type'] =='inventory'){
        //  $sheet->getColumnDimension($lastletter)->setWidth(15);
        //  $sheet->getColumnDimension($in_last_letter)->setAutoSize(false);
        //  $sheet->getColumnDimension($in_last_letter)->setWidth(20);

        //  $sheet->getColumnDimension($dmnd_last_letter)->setAutoSize(false);
        //  $sheet->getColumnDimension($dmnd_last_letter)->setWidth(15);
         }else{
          $sheet->getColumnDimension($lastletter)->setWidth(15);
         }

        $sheet->setShowGridlines(true);

    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/uploads/logo.png'));
        
        

        return $drawing;
    }

    public function properties(): array
    {
        return [
            'title' => 'Purchase Report Export',
            'description' => 'Export of Purchase Report data',
        ];
    }

    public function title(): string
    {
        if($this->data['type'] =='inventory'){
            return $this->data['country']->hr_name.' Purchase Report';
           }else{
               return $this->data['country']->hr_name.' Purchase Report Mukechi';
           }
    }
}
