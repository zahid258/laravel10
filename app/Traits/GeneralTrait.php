<?php

namespace App\Traits;

use DB;
use Image;

use Storage;
trait GeneralTrait
{





    public static function pagination($query,$request = []) {

      if(!empty($request->per_page)){
        $query = $query->paginate($perPage = $request->per_page);
      }else{
        $query = $query->paginate($perPage = config('PER_PAGE'));
      }

      return $query;
    }




}
