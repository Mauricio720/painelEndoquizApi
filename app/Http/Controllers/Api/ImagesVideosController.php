<?php

namespace App\Http\Controllers\Api;

use App\Models\ImagesVideo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImagesVideosController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

     /**
     * @OA\POST(
     *      path="/get_images_videos",
     *      summary="GETALLIMAGESVIDEOS",
     *      description="Rota para retornar os videos e imagens",
     *      tags={"Endoquiz"},
     *      @OA\Parameter(
     *          name="token",
     *          description="Informe o token do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *       @OA\Parameter(
     *          name="idUser",
     *          description="Informe o id do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       
     *      @OA\Parameter(
     *          name="title",
     *          description="Informe o titulo",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          
     *      @OA\Parameter(
     *          name="description",
     *          description="Informe a descrição",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\Parameter(
     *          name="page",
     *          description="Informe a pagina",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *           @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */


    public function getImagesVideos(Request $request){
        $array=['error'=>''];
        $data=$request->only('idUser','token','title','description','page');
        $page=$request->filled('page')?$data['page']:1;

        $imagesVideos=ImagesVideo::paginate(20,['*'],'page',$page);
        
        if($request->hasAny(['title','description'])){
            $title=$request->input('title');
            $description=$request->input('description');
            
            $imagesVideos=ImagesVideo::where('title','LIKE','%'.$title.'%')
                ->where('description','LIKE','%'.$description.'%')
                ->paginate(20,['*'],'page',$page);
        }

        $array['imagesVideos']=$this->filterImagesVideo($imagesVideos);

        return $array;
    }

    private function filterImagesVideo($imagesVideos){
        $allImagesVideo=[];
        
        foreach ($imagesVideos as $key => $item) {
           $newItem=[
                'id'=>$item->id,
                'title'=>$item->title,
                'description'=>$item->description,
                'image'=>asset('storage/imagesVideos/'.$item->image),
                'linkVideo'=>$item->linkVideo
           ];

           $allImagesVideo[]=$newItem;
        }

        return $allImagesVideo;
    }
}
