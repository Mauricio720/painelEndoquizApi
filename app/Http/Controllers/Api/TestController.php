<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubjectTopic;
use App\Models\Test;
use App\Util\TestOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class TestController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    
      /**
     * @OA\POST(
     *      path="/get_all_test",
     *      summary="GETALLTEST",
     *      description="Rota para retornar todas as rotas desse usuário",
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


    public function getAllTest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','token']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'token'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $array['allTests']=Test::where('idUser',$data['idUser'])->orderBy('id','DESC')->get();
            }else{
                $array['error']="Usuário Inválido";
            }
        }
        
        return $array;
    }

       /**
     * @OA\POST(
     *      path="/get_test",
     *      summary="GETTEST",
     *      description="Rota para retornar a prova do usuário com as questões",
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
     *       @OA\Parameter(
     *          name="idTest",
     *          description="Informe o id da prova",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *       @OA\Parameter(
     *          name="page",
     *          description="Informe o numero da pagina da prova",
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

    public function getTest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idTest','idUser','token','page']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'token'=>'required',
            'idTest'=>['required','int'],
            'page'=>['int']
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();
                if($test != null){
                     $testOrganization=new TestOrganization($test);
                     $array['test']=$testOrganization->getTest($request->filled('page')?$data['page']:1);
                }else{
                     $array['error']="Prova Inválida";   
                }
            }else{
                $array['error']="Usuário Inválido";
            }
        }
        
        return $array;
    }

       /**
     * @OA\POST(
     *      path="/create_test",
     *      summary="CREATETEST",
     *      description="Rota para criar a prova do usuário com as questões",
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
     *       @OA\Parameter(
     *          name="name",
     *          description="Informe o nome/titulo da prova",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *       @OA\Parameter(
     *          name="all_subtopic_test",
     *          description="Informe os ids dos assuntos da prova",
     *          example="1,2,3",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *       @OA\Parameter(
     *          name="start",
     *          description="Informe se ja quer criar a prova e inicia-la",
     *          in="query",
     *          @OA\Schema(
     *              type="boolean"
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

        
    public function createTest(Request $request){
        $array=['error'=>''];

        $data=$request->only(['token','idUser','name','all_subtopic_test','start']);
        
        $errors=Validator::make($data,[
            'start'=>'in:true,false',
            'idUser'=>['required','int'],
            'token'=>['required'],
            'name'=>['required','max:255'],
            'all_subtopic_test'=>['required','max:450']
        ],[],[
            'name'=>'nome da prova',
            'all_subtopic_test'=>'Assuntos'
        ]);
        
        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                if($this->verifySubtopic($data['all_subtopic_test'])){
                    $test=new Test();
                    $test->name=$data['name'];
                    $test->all_subject_test=$data['all_subtopic_test'];
                    $test->idUser=$data['idUser'];
                    $test->registerDate=date('Y-m-d');
                    $test->registerTime=date('H:i:s');
                    $test->test_time='00:00:00';
                    $test->status=1;
                    $test->save();

                    $testOrganization=new TestOrganization($test);
                    $testOrganization->makeTest();
                    
                    $test->totalQuestions=$testOrganization->getTotalQuestions($test->id);
                    $test->save();
                    
                    $startTest=false;
                    if($request->filled('start')){
                        $startTest=$this->transform($data['start']);
                    }

                    if($startTest){
                        $array['error']=$testOrganization->startTest();
                        if($array['error'] == ""){
                            $array['test']=$testOrganization->getTest(1);
                        }
                    }
                    
                }else{
                    $array['error']="Assuntos Inválidos";
                }
            }else{
                $array['error']="Usuário Inválido";

            }
        }

        return $array;
    }

    private function transform($value){
        if($value === 'true' || $value === 'TRUE')
            return true;

        if($value === 'false' || $value === 'FALSE')
            return false;

        return $value;
    }

    private function verifySubtopic($allSubjectTopics){
        $isOk=true;
        $allSubtopicTest=explode(',',$allSubjectTopics);
        
        foreach ($allSubtopicTest as $key => $subtopicId) {
            $subtopic=SubjectTopic::where('id',$subtopicId)->first();
            if($subtopic == null){
                $isOk=false;
            }
        }

        return $isOk;
    }

       /**
     * @OA\POST(
     *      path="/start_test",
     *      summary="STARTTEST",
     *      description="Rota para iniciar a prova (a prova tem que estar recem-criada ou pausada)",
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
     *       @OA\Parameter(
     *          name="idTest",
     *          description="Informe o id da prova",
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


    public function startTest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTest','token']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'idTest'=>['required','int'],
            'token'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
               $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();
               if($test != null){
                    $testOrganization=new TestOrganization($test);
                    $array['error']=$testOrganization->startTest();
                    if($array['error'] == ""){
                        $array['test']=$testOrganization->getTest(1);
                    }
                }else{
                    $array['error']="Prova Inválida";   
               }
            }else{
                $array['error']="Usuário Inválido";
            }
        }
        return $array;
    }


       /**
     * @OA\POST(
     *      path="/pause_test",
     *      summary="PAUSETEST",
     *      description="Rota para pausar a prova ",
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
     *       @OA\Parameter(
     *          name="idTest",
     *          description="Informe o id da prova",
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


    public function pauseTest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTest','token']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'idTest'=>['required','int'],
            'token'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();
                if($test != null){
                    $testOrganization=new TestOrganization($test);
                    $array['error']=$testOrganization->pauseTest();
                    if($array['error'] == ""){
                        $array['test']=$test;
                    }
                }else{
                    $array['error']="Prova Inválida";
                }
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }

        /**
     * @OA\POST(
     *      path="/restart_test",
     *      summary="RESTARTTEST",
     *      description="Rota para reiniciar a prova ",
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
     *       @OA\Parameter(
     *          name="idTest",
     *          description="Informe o id da prova",
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


    public function restartTest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTest','token']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'idTest'=>['required','int'],
            'token'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();
                if($test != null){
                    $testOrganization=new TestOrganization($test);
                    $array['error']=$testOrganization->restartTest();
                    if($array['error'] == ""){
                        $array['test']=$test;
                    }
                }else{
                    $array['error']="Prova Inválida";
                }
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }


      /**
     * @OA\POST(
     *      path="/finish_test",
     *      summary="FINISHTEST",
     *      description="Rota para finalizar a prova  (registra a data e a hora que a prova foi finalizada e 
     *      calcula todo o desempenho do usuário: tempo de prova, questões respondidas e não respondidas, 
     *      acertos e erros (calculado em cima das questões respondidas) e porcentagem de acertos da prova 
     *      (calculado em cima das questões respondidas)",
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
     *       @OA\Parameter(
     *          name="idTest",
     *          description="Informe o id da prova",
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

    public function finishTest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTest','token']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'idTest'=>['required','int'],
            'token'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();
                if($test != null){
                    $testOrganization=new TestOrganization($test);
                    $array['error']=$testOrganization->finishTest();
                    if($array['error'] == ""){
                        $array['test']=$test;
                    }
                    
                }else{
                    $array['error']="Prova Inválida";
                }
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }

       /**
     * @OA\POST(
     *      path="/ask_question",
     *      summary="ASKQUESTION",
     *      description="Rota para responder alguma questão da prova (A prova tem que estar em andamento (status=2))",
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
     *       @OA\Parameter(
     *          name="idTest",
     *          description="Informe o id da prova",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *       @OA\Parameter(
     *          name="idQuestion",
     *          description="Informe o id da questão que quer responder",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\Parameter(
     *          name="choosenAlternative",
     *          description="Informe o id da alternativa escolhida pelo usuário dessa questão",
     *          required=true,
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


    public function askQuestion(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTest','token','idQuestion','choosenAlternative']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'idTest'=>['required','int'],
            'token'=>'required',
            'idQuestion'=>['required','int'],
            'choosenAlternative'=>['required','int']
        ],[],[
            'choosenAlternative'=>'alternativa escolhida'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();
                if($test != null){
                    $testOrganization=new TestOrganization($test);
                    $array['error']=$testOrganization->askQuestion($data['idQuestion'],$data['choosenAlternative']);
                }else{
                    $array['error']="Prova Inválida";
                }
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }

       /**
     * @OA\POST(
     *      path="/annotation_question",
     *      summary="ANNOTATIONQUESTION",
     *      description="Rota para inserir alguma anotação na questão da prova" ,
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
     *       @OA\Parameter(
     *          name="idTest",
     *          description="Informe o id da prova",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *       @OA\Parameter(
     *          name="idQuestion",
     *          description="Informe o id da questão que quer responder",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\Parameter(
     *          name="annotation",
     *          description="Informe a anotação digitada pelo usuário dessa questão",
     *          required=true,
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

    public function annotationQuest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTest','token','idQuestion','annotation']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'idTest'=>['required','int'],
            'token'=>'required',
            'idQuestion'=>['required','int'],
            'annotation'=>['required','string']
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();

                if($test != null){
                    $testOrganization=new TestOrganization($test);
                    $array['error']=$testOrganization->annotationQuest($data['idQuestion'],$data['annotation']);
                }else{
                    $array['error']="Prova Inválida";
                }       
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }

       /**
     * @OA\POST(
     *      path="/get_annotation_question",
     *      summary="GETANNOTATIONQUESTION",
     *      description="Rota para pegas as questões com anotações" ,
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

    public function getAnnotationQuest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','token']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'token'=>'required',
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $testOrganization=new TestOrganization();
                $array['annotation_question']=$testOrganization->getQuestAnnotation();
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }

     /**
     * @OA\POST(
     *      path="/favorite_question",
     *      summary="FAVORITEQUESTION",
     *      description="Rota para favoritar ou desfavoritar alguma questão da prova",
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
     *       @OA\Parameter(
     *          name="idTest",
     *          description="Informe o id da prova",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *       @OA\Parameter(
     *          name="idQuestion",
     *          description="Informe o id da questão que quer favoritar ou desfavoritar",
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

    public function favoriteQuest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTest','token','idQuestion']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'idTest'=>['required','int'],
            'token'=>'required',
            'idQuestion'=>['required','int'],
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();

                if($test != null){
                    $testOrganization=new TestOrganization($test);
                    $array['error']=$testOrganization->favoriteQuest($data['idQuestion']);
                }else{
                    $array['error']="Prova Inválida";
                }       
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }

    
      /**
     * @OA\POST(
     *      path="/not_used_alternative",
     *      summary="NOTUSEDALTERNATIVE",
     *      description="Rota para descartar alguma alternativa da questão",
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
     *       @OA\Parameter(
     *          name="idTest",
     *          description="Informe o id da prova",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *       @OA\Parameter(
     *          name="idQuestion",
     *          description="Informe o id da questão que quer favoritar ou desfavoritar",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *     @OA\Parameter(
     *          name="choosenAlternative",
     *          description="Informe o id da alternativa escolhida pelo usuário para descartar",
     *          required=true,
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
    
    public function notUsedAlternative(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTest','token','idQuestion','choosenAlternative']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'idTest'=>['required','int'],
            'token'=>'required',
            'idQuestion'=>['required','int'],
            ],[],[
            
            'choosenAlternative'=>'alternativa escolhida'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();

                if($test != null){
                    $testOrganization=new TestOrganization($test);
                    $array['error']=$testOrganization->notUsedAlternative($data['idQuestion'],$data['choosenAlternative']);
                }else{
                    $array['error']="Prova Inválida";
                }       
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }


    /**
     * @OA\POST(
     *      path="/get_favorite_question",
     *      summary="GETFAVORITEQUESTION",
     *      description="Rota para retornar as questões favoritas de alguma prova" ,
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


    public function getFavoriteQuest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','token']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'token'=>'required',
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $testOrganization=new TestOrganization();
                $array['favoriteQuestions']=$testOrganization->getFavoriteQuest();
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }

    /**
     * @OA\POST(
     *      path="/delete_test",
     *      summary="DELETETEST",
     *      description="Rota para deletar a prova" ,
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
     *          name="idTest",
     *          description="Informe o id da prova",
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



    public function deleteTest(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','idTest','token','idQuestion','choosenAlternative']);
        
        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'idTest'=>['required','int'],
            'token'=>'required',
        ],[]);
            
        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $test=Test::where('id',$data['idTest'])->where('idUser',$data['idUser'])->first();
                if($test != null){
                    $testOrganization=new TestOrganization($test);
                    $array['error']=$testOrganization->deleteTest();
                }else{
                    $array['error']="Prova Inválida";
                }       
            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;   
    }
}
