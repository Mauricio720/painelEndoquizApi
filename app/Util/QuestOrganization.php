<?php

namespace App\Util;

use App\Models\DefaultAlternativeQuestion;
use App\Models\DefaultQuestion;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\TestQuestionAlternative;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Foreach_;

class QuestOrganization {

    private $usedQuestions=[];
    private $subjectsChoose=[];
    private $allFilterQuestions=[];

    public function __construct($subjectsChoose){
        $this->subjectsChoose=explode(',',$subjectsChoose);
        $userPlan=new UserPlanPayment();
        
        if($userPlan->getTypePlan()==2){
            $this->filterQuestionsBySubjectChoose();
        }else{
            $this->filterQuestionsBySubjectChoose(true);
        }
    }

    public function makeTestQuestion($idTest){
        foreach ($this->allFilterQuestions as $key => $question) {
            $testQuestion=new TestQuestion();
            $testQuestion->id_test=$idTest;
            $testQuestion->id_default_question=$question->id;
            $testQuestion->resolved=false;
            $testQuestion->favorite=false;
            $testQuestion->save();
            
            $correctAlternativeId=$this->addAlternativeTest($testQuestion->id,$question->id);
            $testQuestion->correct_alternative_id=$correctAlternativeId;
            $testQuestion->save();
        }
    }

    private function addAlternativeTest($testQuestionId,$idDefaultQuestion){
        $defaultAlternativeQuestion=DefaultAlternativeQuestion::where('id_default_question',$idDefaultQuestion)->get();
        $correctAlternativeId="";
        foreach ($defaultAlternativeQuestion as $key => $defaultAlternative) {
            $testQuestionAlternative=new TestQuestionAlternative();
            $testQuestionAlternative->id_testQuestion=$testQuestionId;
            $testQuestionAlternative->id_default_alternative=$defaultAlternative->id;
            $testQuestionAlternative->is_correct=$defaultAlternative->is_correct;
            $testQuestionAlternative->not_used=false;
            $testQuestionAlternative->save();

            if($defaultAlternative->is_correct){
                $correctAlternativeId=$testQuestionAlternative->id;
            }
        } 
        
        return $correctAlternativeId;
    }

    public function getAllQuestion($idTest,$page=1){
        $allQuestionArray=[];
        $allTestQuestions=TestQuestion::where('id_test',$idTest)->paginate(10,['*'],'page',$page);
        $allTestQuestions->links();
        
        foreach ($allTestQuestions as $key => $testQuestion) {
            $defaultQuestion=DefaultQuestion::where('id',$testQuestion->id_default_question)->first();
            $question=$defaultQuestion->question;
            $image="";
            
            if($defaultQuestion->image != ""){
                $image=asset('storage/questionsImages/'.$defaultQuestion->image);
            }

            $justifyImage="";
            if($defaultQuestion->justifyImage != ""){
                $justifyImage=asset('storage/questionsImages/'.$defaultQuestion->justifyImage);
            }

            
            $video=$defaultQuestion->video;
            
            $question=[
                'id'=>$testQuestion->id,
                'id_test'=>$idTest,
                'question'=>$question,
                'premium'=>$defaultQuestion->premium,
                'image'=>$image,
                'video'=>$video,
                'annotation'=>$testQuestion->annotation,
                'favorite'=>$testQuestion->favorite,
                'resolved'=>$testQuestion->resolved,
                'chosenAlternative'=>$testQuestion->chosen_alternative,
                'justifyContent'=>$defaultQuestion->justifyContent,
                'justifyImage'=>$justifyImage,
                'justifyVideoLink'=>$defaultQuestion->justifyVideoLink
            ];

            $testAlternativeQuestions=TestQuestionAlternative::where('id_testQuestion',$testQuestion->id)->get();
            $allAlternativeQuestion=[];

            foreach ($testAlternativeQuestions as $key => $testAlternative) {
                $defaultAlternative=DefaultAlternativeQuestion::
                    where('id',$testAlternative->id_default_alternative)->first();
                
                $description=$defaultAlternative->description;
                
                $alternative=[
                    'id'=>$testAlternative->id,
                    'alternative'=>$description,
                    'not_used'=>$testAlternative->not_used,
                    'is_correct'=>$testAlternative->is_correct,
                ];

                $allAlternativeQuestion[]=$alternative;
            }

            $question['alternatives']=$allAlternativeQuestion;
            $allQuestionArray[]=$question;
        }

        return $allQuestionArray;
    }

    public function getTotalQuestions($idTest){
        return TestQuestion::where('id_test',$idTest)->count();
    }

    public function getResolvedQuestions($idTest){
        return TestQuestion::where('id_test',$idTest)->where('resolved',1)->count();
    }

    public function getCorrectQuestions($idTest){
        return TestQuestion::where('id_test',$idTest)->where('is_correct',1)->count();
    }

    public function getWrongQuestions($idTest){
        return TestQuestion::where('id_test',$idTest)->where('is_correct',0)->count();
    }

    public function getAnsweredQuestions($idTest){
        return TestQuestion::where('id_test',$idTest)->where('resolved',1)->count();
    }
    
