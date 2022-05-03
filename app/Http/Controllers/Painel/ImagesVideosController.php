<?php

namespace App\Http\Controllers\Painel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ImagesVideo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImagesVideosController extends Controller
{   

    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(Request $request){
        $data=[];
        
        $data['allImagesVideo']=ImagesVideo::paginate(5);
        $data['title']="";
        $data['description']="";
        
        if($request->hasAny('title','description')){
            $data=$this->filterImagesVideo($request,$data);
        }

        return view('dashboard.imagesVideo.allImagesVideos',$data);
    }

    private function filterImagesVideo($request,$data){
        $imagesVideoQuery=ImagesVideo::query();
            
        $title=$request->input('title');
        $description=$request->input('description');

        if($request->filled('title')){
            $imagesVideoQuery->where('title','LIKE','%'.$title.'%');
        }    

        if($request->filled('description')){
            $imagesVideoQuery->where('description','LIKE','%'.$description.'%');
        }

        $data['title']=$title;
        $data['description']=$description;
        $data['allImagesVideo']=$imagesVideoQuery->paginate(5);
        
        return $data;
    }

    public function add(Request $request){
        $data=$request->only(['title','description','imgFile','linkVideo']);
        $this->validatorImgVideo($data);
        
        $imageFile=$data['imgFile'];
        $imgName=$this->uploadImage($imageFile);

        if($request->hasAny(['imgFile','linkVideo'])){
            $imagesVideo=new ImagesVideo();
            $imagesVideo->title=$data['title'];
            $imagesVideo->description=$data['description'];
            $imagesVideo->image=$imgName;
            $imagesVideo->linkVideo=$data['linkVideo'];
            $imagesVideo->save();
        }else{
            return redirect()->route('allImagesVideos')
                ->withErrors('Você precisa preencher pelo menos um link de video ou imagem');
        }

        return redirect()->route('allImagesVideos');
    }

    public function edit(Request $request){
        $data=$request->only(['id','title','description','imgFile','linkVideo']);
        $this->validatorImgVideo($data);
        
        $imagesVideo=ImagesVideo::where('id',$data['id'])->first();
        
        $imgName=$imagesVideo->image;
        if($request->has('imgFile')){
            $imageFile=$data['imgFile'];
            $imgName=$this->uploadImage($imageFile);
        }
        
        if($request->hasAny(['imgFile','linkVideo'])){
            $imagesVideo->title=$data['title'];
            $imagesVideo->description=$data['description'];
            $imagesVideo->image=$imgName;
            $imagesVideo->linkVideo=$data['linkVideo'];
            $imagesVideo->save();
        }else{
            return redirect()->route('allImagesVideos')
                ->withErrors('Você precisa preencher pelo menos um link de video ou imagem');
        }

        return redirect()->route('allImagesVideos');
    }

    private function uploadImage($imageFile){
        $imageName=md5(rand(0,99999).rand(0,99999)).'.'.$imageFile->getClientOriginalExtension();
        if($imageFile->getClientOriginalExtension() == "php" || $imageFile->getClientOriginalExtension() == "js" ){
            return redirect()->back()->withErrors("Extensão inválida!!");
        }
        
        $pathImage="imagesVideos/";
        $imageFile->storeAs($pathImage,$imageName);

        return $imageName;
    }

    private function validatorImgVideo($data){
        return Validator::make($data,[
            'title'=>['required','string','max:150'],
            'description'=>['required','string'],
            'linkVideo'=>['url','max:150','nullable'],
            'image'=>[]
        ],[],[
            'title'=>'titulo',
            'description'=>'descrição'
        ])->validate();
    }

    public function delete($id){
        $imageVideo=ImagesVideo::where('id',$id)->first();

        if($imageVideo != null){
            if($imageVideo->image != ""){
                Storage::delete('imagesVideos/'.$imageVideo->image);
            }
            $imageVideo->delete();
        }

        return redirect()->route('allImagesVideos');
    }
}
