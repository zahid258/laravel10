<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Response;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return Response
     */
    public function sendResponse($result, $message, $arr = [])
    {
        if(!empty($arr)){
        $response = [
            'success' => true,
            key($arr)=>$arr[key($arr)],
            'data' => $result,
            'message' => $message,
            ];
        }else{
            $response = [
                'success' => true,
                'data' => $result,
                'message' => $message,
            ];
        }
       


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
