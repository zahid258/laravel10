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
class PurchaseReportExport implements  
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
            $heading = '(Mukechi)';
            }	
        $col = 4;
		
        
        $lastletter = 'D';
        // header start
        $sheet->mergeCells('A1:'.$lastletter.'3');
        $sheet->setCellValue('A1', $this->data['country']->hr_name. ' TOTAL STOCK '. $heading);
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

                    $row = 5;
					$where = '';
				foreach($this->data['makers'] as $make)
				{
                    if(!empty($this->data['countsOfMaker'][$make->car_maker_id]) && ($this->data['countsOfMaker'][$make->car_maker_id] > 0) ){
						$coll = 4;
						$make_grand_total = 0;
                        $sheet->getStyle('A'.$row.':'.$lastletter.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
						    $sheet->mergeCells('A'.$row.':B'.$row);
							$sheet->setCellValueByColumnAndRow(1, $row, $make->maker_name);
							
                            $sheet->getStyle('A'.$row.':'.$lastletter.$row)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('edc039');
                   
                            $sheet->getStyle('A'.$row.':B'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);



							$sheet->getStyle('A'.$row)->getFont()->setSize(17);
                   
                           // start model 
                     
							foreach($this->data['models'] as $model){ 
                                $maker_model = $model->carMakerId.'-'.$model->modelId;
                                if(!empty($this->data['countsOfModel'][$maker_model]) && ($this->data['countsOfModel'][$maker_model] > 0) ){
								if($make->car_maker_id == $model->carMakerId){

									$color_cell =  ++$row;
                                    $sheet->getStyle('A'.$row.':'.$lastletter.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                    
                                    $sheet->getStyle('A'.$row.':'.$lastletter.$row)
                                    ->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setARGB('ecece5');

									if(in_array($model->carMakerId.'-'.$model->modelId, $this->data['makerModelResults'])){

                                        $sheet->getStyle('A'.$color_cell)
                                        ->getFill()
                                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()
                                        ->setARGB('FF0000');
									}
									
									$sheet->setCellValueByColumnAndRow(1, $color_cell, $model->modelName);
									
									
                                   
									$chassis_code_name_concat = "";
									foreach($this->data['chassisCode'] as $chassis){ 
										if($make->car_maker_id == $model->carMakerId && $chassis->car_model_id == $model->modelId && 
										$model->carMakerId == $chassis->car_maker_id){
											$chassis_code_name_concat = $chassis_code_name_concat." ".$chassis->chassis_code_name.",";
                                         
										}
									}
                                    
									$sheet->setCellValueByColumnAndRow(2, $row, $chassis_code_name_concat );
									$maker_model = $model->carMakerId.'-'.$model->modelId;
									if(!empty($this->data['countsOfModel'][$maker_model]) && ($this->data['countsOfModel'][$maker_model] > 0) ){
										$car_count = $this->data['countsOfModel'][$maker_model];
									}else{
										$car_count = '';
									}
									$sheet->setCellValueByColumnAndRow(3, $row, $car_count);
									//$model_row_number = $row;
									
									$sheet->getStyle('B'.$row)->getFont()->setSize(13);
									$colmdl = 4;
									$model_grand_total = 0;
									
									
                                 // for inventory color
									
									
                                    // chassise
								foreach($this->data['chassisCode'] as $chassis){ 
                                    $maker_model_chs = $chassis->car_maker_id.'-'.$chassis->car_model_id.'-'.$chassis->chassis_code_id;
                                    if( !empty($this->data['countsOfChassisCode'][$maker_model_chs]) && ($this->data['countsOfChassisCode'][$maker_model_chs] > 0) ){
                                    $sheet->getStyle('A'.$row.':'.$lastletter.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
									if($make->car_maker_id == $model->carMakerId && $chassis->car_model_id == $model->modelId && 
										$model->carMakerId == $chassis->car_maker_id){
                                            
										$sheet->setCellValueByColumnAndRow(2, ++$row, $chassis->chassis_code_name);
										//$chassis_code_name_concat = $chassis_code_name_concat." ".$chassis->chassis_code_name.",";
									
									if( !empty($this->data['countsOfChassisCode'][$maker_model_chs]) && ($this->data['countsOfChassisCode'][$maker_model_chs] > 0) ){
										$car_count_ch = $this->data['countsOfChassisCode'][$maker_model_chs];
									}else{
										$car_count_ch = '';
									}

										$sheet->setCellValueByColumnAndRow(3, $row, $car_count_ch);

										$chassies_grand_total = 0;
									
										$colchassis = 4;
										

										// for chassiss inventory color

										
									}
                                  }
								}
									
								}
                            }
								//$row = $modelrow;
								
							}

                     // end model

                    $row++;
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
