<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\DefaultAlternativeQuestion;
use App\Models\DefaultQuestion;
use App\Models\Subject;
use App\Models\SubjectTopic;
use App\Models\User;
use App\Util\QuestionValidation;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data=[];

        $data['allSubtopic']=SubjectTopic::all();
        $data['default_questions']=DefaultQuestion::where('active',1)->orderBy('registerDate','desc')
            ->orderBy('registerTime','desc')->paginate(10);
        $data['default_questions_pagination']=$data['default_questions'];
        
        $data['question']="";
        if($request->filled('question')){
            $question=$request->input('question');
            $data['default_questions']=$this->filterQuestions($question);
            $data['default_questions_pagination']=$this->filterQuestions($question);
            $data['question']=$question;
        }

        $allDefaultQuestions=[];

        foreach ($data['default_questions'] as $key => $default_question) {
            $subjectSubtopicsArray=explode(',',$default_question->id_subject_topics);
           
            $allDefaultQuestions[$key]['default_question']=$default_question;
            $allDefaultQuestions[$key]['default_alternatives']=DefaultAlternativeQuestion::
                where('id_default_question',$default_question->id)->get();
            $allDefaultQuestions[$key]['default_question_subtopic']=[];
            
            foreach ($subjectSubtopicsArray as $subtopicId) {
                $subtopicName=SubjectTopic::where('id',$subtopicId)->first()->name;
                if(!in_array($subtopicName,$allDefaultQuestions[$key]['default_question_subtopic'])){
                    $allDefaultQuestions[$key]['default_question_subtopic'][]=$subtopicName;
                }
            }
        }

        $data['default_questions']=$allDefaultQuestions;
        $data['subtopics']=[];

        if($request->has('checkSubtopic')){
            $checkSubtopicsChoose=$request->input('checkSubtopic');
            $data['default_questions']=$this->filterCheckSubtopic($data['default_questions'],$checkSubtopicsChoose);
            $data['subtopics']=$checkSubtopicsChoose;
        }
        
        return view('dashboard.questions.allQuestions',$data);
    }

    private function filterQuestions($question){
        return DefaultQuestion::where('active',1)
        ->where('question','LIKE',"%".$question."%")->orderBy('registerDate','desc')
        ->orderBy('registerTime','desc')->paginate(10);
    }

    private function filterCheckSubtopic($default_questions,$checkSubtopicsChoose){
        $default_questions_filter=[];
        $checkSubtopicsChoose=$checkSubtopicsChoose;

        foreach ($default_questions as $key => $question) {
            $topicsIds=explode(',',$question['default_question']->id_subject_topics);
            foreach ($checkSubtopicsChoose as $subtopicChoose) {
                if(in_array($subtopicChoose,$topicsIds)){
                    $default_questions_filter[]=$question;
                }
            }
        }

        return $default_questions_filter;
    }

    public function addQuestionView(Request $request){
        $data['allSubject']=Subject::all();
        $allSubjectsComplete=[];
        
        foreach ($data['allSubject'] as $key => $subject) {
            $allSubjectsComplete[$key]['subject']=$subject;
            $allSubjectsComplete[$key]['user']=User::where('id',$subject->idRegisterUser)->first();
            $allSubjectsComplete[$key]['subtopics']=SubjectTopic::where('idSubject',$subject->id)->get();
        }

        $data['allSubject']=$allSubjectsComplete;
        $data['subtopicChoose']=intVal($request->input('subtopicChoose'));
      
        return view('dashboard.questions.addQuestion',$data);
    }

    public function addDefautQuestion(Request $request){
        $data=$request->only(['question','subject_subtopics','alternatives','imageFile',
        'videoLink','justifyContent','videoLinkJustify','imageFileJustify','premiumQuestion']);
        
        if($request->filled(['question','subject_subtopics','alternatives'])){
            $subjectSubtopics=json_decode($data['subject_subtopics'],false);
            $alternatives=json_decode($data['alternatives']);
            
            $questionValidation=new QuestionValidation();
            if($questionValidation->verifyJustOneIsCorrect($alternatives) 
                && $questionValidation->verifyTotalSubtopics($subjectSubtopics)
                && $questionValidation->verifyTotalAlternatives($alternatives)){
                
                $imageName="";    
                if($request->has('imageFile')){
                    $imageFile=$data['imageFile'];
                    $imageName=$this->uploadImageQuestion($imageFile,$imageName);
                }   
                
                $imageJustify="";
                if($request->has('imageFileJustify')){
                    $imageJustify=$data['imageFileJustify'];
                    $imageJustify=$this->uploadImageQuestion($imageJustify,$imageName);
                }  
                
                $defaultQuestion=new DefaultQuestion();    
                $defaultQuestion->question=$data['question'];
                $defaultQuestion->id_subject_topics=implode(',',$subjectSubtopics);
                $defaultQuestion->image=$imageName;
                $defaultQuestion->registerDate=date('Y-m-d');
                $defaultQuestion->registerTime=date('H:i:s');
                $defaultQuestion->video=$request->filled('videoLink')?$data['videoLink']:'';
                $defaultQuestion->premium=$data['premiumQuestion'];
                $defaultQuestion->active=1;
                $defaultQuestion->justifyContent=$request->filled('justifyContent')?$data['justifyContent']:'';
                $defaultQuestion->justifyVideoLink=$request->filled('videoLinkJustify')?$data['videoLinkJustify']:'';
                $defaultQuestion->justifyImage=$imageJustify;
                $defaultQuestion->save();

                foreach ($alternatives as $key => $alternative) {
                    $defaultAlternativeQuestion=new DefaultAlternativeQuestion();
                    $defaultAlternativeQuestion->id_default_question=$defaultQuestion->id;
                    $defaultAlternativeQuestion->description=$alternative->alternativeText;
                    $defaultAlternativeQuestion->is_correct=$alternative->isCorrect;
                    $defaultAlternativeQuestion->save();
                }
            }else{
               
                return redirect()->back()->withErrors("Erro ao adicionar questão");
            }
        }

        return redirect()->route('allQuestions');
    }

    public function editQuestionView($idDefaultQuestion){
        $data=[];

        $data['defaultQuestion']=DefaultQuestion::where('id',$idDefaultQuestion)->first();
        $data['defaultQuestionAlternatives']=DefaultAlternativeQuestion::
            where('id_default_question',$data['defaultQuestion']->id)->get();
        $data['allSubject']=Subject::all();
        $allSubjectsComplete=[];
        
        foreach ($data['allSubject'] as $key => $subject) {
            $allSubjectsComplete[$key]['subject']=$subject;
            $allSubjectsComplete[$key]['user']=User::where('id',$subject->idRegisterUser)->first();
            $allSubjectsComplete[$key]['subtopics']=SubjectTopic::where('idSubject',$subject->id)->get();
        }

        $data['allSubject']=$allSubjectsComplete;
        
        return view('dashboard.questions.editQuestion',$data);
    }
    

    public function editDefautQuestion(Request $request){
        $data=$request->only(['idDefaultQuestion','question','subject_subtopics','alternatives',
            'imageFile','videoLink','justifyContent','videoLinkJustify','imageFileJustify','premiumQuestion']);
        
        if($request->filled(['idDefaultQuestion','question','subject_subtopics','alternatives'])){
            $subjectSubtopics=json_decode($data['subject_subtopics'],false);
            $alternatives=json_decode($data['alternatives']);
            
            $questionValidation=new QuestionValidation();
            if($questionValidation->verifyJustOneIsCorrect($alternatives) 
                && $questionValidation->verifyTotalSubtopics($subjectSubtopics)
                && $questionValidation->verifyTotalAlternatives($alternatives)){
                
                $imageName="";    
                if($request->has('imageFile')){
                    $imageFile=$data['imageFile'];
                    $imageName=$this->uploadImageQuestion($imageFile,$request,$imageName);
                }   
                
                $imageJustify="";
                if($request->has('imageFileJustify')){
                    $imageJustify=$data['imageFileJustify'];
                    $imageJustify=$this->uploadImageQuestion($imageJustify,$request,$imageName);
                }  
                
                $defaultQuestion=DefaultQuestion::where('id',$data['idDefaultQuestion'])->first();    
                $defaultQuestion->question=$data['question'];
                $defaultQuestion->id_subject_topics=implode(',',$subjectSubtopics);
                $defaultQuestion->premium=$data['premiumQuestion'];
                $defaultQuestion->image=$request->has('imageFile')?$imageName:$defaultQuestion->image;
                $defaultQuestion->video=$data['videoLink'];
                $defaultQuestion->justifyContent=$data['justifyContent'];
                $defaultQuestion->justifyVideoLink=$data['videoLinkJustify'];
                $defaultQuestion->justifyImage=$request->has('imageFileJustify')?$imageJustify:$defaultQuestion->justifyImage;
                $defaultQuestion->save();
                
                $allAlternatives=DefaultAlternativeQuestion::where('id_default_question',$defaultQuestion->id)->get();
                foreach ($allAlternatives as $key => $alternative) {
                    $alternative->delete();
                }
                
                foreach ($alternatives as $key => $alternative) {
                    $defaultAlternativeQuestion=new DefaultAlternativeQuestion();
                    $defaultAlternativeQuestion->id_default_question=$defaultQuestion->id;
                    $defaultAlternativeQuestion->description=$alternative->alternativeText;
                    $defaultAlternativeQuestion->is_correct=$alternative->isCorrect;
                    $defaultAlternativeQuestion->save();
                }
            }else{
              
                return redirect()->back()->withErrors("Erro ao adicionar questão");
            }
        }

        return redirect()->route('allQuestions');
    }
    
    private function uploadImageQuestion($imageFile,$imageName){
        $imageName=md5(rand(0,99999).rand(0,99999)).'.'.$imageFile->getClientOriginalExtension();
        if($imageFile->getClientOriginalExtension() == "php" || $imageFile->getClientOriginalExtension() == "js" ){
            return redirect()->back()->withErrors("Extensão inválida!!");
        }
        
        $pathImage="questionsImages/";
        $imageFile->storeAs($pathImage,$imageName);

        return $imageName;
    }

   

    public function updateDefautQuestionText(Request $request){
        $data=$request->only(['idDefaultQuestion','content']);
       
        $request->validate([
            'idDefaultQuestion'=>['required'],
            'content'=>['required','string']
        ],[],[
            'content'=>'conteúdo da questão'
        ]);

      
        if($request->filled(['idDefaultQuestion','content'])){
            $defaultQuestion=DefaultQuestion::where('id',$data['idDefaultQuestion'])->first();
          
            $defaultQuestion->question=$data['content'];
            $defaultQuestion->save();
        }

        return redirect()->route('allQuestions');
    }


    public function updateDefautAlternativeQuestionText(Request $request){
        $data=$request->only(['idAlternative','content']);
       
        $request->validate([
            'idAlternative'=>['required'],
            'content'=>['required','string']
        ],[],[
            'content'=>'conteúdo da alternativa'
        ]);

      
        if($request->filled(['idAlternative','content'])){
            $defaultAlternativeQuestion=DefaultAlternativeQuestion::where('id',$data['idAlternative'])->first();
            $defaultAlternativeQuestion->description=$data['content'];
            $defaultAlternativeQuestion->save();
        }

        return redirect()->route('allQuestions');
    }

    public function deleteDefaultQuestion($idDefaultQuestion){
        $defaultQuestion=DefaultQuestion::where('id',$idDefaultQuestion)->first();
        
        if($defaultQuestion != null ){
            $defaultQuestion->active=0;
            $defaultQuestion->save();
        }

        return redirect()->route('allQuestions');
    }
}