    public function getUnansweredQuestions($idTest){
        return TestQuestion::where('id_test',$idTest)->where('resolved',0)->count();
    }

    public function getFavoriteQuest($idTest){
        $allQuestFavorite=TestQuestion::where('id_test',$idTest)->where('favorite',true)->get();
        return $allQuestFavorite;
    }
    

    private function filterQuestionsBySubjectChoose($freeQuestion=false){
        $allDefaultQuestions=DefaultQuestion::all();
 
        if($freeQuestion){
            $allDefaultQuestions=DefaultQuestion::where('premium',0)->get();
        }
        foreach ($allDefaultQuestions as $key => $question) {
            foreach ($this->subjectsChoose as $key => $subject) {
                $idSubjectTopics=explode(',',$question->id_subject_topics);
                if(in_array($subject,$idSubjectTopics)){
                    if($this->verifyRepeatQuestion($question->question)==false){
                        $this->allFilterQuestions[]=$question;
                        $this->setRepeatQuestions($question->question);
                    }
                }
            } 
        }

        shuffle($this->allFilterQuestions);
    }

    private function verifyRepeatQuestion($question){
        return in_array($question=strtolower($question),$this->usedQuestions);
    }

    private function setRepeatQuestions($question){
        $question=trim(strtolower($question));
        $this->usedQuestions[]=$question;
    }

    public function askQuestion($idTestQuestion,$choosenAlternative){
        if($this->verifyQuestion($idTestQuestion) && 
            $this->verifyChoosenAlternative($choosenAlternative,$idTestQuestion)){
            
            $testQuestion=TestQuestion::where('id',$idTestQuestion)->first();
            $testQuestion->chosen_alternative=$choosenAlternative;
            $testQuestion->resolved=true;
            $testQuestion->save();

            if($choosenAlternative==$testQuestion->correct_alternative_id){
                $testQuestion->is_correct=true;
            }else{
                $testQuestion->is_correct=false;
            }

            $testQuestion->save();
            return "";
        }else{
            return "Erro ao responder a questão!";
        }
    }

    public function annotationQuest($idTestQuestion,$annotation){
        if($this->verifyQuestion($idTestQuestion)){
            $testQuestion=TestQuestion::where('id',$idTestQuestion)->first();
            $testQuestion->annotation=$annotation;
            $testQuestion->save();

            return "";
        
        }else{
            return "Erro ao inserir anotação na questão!";
        }
    }

    
    public function getAnnotationQuest($idTest){
        $questionAnnotationArray=[];
        $questionAnnotation=TestQuestion::where('id_test',$idTest)
            ->where('annotation',"!=","")->get();
        
        foreach ($questionAnnotation as $key => $question) {
            $questionAnnotationArray[]=$question;
        }
        
        return $questionAnnotationArray;
    }

    public function favoriteQuest($idTestQuestion){
        if($this->verifyQuestion($idTestQuestion)){
            $testQuestion=TestQuestion::where('id',$idTestQuestion)->first();
            $testQuestion->favorite=!$testQuestion->favorite;
            $testQuestion->save();

            return "";
        }else{
            return "Erro ao favoritar questão!";
        }
    }
    
    public function notUsedAlternative($idTestQuestion,$choosenAlternative){
        if($this->verifyQuestion($idTestQuestion) && 
            $this->verifyChoosenAlternative($choosenAlternative,$idTestQuestion)){
            
            $alternativeQuestion=TestQuestionAlternative::where('id',$choosenAlternative)->first();
            $alternativeQuestion->not_used=!$alternativeQuestion->not_used;
            $alternativeQuestion->save();

            return "";
        }else{
            return "Erro ao responder a questão!";
        }
    }

    public function deleteQuestions($idTest){
        $allTestQuestion=TestQuestion::where('id_test',$idTest)->get();
        
        foreach ($allTestQuestion as $key => $testQuestion) {
            if($this->verifyQuestion($testQuestion->id)){
                $allTestAlternative=TestQuestionAlternative::where('id_testQuestion',$testQuestion->id_testQuestion)->get();
                foreach ($allTestAlternative as $key => $testAlternative) {
                    $testAlternative->delete();
                }
            }else{
                return "Não foi possivel deletar as questões";
            }
        }
    }

    private function verifyQuestion($idTestQuestion){
        $isOk=true;
        $testQuestion=TestQuestion::where('id',$idTestQuestion)->first();
        $test=Test::where('id',$testQuestion->id_test)->first();
        
        if($test != null){
            if($testQuestion==null){
                $isOk=false;
            }
        }

        return $isOk;
    }

    
    private function verifyChoosenAlternative($choosenAlternative,$idTestQuestion){
        $isOk=true;

        $alternative=TestQuestionAlternative::where('id',$choosenAlternative)
        ->where('id_testQuestion',$idTestQuestion)->first();
        
        if($alternative == null){
            $isOk=false;
        }else{
            $defaultAlternative=DefaultAlternativeQuestion::where('id',$alternative->id_default_alternative)->first();
            if($defaultAlternative == null){
                $isOk=false;
            }

            if($defaultAlternative->is_correct != $alternative->is_correct){
                $isOk=false;
            }
        }

        return $isOk;
    }
}