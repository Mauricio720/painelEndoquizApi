<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserClientInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\ForgetPass;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller{

    public function __construct(){
        $this->middleware('auth:api',['except'=>['resetPassword','updatePassword']]);
    }

      /**
     * @OA\POST(
     *      path="/get_user",
     *      summary="GETUSER",
     *      description="Rota para pegar as informações do usuário",
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

    public function getUser(Request $request){
        $array=['error'=>''];
        $data=$request->only(['idUser','token']);

        $errors=Validator::make($data,[
            'idUser'=>['required','int'],
            'token'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $user=User::where('id',$data['idUser'])->first();
                $userBasicInfo=[
                    'id'=>$user->id,
                    'name'=>$user->name,
                    'lastname'=>$user->lastname,
                    'email'=>$user->email,
                    'nickname'=>$user->nickname,
                    'registerDate'=>$user->registerDate,
                    'registerTime'=>$user->registerTime,
                ];

                $userInfo=UserClientInfo::where('id',$user->idUserClientInfo)->first();
                
                $array['userBasicInfo']=$userBasicInfo;
                $array['userAddressInfo']=$userInfo;
                
            }else{
                $array['error']="Usuário Inválido";
            }
        }

        return $array;
    }

    
    /**
   * @OA\POST(
   *      path="/update_user",
   *      summary="UPDATEUSER",
   *      description="Rota para atualizar usuário",
   *      tags={"Endoquiz"},
   *      
   *      @OA\Parameter(
   *          name="idUser",
   *          description="Informe o id do usuário",
   *          required=true,
   *          in="query",
   *          @OA\Schema(
   *              type="string"
   *          )
   *      ),
   * 
   *     @OA\Parameter(
   *          name="token",
   *          description="Informe o token do usuário",
   *          required=true,
   *          in="query",
   *          @OA\Schema(
   *              type="string"
   *          )
   *      ),
   * 
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


  public function updateUser(Request $request){
      $array=['error'=>''];
      $data=$request->only(['idUser','name','nickname','lastname','email']);

      $errors=$this->validatorUpdateUser($data);
      
      if($errors->fails()){
          $array['error']=$errors->errors()->first();
      }else{
        if(Auth::guard('api')->user()->id==$data['idUser']){
            $user=User::where('id',Auth::guard('api')->user()->id)->first();
            $user->name=$data['name'];
            $user->lastname=$data['lastname'];
            $user->nickname=$request->filled('nickname')?$data['nickname']:'';
            $user->email=$data['email'];
            $user->save();
         }else{
             $array['error']='Usuário Inválido';
         }
      }

      return $array;
  }


  private function validatorUpdateUser($data){
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
        'email'=>['required','email','string','max:255',Rule::unique('users')->ignore(Auth::guard('api')->user()->id)],
        'password'=>['string','max:255','nullable'],
    ],[
        'email.unique'=>'Este e-mail já está sendo utilizado, por favor, escolha outro endereço de e-mail!'
    ],$correctNames);
}
    


    /**
     * @OA\POST(
     *      path="/update_address_info",
     *      summary="UPDATEUSERINFO",
     *      description="Rota para atualizar informações extras(o endereço) do usuário",
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
     *          name="street",
     *          description="Informe a rua do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\Parameter(
     *          name="number",
     *          description="Informe o numero do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\Parameter(
     *          name="neighboorhood",
     *          description="Informe o bairro do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *      @OA\Parameter(
     *          name="city",
     *          description="Informe a cidade do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),     
     *      
     *      @OA\Parameter(
     *          name="state",
     *          description="Informe o estado do usuário",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),       
     * 
     *      @OA\Parameter(
     *          name="cep",
     *          description="Informe o cep do usuário",
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
   

    public function updateUserInfo(Request $request){
        $array=['error'=>""];
        $data=$request->only(['idUser','street','number','neighboorhood','city','state','cep']);

        $errors=$this->validatorAddUserInfo($data);
        $user=User::where('id',$data['idUser'])->first();
        
        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{

            if($user->idUserClientInfo == null){
                $userInfo=new UserClientInfo(); 
                $userInfo->street=$data['street'];
                $userInfo->number=$data['number'];
                $userInfo->neighboorhood=$data['neighboorhood'];
                $userInfo->city=$data['city'];
                $userInfo->state=$data['state'];
                $userInfo->cep=$data['cep'];
                $userInfo->save();
    
                $user->idUserClientInfo=$userInfo->id;
                $user->save();
            }else{
                $userInfo=UserClientInfo::where('id',$user->idUserClientInfo)->first(); 
                $userInfo->street=$data['street'];
                $userInfo->number=$data['number'];
                $userInfo->neighboorhood=$data['neighboorhood'];
                $userInfo->city=$data['city'];
                $userInfo->state=$data['state'];
                $userInfo->cep=$data['cep'];
                $userInfo->save();
            }
        }
        
        return $array;
    }
    
    private function validatorAddUserInfo($data){
        $correctNames = [
            'street'=>'rua',
            'number'=>'numero',
            'neighboorhood'=>'bairro',
            'city'=>'cidade',
            'state'=>'estado',
        ];

        return Validator::make($data,[
            'street'=>['required','string','max:450'],
            'number'=>['required','string','max:10'],
            'neighboorhood'=>['required','string','max:255'],
            'city'=>['required','string','max:255'],
            'state'=>['required','string','max:255'],
            'cep'=>['required','string','max:9',],
        ],[],$correctNames);
    }


    /**
     * @OA\POST(
     *      path="/update_hospital_info",
     *      summary="UPDATEHOSPITALINFO",
     *      description="Rota para adicionar informações sobre o hospital",
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
     *          name="hospitalWork",
     *          description="Informe o hospital que trabalha",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       
     *      @OA\Parameter(
     *          name="residenceLocal",
     *          description="Informe o hospital que fez residência",
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

    public function updateHospitalInfo(Request $request){
        $array=['error'=>''];

        $data=$request->only('idUser','hospitalWork','residenceLocal');
        $errors=$this->validatorUpdateHospitalInfo($data);
        
        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $user=User::where('id',$data['idUser'])->first();
                if($user != null){
                    $idUserClientInfo=$user->idUserClientInfo;

                    $userClientInfo=UserClientInfo::where('id',$idUserClientInfo)->first();
                    $userClientInfo->hospitalWork=$data['hospitalWork'];
                    $userClientInfo->residenceLocal=$data['residenceLocal'];
                    $userClientInfo->save();
                }else{
                    $array['error']="Usuario inválido";
                }
            }else{
                $array['error']="Usuario inválido";
            }   
            
        }

        return $array;
    }

    private function validatorUpdateHospitalInfo($data){
        $correctNames=[
            'hospitalWork'=>'hospital que atua',
            'residenceLocal'=>'local de residência',
        ];
        
        return Validator::make($data,[
            'idUser'=>['required','int'],
            'hospitalWork'=>['string','max:255'],
            'residenceLocal'=>['string','max:255'],
        ],[],$correctNames);
    }


      /**
     * @OA\POST(
     *      path="/reset_pass",
     *      summary="RESETPASS",
     *      description="Rota para mandar o email e trocar a senha (Esqueci a senha)",
     *      tags={"Endoquiz"},
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


    public function resetPassword(Request $request){
        $array=['error'=>''];

        $data=$request->only(['email']);
        $errors=Validator::make($data,[
            'email'=>['required','email','max:255']
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            $user=User::where('email',$data['email'])->first();
            if($user != null){
                $hash=rand(0,9999);
                $user->remember_token=$hash;
                $user->save();

                $this->sendEmail($user);
                
            }else{
                $array['error']="Nenhum usuário com esse email foi encontrado!";
            }
        }

        return $array;
    }

    private function sendEmail(User $user){
        Mail::send(new ForgetPass($user));
    }

      /**
     * @OA\POST(
     *      path="/update_pass",
     *      summary="UPDATEPASS",
     *      description="Rota para alterar a senha com o código enviado para o email (Esqueci a senha)",
     *      tags={"Endoquiz"},
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
     *       @OA\Parameter(
     *          name="hash",
     *          description="Informe a hash enviado no email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="newPass",
     *          description="Informe a nova senha",
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

    public function updatePassword(Request $request){
        $array=['error'=>''];

        $data=$request->only(['email','hash','newPass']);
        $errors=Validator::make($data,[
            'email'=>['required','email','max:255'],
            'hash'=>['required'],
            'newPass'=>['required']
        ],[],[
            'newPass'=>'nova senha',
            'hash'=>'código'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            $userEmail=User::where('email',$data['email'])->first();
            if($userEmail != null){
                $userHash=User::where('email',$data['email'])->where('remember_token',$data['hash'])->first();
                if($userHash != null){
                   $userHash->password=Hash::make($data['newPass']);
                   $userHash->remember_token="";
                   $userHash->save();
                }else{
                    $array['error']="Código inválido ou não compativel com esse email!";
                }
            }else{
                $array['error']="Usuário não encontrado!";
            }
        }

        return $array;
    }
}
