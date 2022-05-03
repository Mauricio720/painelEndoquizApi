<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Painel\HomeController;
use App\Http\Controllers\Painel\QuestionsController;
use App\Http\Controllers\Painel\UserController;
use App\Http\Controllers\Painel\SubjectController;
use App\Http\Controllers\Painel\ClassifiedController;
use App\Http\Controllers\Painel\ImagesVideosController;
use App\Http\Controllers\Painel\RequestController;
use App\Http\Controllers\Painel\SupportController;



Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/users',[UserController::class,'index'])->name('allUsers');
Route::any('/addUser',[UserController::class,'add'])->name('addUser');
Route::any('/editUser/{idUser}',[UserController::class,'edit'])->name('editUser');
Route::get('/deleteUser/{idUser}',[UserController::class,'delete'])->name('deleteUser');
Route::any('/myProfile',[UserController::class,'myProfile'])->name('myProfile');

Route::get('/usersMobile',[UserController::class,'allUsersMobile'])->name('allUsersMobile');
Route::get('/userPaymentPlan/{idUser}',[UserController::class,'userPaymentPlan'])->name('userPaymentPlan');
Route::get('/usersMobileBlocked/{idUser}',[UserController::class,'blockedUserMobile'])->name('blockedUserMobile');

Route::get('/allSubjects',[SubjectController::class,'index'])->name('allSubjects');
Route::post('/addSubject',[SubjectController::class,'addSubject'])->name('addSubject');
Route::post('/editSubject',[SubjectController::class,'editSubject'])->name('editSubject');
Route::get('/deleteSubject/{idSubject}',[SubjectController::class,'deleteSubject'])->name('deleteSubject');

Route::post('/addSubtopic',[SubjectController::class,'addSubtopic'])->name('addSubtopic');
Route::post('/editSubtopic',[SubjectController::class,'editSubtopic'])->name('editSubtopic');
Route::get('/deleteSubtopic/{idSubtopic}',[SubjectController::class,'deleteSubtopic'])->name('deleteSubtopic');

Route::get('/allQuestions',[QuestionsController::class,'index'])->name('allQuestions');
Route::get('/addQuestion/{subtopicChoose?}',[QuestionsController::class,'addQuestionView'])->name('addQuestionView');
Route::post('/addQuestion',[QuestionsController::class,'addDefautQuestion'])->name('addQuestion');
Route::post('/updateDefautQuestionText',[QuestionsController::class,'updateDefautQuestionText'])->name('updateDefaultQuestionText');
Route::post('/updateDefautAlternativeQuestionText',[QuestionsController::class,'updateDefautAlternativeQuestionText'])->name('updateDefautAlternativeQuestionText');
Route::get('/deleteDefaultQuestion/{idDefaultQuestion}',[QuestionsController::class,'deleteDefaultQuestion'])->name('deleteDefaultQuestion');
Route::get('/editQuestion/{idDefaultQuestion}',[QuestionsController::class,'editQuestionView'])->name('editQuestionView');
Route::post('/editDefautQuestion',[QuestionsController::class,'editDefautQuestion'])->name('editDefautQuestion');

Route::get('/all_classified',[ClassifiedController::class,'index'])->name('allClassified');
Route::post('/add_classified',[ClassifiedController::class,'addClassified'])->name('addClassified');
Route::post('/edit_classified',[ClassifiedController::class,'editClassified'])->name('editClassified');
Route::get('/delete_classified/{idClassified}',[ClassifiedController::class,'deleteClassified'])->name('deleteClassified');
Route::post('/add_classified_topic',[ClassifiedController::class,'addClassifiedTopic'])->name('addClassifiedTopic');
Route::post('/edit_classified_topic',[ClassifiedController::class,'editClassifiedTopic'])->name('editClassifiedTopic');
Route::get('/delete_classified_topic/{idTopic}',[ClassifiedController::class,'deleteClassifiedTopic'])->name('deleteClassifiedTopic');

Route::get('/classifiedContent/{idTopic}',[ClassifiedController::class,'classifiedContent'])->name('classifiedContent');
Route::post('/classified_addSubtopic',[ClassifiedController::class,'addSubtopicClassified'])->name('addSubtopicClassified');
Route::post('/classified_editSubtopic',[ClassifiedController::class,'editSubtopicClassified'])->name('editSubtopicClassified');
Route::get('/classified_deleteSubtopic/{idSubtopic}',[ClassifiedController::class,'deleteSubtopicClassified'])->name('deleteSubtopicClassified');

Route::post('/classified_addContent',[ClassifiedController::class,'addContentClassified'])->name('addContentClassified');
Route::post('/classified_editContent',[ClassifiedController::class,'editContentClassified'])->name('editContentClassified');
Route::get('/classified_deleteContent/{idContent}',[ClassifiedController::class,'deleteContentClassified'])->name('deleteContentClassified');

Route::get('/all_supports',[SupportController::class,'index'])->name('allSupports');
Route::post('/answer_support',[SupportController::class,'addSupportContent'])->name('answeredSupport');
Route::get('/support_resolved/{idSupport}',[SupportController::class,'supportResolved'])->name('supportResolved');

Route::get('/all_imagesVideos',[ImagesVideosController::class,'index'])->name('allImagesVideos');
Route::post('/add_imagesVideos',[ImagesVideosController::class,'add'])->name('addImagesVideos');
Route::post('/edit_imagesVideos',[ImagesVideosController::class,'edit'])->name('editImagesVideos');
Route::get('/delete_imagesVideos/{id}',[ImagesVideosController::class,'delete'])->name('deleteImageVideo');
Route::get('/get_image_video/{id}',[RequestController::class,'getImageVideo'])->name('getImageVideo');

Auth::routes();
Route::get('/logout_painel',[LoginController::class,'logout'])->name('logoutPainel');

Route::get('/foo', function () {
    Artisan::call('storage:link');
});






 