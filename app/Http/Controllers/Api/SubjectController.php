<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\SubjectTopic;

class SubjectController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api');
    }

     /**
     * @OA\POST(
     *      path="/get_subjects",
     *      summary="GETSUBJECTS",
     *      description="Rota para retornar as areas e assuntos das provas",
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
     *      
     *     @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          
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
    
    

    public function getAllSubject(){
        $array=['error'=>''];
        $array['subject']=$this->makeSubjectArray();
        
        return $array['subject'];
    }

    private function makeSubjectArray(){
        $subjectArray=[];
        $allSubject=Subject::all();

        foreach ($allSubject as $key => $subject) {
            $subjectArray[$key]['id']=$subject->id;
            $subjectArray[$key]['name']=$subject->name;

            $allSubtopics=SubjectTopic::where('idSubject',$subject->id)->get();
            foreach ($allSubtopics as $keySubtopic => $subtopic) {
                $subjectArray[$key]['subtopic'][$keySubtopic]['id']=$subtopic->id;
                $subjectArray[$key]['subtopic'][$keySubtopic]['name']=$subtopic->name;
            }
        }

        return $subjectArray;
    }
}
