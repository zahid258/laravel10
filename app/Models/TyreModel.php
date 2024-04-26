<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneralTrait;
use DB;
  
class TyreModel extends Model
{
    protected $connection = 'tyredb'; // for eloquent

    public static function fetchTyreList($languageId, $request){
        $TYREDB = config('TYREDB');
        $query =  $TYREDB->table('tyre_record as tr')->select('tr.tyre_id','pcat.category_name as parent_category_name',
         'pcat.image as parent_cat_image' ,'ccat.category_name as child_category_name','size.size_name',
         'tr.tyre_quantity','hld1.hr_name as city','hld1.phone as phone','hld1.address as address',
         'hld1.id as city_id','hld2.hr_name as country','hld2.id as country_id')
         ->leftjoin('tyre_category as ccat', 'tr.tyre_cat_id', '=', 'ccat.tyre_cat_id', 'ccat.delete_state = 0')
         ->leftjoin('tyre_category as pcat', 'pcat.tyre_cat_id', '=', 'ccat.parent_id' , 'pcat.delete_state = 0')
         ->leftjoin('tyre_size as size', function ($leftjoin) {
			$leftjoin->on('tr.tyre_size_id', '=', 'size.tyre_size_id')
				 ->On('size.tyre_cat_id','=',  'ccat.tyre_cat_id');
		 })
        
         ->leftjoin('hr_level_detail as hld1', 'tr.parent_id','=', 'hld1.id', "hld1.language_id='$languageId'")
         ->leftjoin('hr_level_detail as hld2', 'hld1.parent_id', '=', 'hld2.id', "hld2.language_id='$languageId'");
         
         $query = $query->where(['size.delete_state' => 0,'tr.delete_state' =>0])->where('tr.tyre_quantity','<>',0);
         $query =  GeneralTrait::tyreWhere($query,'all',$request)
         ->orderByRaw("size.size_name ASC");
         $query = GeneralTrait::pagination($query,$request);
         return $query;
     }

     public static function fetchTyreAddress($request){
        $TYREDB = config('TYREDB');
        $query =  $TYREDB->table('hr_level_detail as hld1')->select('hld1.id','hld1.hr_name as city',
        'hld1.phone as phone','hld1.address as address',
         'hld1.id as city_id','hld2.hr_name as country','hld2.id as country_id')
         ->leftjoin('hr_level_detail as hld2', 'hld1.parent_id', '=', 'hld2.id')
         ->where(['hld1.parent_id' => $request->country_id])
         ->whereNotNull('hld1.address')
         ->groupBy('hld1.address')
         ->orderBy('city', 'asc')->get();
         return $query;
     }

    public static function fetchTyreCategory($request){
        $TYREDB = config('TYREDB');
        $query =  $TYREDB->table('tyre_record as tr')->select('tcc.category_name as category_name','tcc.tyre_cat_id',
         DB::raw('SUM(tr.tyre_quantity) as main_category_count'))
         ->join('tyre_category as cm', 'cm.tyre_cat_id', '=', 'tr.tyre_cat_id')
         ->join('tyre_category as tcc', 'tcc.tyre_cat_id', '=', 'cm.parent_id')
         ->join('tyre_size as ts', function ($leftjoin) {
			$leftjoin->on('tr.tyre_size_id', '=', 'ts.tyre_size_id')
				 ->On('ts.tyre_cat_id','=',  'cm.tyre_cat_id')
                 ->where('ts.delete_state',0);
		 })
         ->where(['tcc.parent_id' => 0,'tcc.delete_state' =>0,'tr.delete_state'=>0]);
         $query =  GeneralTrait::tyreWhere($query,'mainCategory',$request)
         ->groupBy('tcc.tyre_cat_id')
         ->orderByRaw("tcc.sort_no ASC")->get();
         return $query;
     }

     public static function fetchTyreSize($request){
        $TYREDB = config('TYREDB');
        $languageId = config('DEFAULTLANG');
        $query =  $TYREDB->table('tyre_record as tr')->select('size.tyre_size_id','size.size_name')
         ->leftjoin('tyre_category as ccat', 'tr.tyre_cat_id', '=', 'ccat.tyre_cat_id', 'ccat.delete_state = 0')
         ->leftjoin('tyre_category as pcat', 'pcat.tyre_cat_id', '=', 'ccat.parent_id' , 'pcat.delete_state = 0')
         ->leftjoin('tyre_size as size', function ($leftjoin) {
			$leftjoin->on('tr.tyre_size_id', '=', 'size.tyre_size_id')
				 ->On('size.tyre_cat_id','=',  'ccat.tyre_cat_id');
		 })
        
         ->leftjoin('hr_level_detail as hld1', 'tr.parent_id','=', 'hld1.id', "hld1.language_id='$languageId'")
         ->leftjoin('hr_level_detail as hld2', 'hld1.parent_id', '=', 'hld2.id', "hld2.language_id='$languageId'");
         
         $query = $query->where(['size.delete_state' => 0,'tr.delete_state' =>0])->where('tr.tyre_quantity','<>',0);
         $query =  GeneralTrait::tyreWhere($query,'size',$request)
         ->groupBy('size.tyre_size_id')
         ->orderByRaw("size.size_name ASC")->get();
         return $query;
     }

     
}