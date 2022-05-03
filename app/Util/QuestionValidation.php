<?php

namespace App\Util;

class QuestionValidation{

    public function verifyJustOneIsCorrect($alternatives){
        $numberCorrects=0;
        
        foreach ($alternatives as $key => $alternative) {
           if($alternative->isCorrect){
                $numberCorrects++;
            }
        }

        if($numberCorrects>1){
            return false;
        }else{
            return true;
        }
    }

    public function verifyTotalAlternatives($alternatives){
         if(count($alternatives) < 2 && count($alternatives) > 0){
            return false;
        }else{
            return true;
        }
    }

    public function verifyTotalSubtopics($subjectSubtopics){
        if(count($subjectSubtopics) > 0){
            return true;
        }else{
            return false;
        }
    }
}