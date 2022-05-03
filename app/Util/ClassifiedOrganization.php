<?php

namespace App\Util;

use App\Models\Classified;
use App\Models\ClassifiedData;
use App\Models\ClassifiedSubtopics;
use App\Models\ClassifiedTopics;

class ClassifiedOrganization{

    public function organizeClassified(){
        $allClassifiedArray=[];

        $allClassified=Classified::all();
        foreach ($allClassified as $key => $classified) {
            $classified->image=asset('storage/classified/'.$classified->image);
            $allClassifiedArrayItem['classified']=$classified;
            $allTopics=ClassifiedTopics::where('id_classified',$classified->id)->get();
            
            $classifiedTopicArray=[];
            foreach ($allTopics as $key => $topic) {
                $topic->image=asset('storage/classified/'.$topic->image);
                $classifiedTopicArray[]=$topic;
            }

            $allClassifiedArrayItem['classified']['classifiedTopics']=$classifiedTopicArray;
            
            $allClassifiedArray[]=$allClassifiedArrayItem;
        }

        return $allClassifiedArray;
    }

    public function organizeSubtopicsClassified($idTopic){
        $allSubtopicsArray=[];
        $allSubtopics=ClassifiedSubtopics::where('id_classified_topic',$idTopic)->get();
        
        foreach ($allSubtopics as $key => $subtopic) {
            $subtopic->image=asset('storage/classified/'.$subtopic->image);

            $allSubtopicsItem['subtopic']=$subtopic;
            $allSubtopicsContent=ClassifiedData::where('id_classified_subtopic',$subtopic->id)->get();
            
            $contentArray=[];
            foreach ($allSubtopicsContent as $key => $content) {
                $content->image=asset('storage/classified/'.$content->image);
                $contentArray[]=$content;
            }
            $allSubtopicsItem['subtopic']['content']=$contentArray;
            $allSubtopicsArray[]=$allSubtopicsItem;
        }

        return $allSubtopicsArray;
    }

}