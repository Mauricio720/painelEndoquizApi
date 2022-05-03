<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\DefaultQuestion;
use App\Models\Subject;
use App\Models\SubjectTopic;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data=[];
        $data['allSubject']=Subject::orderBy('id','DESC')->paginate(10);
        $data['allSubjectPaginate']=$data['allSubject'];
        $data['subject']="";
        
        if($request->filled('subject')){
            $subjectName=$request->input('subject');
            $data['allSubject']=Subject::where('name','LIKE','%'.$subjectName.'%')->orderBy('id','DESC')->paginate(10);
            $data['allSubjectPaginate']=$data['allSubject'];
            $data['subject']=$subjectName;
        }
        
        $allSubjectsComplete=[];
        
        foreach ($data['allSubject'] as $key => $subject) {
            $allSubjectsComplete[$key]['subject']=$subject;
            $allSubjectsComplete[$key]['user']=User::where('id',$subject->idRegisterUser)->first();
            $allSubjectsComplete[$key]['subtopics']=SubjectTopic::where('idSubject',$subject->id)->get();
        }

        $data['allSubject']=$allSubjectsComplete;
        
        return view('dashboard.subjects.allSubject',$data);
    }

    public function addSubject(Request $request){
        $data=$request->only('name');
        Validator::make($data,[
            'name'=>['required','string','max:255']
        ],[],['name'=>'área'])->validate();

        $subject=new Subject();
        $subject->name=$data['name'];
        $subject->registerDate=date('Y-m-d');
        $subject->registerTime=date('H:i:s');
        $subject->idRegisterUser=Auth::user()->id;
        $subject->save();

        return redirect()->route('allSubjects');
    }

    public function editSubject(Request $request){
        $data=$request->only('idSubject','name');
        Validator::make($data,[
            'idSubject'=>['required','int'],
            'name'=>['required','string','max:255']
        ],[],['name'=>'Assunto'])->validate();

        $subject=Subject::where('id',$data['idSubject'])->first();
        $subject->name=$data['name'];
        $subject->save();

        return redirect()->route('allSubjects');
    }

    public function deleteSubject($idSubject){
        $subject=Subject::where('id',$idSubject)->first();
        
        if($subject != null){
            $subtopics=SubjectTopic::where('idSubject',$subject->id)->get();
            foreach ($subtopics as $key => $subtopic) {
                if($this->verifyQuestion($subtopic->id)){
                    return redirect()->route('allSubjects')
                        ->withErrors('Não foi possivel deletar a area '.$subject->name.' 
                            pois há assuntos dessa area sendo utilizados nas questões!');
                }
                $subtopic->delete();
            }
            $subject->delete();
        }

        return redirect()->route('allSubjects');
    }

    private function verifyQuestion($idSubtopic){
        $allDefaultQuestions=DefaultQuestion::all();
        $find=false;
        foreach ($allDefaultQuestions as $key => $defaultQuestion) {
            $subjects=explode(',',$defaultQuestion->id_subject_topics);
            if(in_array($idSubtopic,$subjects)){
                $find=true;
            }
        }

        return $find;
    }


    public function addSubtopic(Request $request){
        $data=$request->only('idSubject','name');
        Validator::make($data,[
            'idSubject'=>['required','int'],
            'name'=>['required','string','max:255']
        ],[],['name'=>'Assunto'])->validate();

        $subject=new SubjectTopic();
        $subject->name=$data['name']; 
        $subject->idSubject=$data['idSubject'];
        $subject->registerDate=date('Y-m-d');
        $subject->registerTime=date('H:i:s');
        $subject->idRegisterUser=Auth::user()->id;
        $subject->save();

        return redirect()->route('allSubjects');
    }

    public function editSubtopic(Request $request){
        $data=$request->only('idSubtopic','name');
        Validator::make($data,[
            'idSubtopic'=>['required','int'],
            'name'=>['required','string','max:255']
        ],[],['name'=>'Assunto'])->validate();

        $subject=SubjectTopic::where('id',$data['idSubtopic'])->first();
        $subject->name=$data['name'];
        $subject->save();

        return redirect()->route('allSubjects');
    }

    public function deleteSubtopic($idSubtopic){
        $subtopic=SubjectTopic::where('id',$idSubtopic)->first();
        
        if($this->verifyQuestion($idSubtopic)){
            return redirect()->route('allSubjects')->withErrors('Não foi possivel deletar o assunto '.$subtopic->name.' pois 
                ele está sendo uilizado em alguma questão!');
        }
        if($subtopic != null){
            $subtopic->delete();
        }

        return redirect()->route('allSubjects');
    }
}
