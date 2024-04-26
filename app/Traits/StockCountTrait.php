<?php

namespace App\Traits;


use DB;
use Cache;

trait StockCountTrait
{

    public function TotalStockCount($languageId)
    {
        $query = DB::table('car_record')
            ->select(DB::raw('COUNT(car_id) as total'))
            ->whereIn('is_sale', config('IS_SALE'))
            ->whereNotIn('parent_id', [224, 578, 298, 351])
            ->where('delete_state', '=', 0)
            ->first();

            return $query->total;
    }

}
