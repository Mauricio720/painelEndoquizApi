<?php
namespace App\Util;

use App\Models\DefaultQuestion;
use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Support\Facades\Auth;

class TestUser{

    private $loggedUser;
    
    public function __construct(){
        $this->loggedUser=Auth::guard('api')->user();
    }

    public function getAllTest(){

    }

    public function getTestById($idTest){

    }

    public function getTotalDefaultQuestion(){
        return DefaultQuestion::count();
    }

    public function getTotalTestQuestion(){
        $totalQuestions=0;
        
        $test=Test::where('idUser',$this->loggedUser->id)->get();
        
        foreach ($test as $key => $testItem) {
            $allQuestionsTest=TestQuestion::where('id_test',$testItem->id)->count();
            if($allQuestionsTest != null){
                $totalQuestions+=$allQuestionsTest;
            }
        }

        return $totalQuestions;
    }


    public function getTotalTestQuestionResolved(){
        $totalQuestions=0;
        
        $test=Test::where('idUser',$this->loggedUser->id)->get();
        
        foreach ($test as $key => $testItem) {
            $allQuestionsTest=TestQuestion::where('id_test',$testItem->id)->where('resolved',true)->count();
            if($allQuestionsTest != null){
                $totalQuestions+=$allQuestionsTest;
            }
        }

        return $totalQuestions;
    }

    public function getTotalTestQuestionFilter($resolved,$isCorrect,$favorite){
        $totalQuestions=0;
        
        $test=Test::where('idUser',$this->loggedUser->id)->get();
        
        foreach ($test as $key => $testItem) {
            $allQuestionsTest=TestQuestion::where('id_test',$testItem->id)
                ->where('resolved',$resolved)
                ->where('is_correct',$isCorrect)
                ->where('favorite',$favorite)
                ->count();

             if($allQuestionsTest != null){
                $totalQuestions+=$allQuestionsTest;
            }
        }

        return $totalQuestions;
    }

    public function getTestQuestions($idTest){

    }

    public function getCountQuestionTest($idTest){

    }
}