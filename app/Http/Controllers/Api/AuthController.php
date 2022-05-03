<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Util\MercadoPagoPayment;
use Illuminate\Support\Facades\Config;
use App\Util\UserPlanPayment;
use App\Util\TestUser;

class AuthController extends Controller
{   

    private $loggedUser;

    public function __construct(){
        $this->middleware('auth:api',['except'=>['login','addUser','unauthorized']]);

    }

      /**
     * @OA\POST(
     *      path="/auth/authentication",
     *      summary="AUTHENTICATION",
     *      description="Rota para autenticação do usuário",
     *      tags={"Endoquiz"},
     *      @OA\Parameter(
     *          name="idUser",
     *          description="Informe o id do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="token",
     *          description="Informe o token do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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

    public function authentication(Request $request){
        $array=['error'=>''];

        $data=$request->only('idUser');
        $errors=Validator::make($data,[
                'idUser'=>['required']
        ],[],[]);

        $this->loggedUser=Auth::guard('api')->user();

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if($this->loggedUser->id==$data['idUser']){
                $userPlan=new UserPlanPayment();

                $linkMercadoPago=$userPlan->getLinkMercadoPago();

                if($linkMercadoPago==""){
                    $mercadoPago=new MercadoPagoPayment(Config::get('tokenPagamento'),$userPlan->getId());
                    $linkMercadoPago=$userPlan->doPaymentPremiumPlan($mercadoPago);
                    $userPlan->setLinkMercadoPago($linkMercadoPago);
                }

                if($userPlan->getTypePlan()==1){
                    $array['linkMercadoPago']=$linkMercadoPago;
                }
                
                $test=new TestUser();
                $totalQuestions=$test->getTotalDefaultQuestion();
                $totalQuestionsAnswered=$test->getTotalTestQuestionResolved();
                
                $array['totalQuestionsAnswered']=$totalQuestionsAnswered;
                $array['totalQuestions']=$totalQuestions;

                $userPlan->updateQuestionsAnswered($totalQuestionsAnswered);
                
                $array['questionsAnsweredPercentage']=$userPlan->verifyTotalQuestionAsk($totalQuestions,$totalQuestionsAnswered);
                
            }else{
                $array['error']="Usuário inválido";
            }
        }

        return $array;
    }

    
      /**
     * @OA\POST(
     *      path="/auth/add_user",
     *      summary="ADDUSER",
     *      description="Rota para adição do usuário",
     *      tags={"Endoquiz"},
     *      @OA\Parameter(
     *          name="name",
     *          description="Informe o nome do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="lastname",
     *          description="Informe o sobrenome do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\Parameter(
     *          name="email",
     *          description="Informe o email do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="password",
     *          description="Informe a senha do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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


    public function addUser(Request $request){
        $array=['error'=>''];
        $data=$request->only(['name','nickname','lastname','email','password']);

        $errors=$this->validatorAddUser($data);
        
        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            $user=new User();
            $user->name=$data['name'];
            $user->lastname=$data['lastname'];
            $user->nickname=$request->filled('nickname')?$data['nickname']:'';
            $user->email=$data['email'];
            $user->password=Hash::make($data['password']);
            $user->type=2;
            $user->registerDate=date('Y-m-d');
            $user->registerTime=date('H:i:s');
            $user->save();
        }

        return $array;
    }

    private function validatorAddUser($data){
        $correctNames=[
            'name'=>'nome',
            'lastname'=>'sobrenome',
            'nickname'=>'apelido',
            'password'=>'senha'
        ];
        
        return Validator::make($data,[
            'name'=>['required','string','max:255'],
            'nickname'=>['string','max:255','nullable'],
            'lastname'=>['required','string','max:255'],
            'email'=>['required','email','string','max:255','unique:users'],
            'password'=>['required','string','max:255'],
        ],[
            'email.unique'=>'Este e-mail já está sendo utilizado, por favor, escolha outro endereço de e-mail!'
          ],$correctNames);
    }

     /**
     * @OA\POST(
     *      path="/auth/login",
     *      tags={"Projects"},
     *      summary="LOGIN",
     *      description="Rota para realização de login",
     *      tags={"Endoquiz"},
     *      @OA\Parameter(
     *          name="email",
     *          description="Email para login",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="password",
     *          description="Senha para login",
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
    
    
    public function login(Request $request){
        $array=['error'=>''];
        $data=$request->only(['email','password']);
        $validation= Validator::make($data,[
                'email'=>['email','string','nullable'],
                'password'=>['string','nullable'],
            ]
        );

        if($validation->fails()){
            $array['error']=$validation->errors()->first();
        }else{
           $token=Auth::guard('api')->attempt([
                'email'=>$data['email'],
                'password'=>$data['password'],
                'type'=>2
            ]);

            if($token){
                $blocked=Auth::guard('api')->user()->blocked;
                
                if($blocked){
                    $array['error']="Usuário bloqueado!";
                }else{
                    $userPlan=new UserPlanPayment();
                    
                    if($userPlan->getLinkMercadoPago()==""){
                        $mercadoPago=new MercadoPagoPayment(Config::get('tokenPagamento'),$userPlan->getId());
                        $linkMercadoPago=$userPlan->doPaymentPremiumPlan($mercadoPago);
                        $userPlan->setLinkMercadoPago($linkMercadoPago);
                    }

                    if($userPlan->getTypePlan()==1){
                        $array['linkMercadoPago']=$userPlan->getLinkMercadoPago();
                    }
                    
                    $test=new TestUser();
                    $totalQuestions=$test->getTotalDefaultQuestion();
                    $totalQuestionsAnswered=$test->getTotalTestQuestionResolved();
                    
                    $array['totalQuestionsAnswered']=$totalQuestionsAnswered;
                    $array['totalQuestions']=$totalQuestions;

                    $userPlan->updateQuestionsAnswered($totalQuestionsAnswered);
                    
                    $array['questionsAnsweredPercentage']=$userPlan->verifyTotalQuestionAsk($totalQuestions,$totalQuestionsAnswered);
                    $array['typePlan']=$userPlan->getTypePlan();
                
                    $array['token']=$token;
                    $array['user']=User::where('id',Auth::guard('api')->user()->id)->first(['id','name','lastname','email','nickname','idUserClientInfo','blocked']);

                }
            }else{
                $array['error']="Email ou senha estão incorretos";
            }
        }

        return $array;
    }
    
      /**
     * @OA\POST(
     *      path="/auth/logout",
     *      summary="LOGOUT",
     *      description="Rota para realização do logout do usuário",
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
    

    public function logout(){
        Auth::guard('api')->logout();
        return ['error'=>""];
    }

     /**
     * @OA\POST(
     *      path="/auth/refresh",
     *      summary="REFRESH",
     *      description="Rota para atualizar e gerar um novo token para usuário",
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
    

    public function refresh(){
        $token=Auth::guard('api')->refresh();
        return ['error'=>"",'token'=>$token];
    }

    public function unauthorized(){
        return response()->json(['error'=>"Não autorizado"],401);
    }

}
