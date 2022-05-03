<?php

namespace App\Util;

use App\Util\QuestOrganization;
use App\Models\TestQuestion;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TestOrganization{

    private $loggedUser;
    private $test;
    private $questionOrganization;


    public function __construct(Test $test=null){
        $this->loggedUser=Auth::guard('api')->user();
        if($test != null){
            $this->test=$test;
            $this->questionOrganization=new QuestOrganization($test->all_subject_test);
        }
    }

    public function makeTest(){
        $this->questionOrganization->makeTestQuestion($this->test->id);
    }

    public function getTest($page=0){
        $testArray=[];

        $testArray=$this->test;
        $testArray['questions']=$this->questionOrganization->getAllQuestion($this->test->id,$page);
        
        return  $testArray;
    }

  
    public function startTest(){
        if($this->test->status==1){
            $this->test->start=date('Y-m-d');
            $this->test->startTime=date('H:i:s');
            $this->test->status=2;
            $this->test->save();
            return "";
        }else{
            return "A prova tem que estar recém críada para ser iniciada";
        }
    }

    public function pauseTest(){
        if($this->test->status==2){
            $this->test->pause=date('Y-m-d');
            $this->test->pauseTime=date('H:i:s');
            $this->test->status=3;
            $this->test->test_time=$this->calcTestTime();
            $this->test->save();
            return "";
        }else{
            return "A prova tem que estar em andamento para ser pausada";
        }
    }

    public function restartTest(){
        if($this->test->status==3){
            $this->test->status=2;
            $this->test->restart=date('Y-m-d');
            $this->test->restartTime=date('H:i:s');
            $this->test->save();

            return "";
        }else{
            return "A prova tem que estar pausada para ser reiniciada";
        }
    }

    public function finishTest(){
        if($this->test->status==2){
            $this->test->finish=date('Y-m-d');
            $this->test->finishTime=date('h:i:s');
            $this->test->status=4;
            $this->test->test_time=$this->calcTestTime();
            $this->test->concluded_percentage=$this->calcPercentageQuestionsConcluded();
            $this->test->correct_questions=$this->questionOrganization->getCorrectQuestions($this->test->id);
            $this->test->wrong_questions=$this->questionOrganization->getWrongQuestions($this->test->id);
            $this->test->unanswered_questions=$this->questionOrganization->getUnansweredQuestions($this->test->id);
            $this->test->answered_questions=$this->questionOrganization->getAnsweredQuestions($this->test->id);
            $this->test->totalPercentageQuestions=$this->calcTotalPercentageQuestions();
            $this->test->save();
            return "";
        }else{
            return "A prova tem que estar em andamento para ser finalizada";
        }
    }

    public function deleteTest(){
        $érror=$this->questionOrganization->deleteQuestions($this->test->id);
        if($érror == ""){
            $this->test->delete();
        }
        return $érror;
    }

    private function calcPercentageQuestionsConcluded(){
        $totalQuestions=$this->questionOrganization->getTotalQuestions($this->test->id);
        $resolvedQuestions=$this->questionOrganization->getResolvedQuestions($this->test->id);
    
        $total=$resolvedQuestions*100/$totalQuestions;

        return number_format($total,2);
    }

    private function calcTotalPercentageQuestions(){
        $totalQuestions=$this->questionOrganization->getTotalQuestions($this->test->id);
        $resolvedQuestions=$this->questionOrganization->getResolvedQuestions($this->test->id);
        $correctQuestions=$this->questionOrganization->getCorrectQuestions($this->test->id);
        
        $total="Prova não concluida!";
        if($resolvedQuestions==$totalQuestions){
            $total=($correctQuestions*100)/$totalQuestions;
            $total=number_format($total,2);
        }
        
        return $total;
    }
   
    private function calcTestTime(){
        $restartTime=$this->test->restartTime;
        $restartDate=$this->test->restart;

        if($this->test->restart == null){
            $restartTime=$this->test->startTime;
            $restartDate=$this->test->start;
        }

        $restartDate=explode('-',$restartDate);
        $restartTime=explode(':',$restartTime);

        $restart = Carbon::parse($restartDate[0].'-'.$restartDate[1].'-'.$restartDate[2].' '. 
        $restartTime[0].':'.$restartTime[1].':00');
        
        $now=date('Y-m-d H:i:s');
        $secondDate =   Carbon::parse($now);
    
        $minutes= $secondDate->diffInMinutes($restart);

        $time = floor($minutes / 60);
        $minutes = $minutes % 60;
        $finalTime= $time.":".$minutes;
        $timeDivide=explode(':',$finalTime);

        $time_test=explode(':',$this->test->test_time);
        $now=date('Y-m-d');
        $timeRestart = Carbon::parse($now." ".$time_test[0].':'.$time_test[1].':00');
        $newHour=$timeRestart->addHours($timeDivide[0])->addMinutes($timeDivide[1]);
        
        $newHour=explode(' ',$newHour)[1];
        
        return $newHour;
    }

    public function askQuestion($idTestQuestion,$choosenAlternative){
        if($this->test->status==2){
            return $this->questionOrganization->askQuestion($idTestQuestion,$choosenAlternative);
        }else{
            return "A prova tem que estar em andamento para responder a questão!";
        }
    }

    public function annotationQuest($idTestQuestion,$annotation){
        return $this->questionOrganization->annotationQuest($idTestQuestion,$annotation);
    }

    public function getQuestAnnotation(){
        $testUser=Test::where('idUser',$this->loggedUser->id)->get();
        $allQuestionsAnnotationArray=[];
        
        foreach ($testUser as $key => $test) {
            $questionOrganization=new QuestOrganization($test->all_subject_test);
            $allQuestionsAnnotation=$questionOrganization->getAnnotationQuest($test->id);
            
            foreach ($allQuestionsAnnotation as $key => $questionAnnotation) {
                $allQuestionsAnnotationArray[]=$questionAnnotation;
            }
        }

        return  $allQuestionsAnnotationArray;
    }

    public function notUsedAlternative($idTestQuestion,$idAlternative){
        return $this->questionOrganization->notUsedAlternative($idTestQuestion,$idAlternative);
    }

    public function favoriteQuest($idTestQuestion){
        return $this->questionOrganization->favoriteQuest($idTestQuestion);
    }

    public function getTotalQuestions($idTest){
        return $this->questionOrganization->getTotalQuestions($idTest);
    }

    public function getFavoriteQuest(){
        $allFavoriteQuestion=[];
        $allTest=Test::where('idUser',$this->loggedUser->id)->get();
        
        foreach ($allTest as $key => $test) {
            $questionOrganization=new QuestOrganization($test->all_subject_test);
            $allFavoriteQuestion['test']=$test;
            $allFavoriteQuestion['questions']=$questionOrganization->getFavoriteQuest($test->id);
        }

        return $allFavoriteQuestion;
    }
}