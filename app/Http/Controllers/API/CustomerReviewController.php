<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\API\BaseController as BaseController;
    use App\Http\Resources\CountryResource;
    use App\Http\Resources\BannerResource;
    use App\Http\Resources\BodyTypeResource;
    use App\Http\Resources\CarListResource;
    use App\Http\Resources\CarDetailResource;
    use Illuminate\Pagination\LengthAwarePaginator;
    use App\Models\CountryModel;
    use App\Models\MainModel;
    use App\Models\CarModel;
    use App\Models\CarDetailModel;
    use App\Http\Resources\CustomerReviewListResource;
    use App\Models\CustomerReviewModel;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Config;
    use App\Traits\StockCountTrait;
    use App\Traits\GeneralTrait;
    use Laravel\Passport\Token;
    use Validator;
    use Image;
    use Storage;
    class CustomerReviewController extends BaseController
    {
        use GeneralTrait;
        /**
         * Display a listing of the resource.
         * @return Response
         */
        public function fetchCustomerReview(Request $request)
        {
            $defaultLang = Config::get('DEFAULTLANG');
            $customerReview = CustomerReviewModel::fetchCustomerReview($defaultLang,$request);
            $total = ['totalReview'=>CustomerReviewModel::TotalCustomerReview($request)]; 
            return $this->sendResponse(new CustomerReviewListResource($customerReview) , 'Customer Review List retrieved successfully.',$total);
        }

        public function saveCustomerReview(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'country_id' => 'required',
                'car_id' => 'required',
                'title' => 'required',
                'review_rating' => 'required',
                'reviews' => 'required',
                'customer_name' => 'required',
                'email' => 'required|email',
                'maker_name' => 'required',
                'model_name' => 'required',
            ]);
       
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $input = $request->all();
            $uploadPath = 'public/uploads/customer_review_files/';
            $time = time();
            $customer_image = '';
            $original_cust_image = '';
            $customer_thumbnail = '';
            $car_image_thumbnail = '';
            $car_image = '';
            $customer_video = '';
            if ($request->hasFile('customer_image')){
                $file = $request->file('customer_image');
                $extension =  $file->extension();
                $height = Image::make($file)->height();
                $width = Image::make($file)->width();
                $original_cust_image = 'customer' . '-' . $time . "." . $extension;
                $customer_thumbnail  = 'customer-s' . '-' . $time . "." . $extension;
                $customer_image      = 'customer-l' . '-' . $time . "." . $extension;
                $this->uploadImageAndResize($width,$height, $file,$original_cust_image);
                $this->uploadImageAndResize(300,300, $file,$customer_thumbnail);
                $this->uploadImageAndResize(600,600, $file,$customer_image);
                $originalImageUrl  =  $uploadPath.$original_cust_image;
                $thumbnailImageUrl = $uploadPath.$customer_thumbnail;
                $customerImageUrl  =  $uploadPath.$customer_image;
                $this->fileUploadS3($originalImageUrl, $extension);
                $this->fileUploadS3($thumbnailImageUrl, $extension);
                $this->fileUploadS3($customerImageUrl, $extension);
                
            }
            

            if ($request->hasFile('customer_video')){
                $file = $request->file('customer_video');
                $extension =  $file->extension();
                $customer_video = 'cusvideo' . '-' . $time . "." . $extension;
                $file->move('public/uploads/customer_review_files/', $customer_video);
                $videoUrl = $uploadPath.$customer_video;
                $this->fileUploadS3($videoUrl, $extension);
            }

            if(!empty($input['system_car_img'])){
                $car_image = $input['system_car_img'];
                $car_image_thumbnail =  $input['system_car_img'];
            }else{
                if ($request->hasFile('car_image')){
                    $file = $request->file('car_image');
                    $extension =  $file->extension();
                    $height = Image::make($file)->height();
                    $width = Image::make($file)->width();
                    $original_car_image = 'car' . '-' . $time . "." . $extension;
                    $car_thumbnail = 'car-s' . '-' . $time . "." . $extension;
                    $car_image = 'https://s3.eu-central-1.amazonaws.com/jansnewfiles/customer_review/customer_review/uploads/customer_review_files/'.$original_car_image;
				    $car_image_thumbnail = 'https://s3.eu-central-1.amazonaws.com/jansnewfiles/customer_review/customer_review/uploads/customer_review_files/'.$car_thumbnail;
                    $this->uploadImageAndResize($width,$height, $file,$original_car_image);
                    $this->uploadImageAndResize(600,600, $file,$car_thumbnail);
                    $originalCarImageUrl  = $uploadPath.$original_car_image;
                    $thumbnailCarImageUrl = $uploadPath.$car_thumbnail;
                    $this->fileUploadS3($originalCarImageUrl, $extension);
                    $this->fileUploadS3($thumbnailCarImageUrl, $extension);
                    
                }
            }
            

          $customerReview = [
            'country_id'          => $input['country_id'],
            'car_id'              => $input['car_id'],
            'title'               => $input['title'],
            'review_rating'       => $input['review_rating'],
            'reviews'             => $input['reviews'],
			'customer_name'       => $input['customer_name'],
            'email'               => $input['email'],
            'customer_image'      => $customer_image,
            'original_cust_image' => $original_cust_image,
            'customer_thumbnail'  => $customer_thumbnail,
            'car_image'           => $car_image,
            'car_image_thumbnail' => $car_image_thumbnail,
            'customer_video'      => $customer_video,
            'maker_name'          => $input['maker_name'],
            'model_name'          => $input['model_name'],
            'created_at'          =>date('Y-m-d'),
          ];

           $customer  = CustomerReviewModel::saveCustomerReview($customerReview);
           if(!empty($customer)){
            return $this->sendResponse($customer , 'Success! Your review is pending approval. Thank you.');
           }else{
            return $this->sendError('error.', 'Your review is not submited!');
           }
            
        }

    }
