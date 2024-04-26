<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use App\Exports\SummaryListExportTest;
use App\Exports\PurchaseReportExportTest;
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
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class MultipleSheetExportTest implements
    WithMultipleSheets
    //FromCollection, 
    //WithHeadings,
    //WithMapping,
   // WithStyles,
   // WithProperties,
   // WithDrawings,
   // WithCustomStartCell,
   // WithTitle
{
    use Exportable;
    protected $data;
    
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        //return $this->data;
    }

   
    public function sheets(): array
    {
    
     $sheets = [
        new SummaryListExportTest($this->data),
        new PurchaseReportExportTest($this->data),
      ];
  
      return $sheets;  
    }

   
}
