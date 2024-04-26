<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\API\BaseController as BaseController;
    use App\Http\Resources\UserListResource;
    use App\Models\MainModel;
    use Illuminate\Http\Request;
    use Config;
    use App\Traits\GeneralTrait;
    use Validator;

    class MainController extends BaseController
    {
        use GeneralTrait;




        public function userList(Request $request)
        {
            $defaultLang = Config::get('DEFAULTLANG');
            $users = MainModel::userList($request,$defaultLang);
            return $this->sendResponse(new UserListResource($users) , 'User fetched successfully');
        }
        public function userLikesList(Request $request){
            $defaultLang = Config::get('DEFAULTLANG');
            $users = MainModel::userLikesList($request,$defaultLang);
            return $this->sendResponse(new UserListResource($users) , 'User fetched successfully');

        }

        public function saveImpression(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'user_like_id' => 'required',
                'is_like' => 'required',

            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $input = $request->all();










            $impression = [
                'user_id'          => $input['user_id'],
                'user_like_id'              => $input['user_like_id'],
                'is_like'               => $input['is_like'],
                'created_at'          =>date('Y-m-d h:i:s'),
            ];

            $customer  = MainModel::saveImpression($impression);
            if(!empty($customer)){
                return $this->sendResponse($customer , 'Success! Your Impression added successfully');
            }else{
                return $this->sendError('error.', 'Your Impression is not submited!');
            }

        }




    }
