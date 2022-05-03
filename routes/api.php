<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassifiedController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ImagesVideosController;
use Illuminate\Support\Facades\Route;


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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::get('/ping',function(){
    return ['pong'=>true];
});

Route::get('/401',[AuthController::class,'unauthorized'])->name('login');
Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/logout',[AuthController::class,'logout']);
Route::post('/auth/refresh',[AuthController::class,'refresh']);
Route::post('/auth/add_user',[AuthController::class,'addUser']);
Route::post('/auth/authentication',[AuthController::class,'authentication']);

Route::post('/reset_pass',[UserController::class,'resetPassword']);
Route::post('/update_pass',[UserController::class,'updatePassword']);
Route::post('/update_user',[UserController::class,'updateUser']);

Route::post('/get_user',[UserController::class,'getUser']);

Route::post('/update_address_info',[UserController::class,'updateUserInfo']);
Route::post('/update_hospital_info',[UserController::class,'updateHospitalInfo']);

Route::post('/get_subjects',[SubjectController::class,'getAllSubject']);

Route::post('/get_test',[TestController::class,'getTest']);
Route::post('/get_all_test',[TestController::class,'getAllTest']);
Route::post('/create_test',[TestController::class,'createTest']);
Route::post('/start_test',[TestController::class,'startTest']);
Route::post('/pause_test',[TestController::class,'pauseTest']);
Route::post('/restart_test',[TestController::class,'restartTest']);
Route::post('/finish_test',[TestController::class,'finishTest']);

Route::post('/ask_question',[TestController::class,'askQuestion']);
Route::post('/annotation_question',[TestController::class,'annotationQuest']);
Route::post('/get_annotation_question',[TestController::class,'getAnnotationQuest']);
Route::post('/favorite_question',[TestController::class,'favoriteQuest']);
Route::post('/not_used_alternative',[TestController::class,'notUsedAlternative']);
Route::post('/get_favorite_question',[TestController::class,'getFavoriteQuest']);
Route::post('/delete_test',[TestController::class,'deleteTest']);

Route::post('/get_classified',[ClassifiedController::class,'getAllClassified']);
Route::post('/get_classified_subtopic',[ClassifiedController::class,'getSubtopicClassified']);

Route::post('/addSupport',[SupportController::class,'addSupport']);
Route::post('/add_support_content',[SupportController::class,'addSupportContent']);
Route::post('/get_supports',[SupportController::class,'getSupports']);
Route::post('/support_resolved',[SupportController::class,'supportResolved']);

Route::post('/get_images_videos',[ImagesVideosController::class,'getImagesVideos']);

Route::post('/notification',[NotificationController::class,'notificationMercadoPago']);