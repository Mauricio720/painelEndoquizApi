<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Classified;
use App\Models\ClassifiedData;
use App\Models\ClassifiedSubtopics;
use App\Models\ClassifiedTopics;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClassifiedController extends Controller
{   

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data=[];

        $data['allClassified']=Classified::orderBy('id','DESC')->paginate(10);;
        $data['allClassifiedPaginate']=$data['allClassified'];
        $allClassifiedComplete=[];

        $data['classified']="";
        if($request->filled('classified')){
            $classified=$request->input('classified');
            $data['allClassified']=$this->filterClassified($classified);
            $data['allClassifiedPaginate']=$this->filterClassified($classified);
            $data['classified']=$classified;
        }
        
        foreach ($data['allClassified'] as $key => $classified) {
            $allClassifiedComplete[$key]['classified']=$classified;
            $allClassifiedComplete[$key]['user']=User::where('id',$classified->idRegisterUser)->first();
            $allClassifiedComplete[$key]['classifiedTopics']=ClassifiedTopics::where('id_classified',$classified->id)->get();
        }

        $data['allClassified']=$allClassifiedComplete;

        return view('dashboard.classified.all_classified',$data);
    }

    private function filterClassified($classified){
        return Classified::where('name','LIKE','%'.$classified.'%')->orderBy('id','DESC')->paginate(10);
    }

    public function addClassified(Request $request){
        $data=$request->only('name','imageClassified','linkVideo');
        
        Validator::make($data,[
            'name'=>['required','max:255']
        ],[],['name'=>'nome do classificado'])->validate();

        $imageName="";
        if($request->has('imageClassified')){
            $imageFile=$data['imageClassified'];
            $imageName=$this->uploadImage($imageFile);
        }

        if($request->filled('name')){
            $classified=new Classified();
            $classified->name=$data['name'];
            $classified->image=$imageName;
            $classified->linkVideo=$data['linkVideo'];
            $classified->registerDate=date('Y-m-d');
            $classified->registerTime=date('H:i:s');
            $classified->idRegisterUser=Auth::user()->id;
            $classified->save();
        }

        return redirect()->route('allClassified');
    }

    public function editClassified(Request $request){
        $data=$request->only(['idClassified','name','imageClassified','linkVideo']);
        
        Validator::make($data,[
            'name'=>['required','max:255']
        ],[],['name'=>'nome do classificado'])->validate();

        $classified=Classified::where('id',$data['idClassified'])->first();
        $imageName=$classified->image;
        
        if($request->has('imageClassified')){
            $imageFile=$data['imageClassified'];
            $imageName=$this->uploadImage($imageFile);
        }

        if($request->filled(['name'])){
           $classified->name=$data['name'];
            $classified->image=$imageName;
            $classified->linkVideo=$data['linkVideo'];
            $classified->save();
        }

        return redirect()->route('allClassified');
    }

    public function deleteClassified($idClassified){
        $classified=Classified::where('id',$idClassified)->first();
        Storage::delete('classified/'.$classified->image);

        if($classified != null){
            $this->deleteClassifiedTopics($classified->id); 
            $classified->delete();
        }
        
        return redirect()->route('allClassified');
    }

    private function deleteClassifiedTopics($idClassified){
        $allClassifiedTopics=ClassifiedTopics::where('id_classified',$idClassified)->get();
        foreach ($allClassifiedTopics as $key => $topics) {
            $this->deleteClassifiedSubtopics($topics->id);
            $topics->delete();
        }
    }

    private function deleteClassifiedSubtopics($idTopic){
        $allClassifiedSubTopics=ClassifiedSubtopics::where('id_classified_topic',$idTopic)->get();
        foreach ($allClassifiedSubTopics as $key => $subtopics) {
            $this->deleteClassifiedData($subtopics->id);
            $subtopics->delete();
        }
    }

    private function deleteClassifiedData($idSubtopic){
        $allClassifiedData=ClassifiedData::where('id_classified_subtopic',$idSubtopic)->get();
        foreach ($allClassifiedData as $key => $data) {
            $data->delete();
        }
    }

    public function addClassifiedTopic(Request $request){
        $data=$request->only(['idClassified','name','imageClassified','linkVideo']);
        
        Validator::make($data,[
            'name'=>['required','max:255']
        ],[],['name'=>'nome do tópico'])->validate();

        $imageName="";
        if($request->has('imageClassified')){
            $imageFile=$data['imageClassified'];
            $imageName=$this->uploadImage($imageFile);
        }

        if($request->filled(['idClassified','name'])){
            $classifiedTopic=new ClassifiedTopics();
            $classifiedTopic->name=$data['name'];
            $classifiedTopic->id_classified=$data['idClassified'];
            $classifiedTopic->image=$imageName;
            $classifiedTopic->linkVideo=$data['linkVideo'];
            $classifiedTopic->registerDate=date('Y-m-d');
            $classifiedTopic->registerTime=date('H:i:s');
            $classifiedTopic->idRegisterUser=Auth::user()->id;
            $classifiedTopic->save();
        }

        return redirect()->route('allClassified');
    }

    public function editClassifiedTopic(Request $request){
        $data=$request->only(['idTopic','name','imageClassified','linkVideo']);
        
        Validator::make($data,[
            'name'=>['required','max:255']
        ],[],['name'=>'nome do tópico'])->validate();

        $classifiedTopic=ClassifiedTopics::where('id',$data['idTopic'])->first();
        $imageName=$classifiedTopic->image;
        if($request->has('imageClassified')){
            $imageFile=$data['imageClassified'];
            $imageName=$this->uploadImage($imageFile);
        }

        if($request->filled(['idTopic','name'])){
            $classifiedTopic->name=$data['name'];
            $classifiedTopic->image=$imageName;
            $classifiedTopic->linkVideo=$data['linkVideo'];
            $classifiedTopic->save();
        }

        return redirect()->route('allClassified');
    }

    public function deleteClassifiedTopic($idTopic){
        $classifiedTopic=ClassifiedTopics::where('id',$idTopic)->first();
        
        Storage::delete('classified/'.$classifiedTopic->image);

        if($classifiedTopic != null){
             $classifiedTopic->delete();
        }
        
        return redirect()->route('allClassified');
    }

    public function classifiedContent($idTopic,Request $request){
        $data=[];
        $data['topic']=ClassifiedTopics::where('id',$idTopic)->first();
        $data['allClassifiedSubtopics']=ClassifiedSubtopics::where('id_classified_topic',$idTopic)->paginate(10);
        $data['allClassifiedSubtopicsPagination']=$data['allClassifiedSubtopics'];
        $allClassifiedSubtopicsComplete=[];
        
        $data['classifiedSubtopic']="";
        if($request->filled('classifiedSubtopic')){
            $classifiedSubtopic=$request->input('classifiedSubtopic');
            $data['allClassifiedSubtopics']=$this->filterClassifiedSubtopic($classifiedSubtopic);
            $data['allClassifiedSubtopicsPagination']=$this->filterClassifiedSubtopic($classifiedSubtopic);
            $data['classifiedSubtopic']=$classifiedSubtopic;
        }

        foreach ($data['allClassifiedSubtopics'] as $key => $classifiedSubtopic) {
            $allClassifiedSubtopicsComplete[$key]['classifiedSubtopic']=$classifiedSubtopic;
            $allClassifiedSubtopicsComplete[$key]['user']=User::where('id',$classifiedSubtopic->idRegisterUser)->first();
            $allClassifiedSubtopicsComplete[$key]['classifiedData']=ClassifiedData::where('id_classified_subtopic',$classifiedSubtopic->id)->get();
        }

        $data['allClassifiedSubtopics']=$allClassifiedSubtopicsComplete;
        
        return view('dashboard.classified.classified_content',$data);
    }

    private function filterClassifiedSubtopic($topic){
        return ClassifiedSubtopics::where('name','LIKE','%'.$topic."%")->paginate(10);
    }

    public function addSubtopicClassified(Request $request){
        $data=$request->only(['idTopic','name','imageClassified','linkVideo']);
        
        Validator::make($data,[
            'name'=>['required','max:255']
        ],[],['name'=>'nome do subtópico'])->validate();

        $imageName="";
        if($request->has('imageClassified')){
            $imageFile=$data['imageClassified'];
            $imageName=$this->uploadImage($imageFile);
        }

        if($request->filled(['idTopic','name'])){
            $classifiedSubtopic=new ClassifiedSubtopics();
            $classifiedSubtopic->name=$data['name'];
            $classifiedSubtopic->id_classified_topic=$data['idTopic'];
            $classifiedSubtopic->image=$imageName;
            $classifiedSubtopic->linkVideo=$data['linkVideo'];
            $classifiedSubtopic->registerDate=date('Y-m-d');
            $classifiedSubtopic->registerTime=date('H:i:s');
            $classifiedSubtopic->idRegisterUser=Auth::user()->id;
            $classifiedSubtopic->save();
        }

        return redirect()->route('classifiedContent',['idTopic'=>$data['idTopic']]);
    }

    public function editSubtopicClassified(Request $request){
        $data=$request->only(['idTopic','idClassifiedSubtopic','name','imageClassified','linkVideo']);
        
        Validator::make($data,[
            'name'=>['required','max:255']
        ],[],['name'=>'nome do subtópico'])->validate();

        
        $classifiedSubtopic=ClassifiedSubtopics::where('id',$data['idClassifiedSubtopic'])->first();

        $imageName=$classifiedSubtopic->image;
        if($request->has('imageClassified')){
            $imageFile=$data['imageClassified'];
            $imageName=$this->uploadImage($imageFile);
        }

        if($request->filled(['idClassifiedSubtopic','name'])){
            $classifiedSubtopic->name=$data['name'];
            $classifiedSubtopic->image=$imageName;
            $classifiedSubtopic->linkVideo=$data['linkVideo'];
            $classifiedSubtopic->save();
        }

        return redirect()->route('classifiedContent',['idTopic'=>$data['idTopic']]);
    }

    public function deleteSubtopicClassified($idSubtopic){
        $classifiedSubtopic=ClassifiedSubtopics::where('id',$idSubtopic)->first();
        Storage::delete('classified/'.$classifiedSubtopic->image);
        
        if($classifiedSubtopic != null){
            $this->deleteContent($idSubtopic);
            $classifiedSubtopic->delete();
        }
        
        return redirect()->route('classifiedContent',['idTopic'=>$classifiedSubtopic->id_classified_topic]);
    }

    private function deleteContent($idSubtopic){
        $allClassifiedContent=ClassifiedData::where('id_classified_subtopic',$idSubtopic)->get();
        foreach ($allClassifiedContent as $key => $classifiedContent) {
            Storage::delete('classified/'.$classifiedContent->image);

            $classifiedContent->delete();
        }
    }

    public function addContentClassified(Request $request){
        $data=$request->only(['idTopic','idClassifiedSubtopic','name','imageClassified','linkVideo']);
        
        Validator::make($data,[
            'name'=>['required']
        ],[],['name'=>'conteúdo'])->validate();

        $imageName="";
        if($request->has('imageClassified')){
            $imageFile=$data['imageClassified'];
            $imageName=$this->uploadImage($imageFile);
        }

        if($request->filled(['idClassifiedSubtopic','name'])){
            $classifiedData=new ClassifiedData();
            $classifiedData->content=$data['name'];
            $classifiedData->id_classified_subtopic=$data['idClassifiedSubtopic'];
            $classifiedData->image=$imageName;
            $classifiedData->linkVideo=$data['linkVideo'];
            $classifiedData->registerDate=date('Y-m-d');
            $classifiedData->registerTime=date('H:i:s');
            $classifiedData->idRegisterUser=Auth::user()->id;
            $classifiedData->save();
        }

        return redirect()->route('classifiedContent',['idTopic'=>$data['idTopic']]);
    }

    public function editContentClassified(Request $request){
        $data=$request->only(['idTopic','idClassifiedData','name','imageClassified','linkVideo']);
        
        Validator::make($data,[
            'name'=>['required']
        ],[],['name'=>'conteúdo'])->validate();
        
        $classifiedData=ClassifiedData::where('id',$data['idClassifiedData'])->first();

        $imageName=$classifiedData->image;
        if($request->has('imageClassified')){
            $imageFile=$data['imageClassified'];
            $imageName=$this->uploadImage($imageFile);
        }

        if($request->filled(['idClassifiedData','name'])){
            $classifiedData->content=$data['name'];
            $classifiedData->image=$imageName;
            $classifiedData->linkVideo=$data['linkVideo'];
            $classifiedData->save();
        }

        return redirect()->route('classifiedContent',['idTopic'=>$data['idTopic']]);
    }

    public function deleteContentClassified($idContent){
        $classifieData=ClassifiedData::where('id',$idContent)->first();
        
        if($classifieData != null){
            $classifieData->delete();
        }
        
        $classifiedSubtopic=ClassifiedSubtopics::where('id',$classifieData->id_classified_subtopic)->first();
        return redirect()->route('classifiedContent',['idTopic'=>$classifiedSubtopic->id_classified_topic]);
    }

    private function uploadImage($imageFile){
        $imageName=md5(rand(0,99999).rand(0,99999)).'.'.$imageFile->getClientOriginalExtension();
        if($imageFile->getClientOriginalExtension() == "php" || $imageFile->getClientOriginalExtension() == "js" ){
            return redirect()->back()->withErrors("Extensão inválida!!");
        }
        
        $pathImage="classified/";
        $imageFile->storeAs($pathImage,$imageName);

        return $imageName;
    }
    
}
