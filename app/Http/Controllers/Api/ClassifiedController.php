<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Util\ClassifiedOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClassifiedController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    
      /**
     * @OA\POST(
     *      path="/get_classified",
     *      summary="GETALLCLASSIFIED",
     *      description="Rota para retornar todos os classificados com os tópicos",
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

    
    public function getAllClassified(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','token']);
        
        $errors=Validator::make($data,[
            'token'=>'required',
            'idUser'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $classifiedOrganization=new ClassifiedOrganization();
                $array['allClassified']=$classifiedOrganization->organizeClassified();
            }else{
                $array['error']="Usuário Inválido";
            }
        }

        return $array;
    }

     /**
     * @OA\POST(
     *      path="/get_classified_subtopic",
     *      summary="GETALLCLASSIFIEDSUBTOPICS",
     *      description="Rota para retornar todos os subtópicos e assuntos de algum topico do classificado",
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
     *          name="idTopic",
     *          description="Informe o id do tópico",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  
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

    public function getSubtopicClassified(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTopic','token']);
        
        $errors=Validator::make($data,[
            'token'=>'required',
            'idUser'=>'required',
            'idTopic'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $classifiedOrganization=new ClassifiedOrganization();
                $array['subtopicsClassified']=$classifiedOrganization->organizeSubtopicsClassified($data['idTopic']);
            }else{
                $array['error']="Usuário Inválido";
            }
        }

        return $array;
    }
}
