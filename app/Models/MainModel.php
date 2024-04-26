<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use App\Traits\GeneralTrait;

class MainModel extends Model
{
    use GeneralTrait;



    public static function userList($request , $languageId){
        $query = DB::table('users as u')
            ->select('u.*')
            //->leftjoin('hr_level_detail as hld2', 'crt.country_id', '=', 'hld2.id','hld2.language_id =$languageId','hld2.delete_state=0')
            //->where(['crt.delete_state'=>0,'crt.status'=>1])

            ->orderBy('u.id','DESC');
        $query = GeneralTrait::pagination($query,$request);
        return $query;
    }

    public static function userLikesList($request , $languageId){
        $query = DB::table('users as u')
            ->select('u.*')
            ->join('user_likes as ul', 'u.id', '=', 'ul.user_id')
            ->where(['ul.is_like'=>1])

            ->orderBy('u.id','DESC');
        $query = GeneralTrait::pagination($query,$request);
        return $query;
    }




    public static function saveImpression($impression){
        $result = [];
        $id =  DB::table('user_likes')->insertGetId($impression);
        if(!empty($id)){
            return $result = DB::table('user_likes')->where('id', $id)->first();
        }
        return $result;
    }

}
