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

class SummaryListExport implements  
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
		foreach($this->data['yearsList'] as $year)
        {
            $lastcoll = $letter . '4';
            $lastletter = $letter;
            $in_last_letter = $inv_letter;
            $dmnd_last_letter = $demand_letter;
            $sheet->setCellValueByColumnAndRow($col, 4, $year->year);
            $sheet->getColumnDimension($lastletter)->setAutoSize(false);
            $sheet->getColumnDimension($lastletter)->setWidth(10); 
            $col++;
            $row++;
            $letter++;
            $inv_letter++;
            $demand_letter++;
        }
        
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

        

        $sheet->setCellValueByColumnAndRow($col, 4, 'INVT STOCK');
        if($this->data['type'] =='inventory'){
            $sheet->setCellValueByColumnAndRow($col+1, 4, 'Demand');
            $sheet->setCellValueByColumnAndRow($col+2, 4, 'Price');
            $sheet->setCellValueByColumnAndRow($col+3, 4, 'Remarks');
           }
        $sheet->getStyle(4)->getFont()->setBold(true);
        $sheet->getStyle(4)->getFont()->setSize(15);

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(27);
        $sheet->getStyle('C4:'.$lastletter.'4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension($lastletter)->setAutoSize(false);
                   
        if($this->data['type'] =='inventory'){
         $sheet->getColumnDimension($lastletter)->setWidth(15);
         $sheet->getColumnDimension($in_last_letter)->setAutoSize(false);
         $sheet->getColumnDimension($in_last_letter)->setWidth(20);

         $sheet->getColumnDimension($dmnd_last_letter)->setAutoSize(false);
         $sheet->getColumnDimension($dmnd_last_letter)->setWidth(15);
         }else{
          $sheet->getColumnDimension($lastletter)->setWidth(15);
         }

        $sheet->setShowGridlines(true);

                    $row = 5;
					$where = '';
				foreach($this->data['makers'] as $make)
				{
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
						   
							foreach($this->data['yearsList'] as $years){ 
								$make_grand_total += $this->fetchCountByYearMake($make->car_maker_id,$years->year,$this->data['yearMakerCount']);
								$sheet->setCellValueByColumnAndRow($coll, $row, $this->fetchCountByYearMake($make->car_maker_id,$years->year,$this->data['yearMakerCount']));
                                $sheet->getStyle('C'.$row.':'.$lastletter.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
								$coll++;
	
								
							}


							$mmodel_demond = 0;
						if($this->data['type'] =='inventory'){
							foreach($this->data['models'] as $model){ 
							
							foreach($this->data['chassisCode'] as $chassis){ 
								if($make->car_maker_id == $model->carMakerId && $chassis->car_model_id == $model->modelId && 
								$model->carMakerId == $chassis->car_maker_id){
									
                                    if(!empty($this->data['demonds'])){                    
									foreach($this->data['demonds'] as $dmnd){
										if ($dmnd->maker_id == $make->car_maker_id && $dmnd->model_id == $model->modelId && 
										$chassis->chassis_code_id == $dmnd->chassis_code_id) {
                                        if($dmnd->demond != 0){
										  $mmodel_demond += $dmnd->demond;
                                         }

										$model_demond_color=$dmnd->demond;
										}
									 }
                                  }
								}
							}
						}
					}


							$sheet->setCellValueByColumnAndRow($coll, $row, $make_grand_total);
                           
							if($mmodel_demond != 0 && $this->data['type'] =='inventory'){
								$sheet->setCellValueByColumnAndRow($coll+1, $row, $mmodel_demond);
							}
                   
                           // start model 
                     
							foreach($this->data['models'] as $model){ 

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
									
									
                                    $model_demond = 0;
									$chassis_code_name_concat = "";
									foreach($this->data['chassisCode'] as $chassis){ 
										if($make->car_maker_id == $model->carMakerId && $chassis->car_model_id == $model->modelId && 
										$model->carMakerId == $chassis->car_maker_id){
											$chassis_code_name_concat = $chassis_code_name_concat." ".$chassis->chassis_code_name.",";
                                          if($this->data['type'] =='inventory'){
                                            if(!empty($this->data['demonds'])){
											foreach($this->data['demonds'] as $dmnd){
												if ($dmnd->maker_id == $make->car_maker_id && $dmnd->model_id == $model->modelId && 
												$chassis->chassis_code_id == $dmnd->chassis_code_id) {
                                                if($dmnd->demond != 0){
                                                    $model_demond += $dmnd->demond;
                                                }
												
												$model_demond_color=$dmnd->demond;
												}
											 }
                                            }
											}
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
									foreach($this->data['yearsList'] as $years){ 
								       $model_grand_total += $this->fetchCountByYearModel($make->car_maker_id,$model->modelId,$model->carMakerId,$years->year,$this->data['yearModelCount']);
										$sheet->setCellValueByColumnAndRow($colmdl, $row, $this->fetchCountByYearModel($make->car_maker_id,$model->modelId,$model->carMakerId,$years->year,$this->data['yearModelCount']));
										$sheet->getStyle('C'.$row.':'.$lastletter.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                        $colmdl++;
									}
									
                                 // for inventory color
									if($model_demond != 0 && $this->data['type'] =='inventory'){
										if($model_grand_total > $model_demond){

                                            $sheet->getStyle($in_last_letter.$row)
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()
                                            ->setARGB('ff0000');

										 }elseif ($model_demond == $model_grand_total) {
                                            $sheet->getStyle($in_last_letter.$row)
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()
                                            ->setARGB('fccf3a');

										 }elseif($model_grand_total < $model_demond){
                                            $sheet->getStyle($in_last_letter.$row)
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()
                                            ->setARGB('008000');
										 }
									 }
	                                
									$sheet->setCellValueByColumnAndRow($colmdl, $row, $model_grand_total);
									
                                    if($model_demond != 0 && $this->data['type'] =='inventory'){
										if($model_grand_total >$model_demond){
                                            $sheet->getStyle($dmnd_last_letter.$row)
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()
                                            ->setARGB('ff0000');

										 }elseif ($model_demond == $model_grand_total) {
						  
                                            $sheet->getStyle($dmnd_last_letter.$row)
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()
                                            ->setARGB('fccf3a');

										 }elseif($model_grand_total < $model_demond){
											
                                            $sheet->getStyle($dmnd_last_letter.$row)
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()
                                            ->setARGB('008000');

										 }

										
										$sheet->setCellValueByColumnAndRow($colmdl+1, $row, $model_demond);
									}
									// chassise start here

                                    foreach($this->data['chassisCode'] as $chassis){ 
                                        $sheet->getStyle('A'.$row.':'.$lastletter.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                        if($make->car_maker_id == $model->carMakerId && $chassis->car_model_id == $model->modelId && 
                                            $model->carMakerId == $chassis->car_maker_id){
                                                
                                            $sheet->setCellValueByColumnAndRow(2, ++$row, $chassis->chassis_code_name);
                                            //$chassis_code_name_concat = $chassis_code_name_concat." ".$chassis->chassis_code_name.",";
                                        $maker_model_chs = $chassis->car_maker_id.'-'.$chassis->car_model_id.'-'.$chassis->chassis_code_id;
                                        if( !empty($this->data['countsOfChassisCode'][$maker_model_chs]) && ($this->data['countsOfChassisCode'][$maker_model_chs] > 0) ){
                                            $car_count_ch = $this->data['countsOfChassisCode'][$maker_model_chs];
                                        }else{
                                            $car_count_ch = '';
                                        }
    
                                            $sheet->setCellValueByColumnAndRow(3, $row, $car_count_ch);
    
                                            $chassies_grand_total = 0;
                                        
                                            $colchassis = 4;
                                            foreach($this->data['yearsList'] as $years){ 
                                               $chassies_grand_total += $this->fetchCountByYearChassis($make->car_maker_id,$model->modelId,$model->carMakerId,$chassis->car_maker_id,$chassis->car_model_id,$chassis->chassis_code_id,$years->year,$this->data['yearChassisCount']);
                                                $sheet->setCellValueByColumnAndRow($colchassis, $row, $this->fetchCountByYearChassis($make->car_maker_id,$model->modelId,$model->carMakerId,$chassis->car_maker_id,$chassis->car_model_id,$chassis->chassis_code_id,$years->year,$this->data['yearChassisCount']));
                                                $sheet->getStyle('C'.$row.':'.$lastletter.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                                $colchassis++;
                                            }
    
                                            // for chassiss inventory color
                                            $sheet->setCellValueByColumnAndRow($colchassis, $row, $chassies_grand_total);
                                            if($this->data['type'] =='inventory'){
                                                $chassisdemonds =  $this->data['chassisDemond']; //$this->fetchChassisDemond($this->data['countryId'],$chassis->chassis_code_id);
                                              if(!empty($this->data['chassisDemond'][$maker_model_chs]['demond'])){
                                                if($this->data['chassisDemond'][$maker_model_chs]['demond'] > $chassies_grand_total){
                                                    
                                                    $sheet->getStyle($in_last_letter.$row)
                                                    ->getFill()
                                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                                    ->getStartColor()
                                                    ->setARGB('ff0000');
    
    
                                                 }elseif ($this->data['chassisDemond'][$maker_model_chs]['demond'] == $chassies_grand_total) {
                                  
                                                    $sheet->getStyle($in_last_letter.$row)
                                                    ->getFill()
                                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                                    ->getStartColor()
                                                    ->setARGB('fccf3a');
    
                                                 }elseif($this->data['chassisDemond'][$maker_model_chs]['demond'] < $chassies_grand_total){
                                                    
                                                    $sheet->getStyle($in_last_letter.$row)
                                                    ->getFill()
                                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                                    ->getStartColor()
                                                    ->setARGB('00ffff');
                                                 }
                                            
                                           
                                            
                                        
                                            if($this->data['chassisDemond'][$maker_model_chs]['demond'] > $chassies_grand_total){
                                                
                                                $sheet->getStyle($dmnd_last_letter.$row)
                                                ->getFill()
                                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                                ->getStartColor()
                                                ->setARGB('ff0000');
    
                                             }elseif ($this->data['chassisDemond'][$maker_model_chs]['demond'] == $chassies_grand_total) {
                              
                                                $sheet->getStyle($dmnd_last_letter.$row)
                                                ->getFill()
                                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                                ->getStartColor()
                                                ->setARGB('fccf3a');
    
                                             }elseif($this->data['chassisDemond'][$maker_model_chs]['demond'] < $chassies_grand_total){
                                                
                                                $sheet->getStyle($dmnd_last_letter.$row)
                                                ->getFill()
                                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                                ->getStartColor()
                                                ->setARGB('00ffff');
                                             }
    
                                            if($this->data['chassisDemond'][$maker_model_chs]['demond'] != 0){
                                                $sheet->setCellValueByColumnAndRow($colchassis+1, $row, $this->data['chassisDemond'][$maker_model_chs]['demond']);
                                            }
                                            
                                            
                                            if($this->data['chassisDemond'][$maker_model_chs]['price'] != 0){
                                                $sheet->setCellValueByColumnAndRow($colchassis+2, $row, $this->data['chassisDemond'][$maker_model_chs]['price']);
                                            } 
                                            
    
                                            $sheet->setCellValueByColumnAndRow($colchassis+3, $row, $this->data['chassisDemond'][$maker_model_chs]['remarks']);
                                        }else{
                                            $sheet->getStyle($in_last_letter.$row)
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()
                                            ->setARGB('00ffff');
    
                                            $sheet->getStyle($dmnd_last_letter.$row)
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()
                                            ->setARGB('00ffff');
    
                                            $sheet->setCellValueByColumnAndRow($colchassis, $row, $chassies_grand_total);
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
            'title' => 'Sumary List Export',
            'description' => 'Export of Sumary list data',
        ];
    }

    public function title(): string
    {   if($this->data['type'] =='inventory'){
         return $this->data['country']->hr_name.' Sumary Report';
        }else{
            return $this->data['country']->hr_name.' Sumary Report Mukechi';
        }
        
    }
}
