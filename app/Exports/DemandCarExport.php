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

class DemandCarExport implements  
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
        return $this->data;
    }

   

    public function startCell(): string
    {
        return 'A6';
    }

    public function styles(Worksheet $sheet)
    {
        $letter = "A";
        $endletter = "B";
        $snm = 1;
        $spur = 2;
        $firstmergColumns = [];
        $secondMergeColumns = [];
        $sc=1;
        $colNamePur = [];

         $in = 0;
         $total = count($this->data['rightsCountry'])*2;
           for($i=0;$i<$total;$i++){
                       
                       $lastcoll = $letter;
                        $startletter = $letter;
                        $nexttletter = $endletter;
                        $firstmergColumns[$sc] = $lastcoll;
                        $secondMergeColumns[$sc] = $nexttletter;
                        if(!empty($this->data['rightsCountry'][$i]->id)){
                        $colNamePur[] = ['name'=>$snm,'pur'=>$spur,'country_id'=>$this->data['rightsCountry'][$i]->id];
                        
                        }else{
                            $colNamePur[] = ['name'=>$snm,'pur'=>$spur,'country_id'=>''];
                        }
    
                        $in++;
                        $letter++;
                        $endletter++;
                        $snm = $snm+2;
                        $spur = $spur+2;
                        $sc++;
               
           }

          
        //dd($colNamePur);
    
        $sheet->mergeCells('A1:'.$lastcoll.'3');
        // add some text
        $sheet->setCellValue('A1','DEMAND CARS');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(22);
        $sheet->getStyle('A1:'.$lastcoll.'3')
        ->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('538DD5');

        $sheet->getStyle('A1:'.$lastcoll.'3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        
           
           $t = 1;
           $first_merger_col = [];
           $sec_merger_col = [];
          
           foreach($firstmergColumns as $key=>$value){
            if(!empty($firstmergColumns[$t])){
                $first_merger_col[] = $firstmergColumns[$t];
                $sec_merger_col[] =   $secondMergeColumns[$t];
                
            }
    
             $t=$t+2;
           }
           
         // dd($sec_merger_col);
          
           $col = 1;      
                    foreach($this->data['rightsCountry'] as $key => $country_res)
                    {
                           // if(!empty($first_merger_col[$key])){
                            $sheet->getColumnDimension($first_merger_col[$key])->setWidth(30);
                            $sheet->getStyle($first_merger_col[$key].'4')->getFont()->setBold(true);
                            $sheet->getStyle($first_merger_col[$key].'4'.':'.$sec_merger_col[$key].'4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->mergeCells($first_merger_col[$key].'4'.':'.$sec_merger_col[$key].'4')->setCellValueByColumnAndRow($col, 4, strtoupper($country_res->hr_name));
                            $sheet->getStyle($first_merger_col[$key].'4'.':'.$sec_merger_col[$key].'4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $sheet->getStyle($first_merger_col[$key].'4'.':'.$sec_merger_col[$key].'4')
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB('edc039');

                                $sheet->getStyle($first_merger_col[$key].'4')->getFont()->setSize(20);
                            //}
                            $col = $col+2;
                            
                            
                    }
    
                 
        
        
    
    $model_records = [];
    
    
    foreach($this->data['rightsCountry'] as $key => $country_res){
        $total_count = 0;
        $row = 6;
        foreach($this->data['makers'] as $make){
            foreach($this->data['models'] as $model){ 
                if($make->car_maker_id == $model->car_maker_id){
                foreach($this->data['regularSales'] as $r_sale){
                    if($make->car_maker_id == $r_sale->maker_id && $model->car_model_id == $r_sale->model_id && $country_res->id == $r_sale->country_id)
                    {
                         
                          if($colNamePur[$key]['country_id'] == $r_sale->country_id){
                            
                               $model_records[$row][$colNamePur[$key]['name']] = $model->model_name; 
                            //if(!empty($this->data['countryCounts'])){
                            foreach($this->data['countryCounts'] as $c_res){
                                if($make->car_maker_id == $c_res->car_maker_id && $model->car_model_id == $c_res->car_model_id && $country_res->id == $c_res->country_id)
                                {
                                  $total_count += $c_res->model_count;	
                                  $model_records[$row][$colNamePur[$key]['pur']] =  $c_res->model_count;
                                }
                               }
                             //}
                            
                            }
                        
                        
                        $row++;
                    }
                    
                }
                
            }
            
            }
            
         }
            $model_records[$row][$colNamePur[$key]['name']] = 'TOTAL';
            $model_records[$row][$colNamePur[$key]['pur']] =  $total_count;
        }
    
        //dd($model_records);
        $coln = 1;
        
        foreach($this->data['rightsCountry'] as $key => $country_res)
        {
            
            
            $sheet->getStyle($firstmergColumns[$coln].'5'.':'.$secondMergeColumns[$coln].'5')->getFont()->setBold(true);
            $sheet->setCellValueByColumnAndRow($coln, 5, 'NAME');
            $sheet->getStyle($firstmergColumns[$coln].'5'.':'.$secondMergeColumns[$coln].'5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($firstmergColumns[$coln].'5'.':'.$secondMergeColumns[$coln].'5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle($firstmergColumns[$coln].'5'.':'.$secondMergeColumns[$coln].'5')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
             ->setARGB('b92a0c');
            $sheet->getStyle($firstmergColumns[$coln].'5')->getFont()->setSize(16);
           
            $coln++;
            $sheet->setCellValueByColumnAndRow($coln, 5, 'PUR');
            $sheet->getStyle($firstmergColumns[$coln].'5')->getFont()->setSize(16);
            $coln++;
            
        }
       
        //dd($model_records);

        $total_columns = count($this->data['rightsCountry'])*2;
        foreach($model_records as $key => $modl)
        {
            $cl = 1;
             for($i=0;$i<$total_columns;$i++){
                $sheet->getStyle($firstmergColumns[$cl].$key)->getFont()->setBold(true);
                $sheet->getStyle($firstmergColumns[$cl].$key)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($firstmergColumns[$cl].$key)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
               if(!empty($model_records[$key][$cl])){
               $sheet->setCellValueByColumnAndRow($cl, $key,  $model_records[$key][$cl]);
               
                $sheet->getStyle($firstmergColumns[$cl].$key)->getFont()->setSize(12);
               
               
               if($model_records[$key][$cl] == 'TOTAL'){
                
                $sheet->getStyle($firstmergColumns[$cl].$key.':'.$secondMergeColumns[$cl].$key)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('008000');
                
               }
              }
              $cl++;
             }
    
            
             
        }
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
            'title' => 'Demand Car Export',
            'description' => 'Export of Demand Car data',
        ];
    }

    public function title(): string
    {   
         return 'Demand Cars';
       
        
    }
}
