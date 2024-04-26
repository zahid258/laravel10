<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureTokenIsValid;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\MakeController;
use App\Http\Controllers\API\MainController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\EnquiryController;
use App\Http\Controllers\API\AuctionCarController;
use App\Http\Controllers\API\PromotionCarController;
use App\Http\Controllers\API\TyreController;
use App\Http\Controllers\API\TyreAuctionController;
use App\Http\Controllers\API\CustomerReviewController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\ExportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('user_list', [MainController::class, 'userList']);

    Route::get('user_likes_list', [MainController::class, 'userLikesList']);

    Route::post('user_impression', [MainController::class, 'saveImpression']);



});


