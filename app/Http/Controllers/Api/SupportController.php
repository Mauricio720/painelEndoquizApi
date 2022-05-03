<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SupportContent;
use App\Models\Support;
use App\Models\SupportChat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Mail\SupportRequestMail;
use Illuminate\Support\Facades\Mail;


class SupportController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth:api');
    }

     /**
     * @OA\POST(
     *      path="/get_supports",
     *      summary="GETSUPORT",
     *      description="Rota para retorno dos suportes do usuário",
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
    
    public function getSupports(Request $request){
        $array=['error'=>''];

        $data=$request->only(['token','idUser']);
        
        $errors=Validator::make($data,[
            'token'=>'required',
            'idUser'=>'required',
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $allSupport=Support::where('idUser',Auth::guard('api')->user()->id)->get();;
                $allSupportComplete=[];

                foreach ($allSupport as $key => $support) {
                    $allSupportComplete[$key]['support']=$support;
                
                    $supportChat=SupportChat::where('idSupport',$support->id)
                        ->orderBy('registerDate','desc')->get();
                    $allSupportComplete[$key]['support_chat']=$supportChat;
                    
                }
                
                $array['allSupports']=$allSupportComplete;

            }else{
                $array['error']="Usuário inválido!";
            }
        }

        return $array;
    }

    
      /**
     * @OA\POST(
     *      path="/addSupport",
     *      summary="ADDSUPORT",
     *      description="Rota para adição de suporte para o usuário",
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
     * 
     *     @OA\Parameter(
     *          name="subject",
     *          description="Informe o assunto do suporte",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *       @OA\Parameter(
     *          name="content",
     *          description="Informe o conteúdo do suporte",
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
    
    public function addSupport(Request $request){
        $array=['error'=>''];

        $data=$request->only(['token','idUser','subject','content']);
        $errors=Validator::make($data,[
            'token'=>'required',
            'idUser'=>'required',
            'subject'=>['required','max:255'],
            'content'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            $support=new Support();
            $support->idUser=$data['idUser'];
            $support->subject=$data['subject'];
            $support->status=1;
            $support->registerDate=date('Y-m-d');
            $support->registerTime=date('H:i:s');
            $support->save();

            $supportChat=new SupportChat();
            $supportChat->content=$data['content'];
            $supportChat->typeUser=1;
            $supportChat->idSupport=$support->id;
            $supportChat->idUser=$data['idUser'];
            $supportChat->registerDate=date('Y-m-d');
            $supportChat->registerTime=date('H:i:s');
            $supportChat->save();
        
            $this->sendEmailRequest($supportChat);
        }

        return $array;
    }

    private function sendEmailRequest(SupportChat $supportChat){
        Mail::send(new SupportRequestMail($supportChat));
    }

     /**
     * @OA\POST(
     *      path="/add_support_content",
     *      summary="ADDSUPORTCONTENT",
     *      description="Rota para adição de conteudo para o suporte para o usuário",
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
     * 
     *     @OA\Parameter(
     *          name="idSupport",
     *          description="Informe o id do suporte",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      
     *   @OA\Parameter(
     *          name="content",
     *          description="Informe o conteúdo do suporte",
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
    

    public function addSupportContent(Request $request){
        $array=['error'=>''];

        $data=$request->only(['token','idUser','idSupport','subject','content']);
        $errors=Validator::make($data,[
            'token'=>'required',
            'idUser'=>'required',
            'idSupport'=>'required',
            'content'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if(Auth::guard('api')->user()->id==$data['idUser']){
                $supportChat=new SupportChat();
                $supportChat->idSupport=$data['idSupport'];
                $supportChat->content=$data['content'];
                $supportChat->typeUser=1;
                $supportChat->idUser=$data['idUser'];
                $supportChat->registerDate=date('Y-m-d');
                $supportChat->registerTime=date('H:i:s');
                $supportChat->save();

                $this->sendEmailContent($supportChat);
            }else{
                $array['error']="Usuário Inválido!";
            }
        }

        return $array;
    }

    private function sendEmailContent($supportChat){
        Mail::send(new SupportContent($supportChat));
    }

     /**
     * @OA\POST(
     *      path="/support_resolved",
     *      summary="RESOLVEDSUPORT",
     *      description="Rota para marcar como resolvido o supporte",
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
     * 
     *      @OA\Parameter(
     *          name="idSupport",
     *          description="Informe o id do suporte",
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

    public function supportResolved(Request $request){
        $array=['error'=>""];
        
        $data=$request->only(['token','idUser','idSupport']);

        $errors=Validator::make($data,[
            'token'=>'required',
            'idUser'=>'required',
            'idSupport'=>'required',
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            $support=Support::where('id',$data['idSupport'])->first();
            
            if($support != null){
                $support->status=!$support->status;
                $support->save();

            }
        }    
           
        return redirect()->route('allSupports');
    }
}
