<?php

namespace App\Http\Controllers\Painel;

use App\Models\Support;
use App\Http\Controllers\Controller;
use App\Models\SupportChat;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\SupportAnswer;
use Illuminate\Support\Facades\Mail;


class SupportController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data=[];
        $data['allSupport']=Support::paginate(10);
        $data['allSupportPagination']=$data['allSupport'];
        $data['subject']="";
        $data['content']="";
        $data['answer']="";
        $data['registerDate']="";
        $data['status']="";
        
        if($request->has('subject') || $request->has('registerDate') || $request->has('status')){
            $subject=$request->input('subject');
            $registerDate=$request->input('registerDate');
            $status=$request->input('status');
            $support=Support::query();

            if($request->filled('subject')){
                $support->where('subject','LIKE','%'.$subject."%");
            }

            if($request->filled('registerDate')){
                $support->where('registerDate',$registerDate);
            }

            if($request->filled('status')){
                $support->where('status',$status);
            }

            $data['allSupport']=$support->paginate(10);
            $data['allSupportPagination']=$data['allSupport'];

            $data['subject']=$subject;
            $data['registerDate']=$registerDate;
            $data['status']=$status;
        }
        
        $allSupportComplete=[];
        foreach ($data['allSupport'] as $key => $support) {
            $allSupportComplete[$key]['support']=$support;
            
            $supportChat=SupportChat::where('idSupport',$support->id)
            ->join('users','users.id','support_chats.idUser')
            ->get(['users.*','support_chats.*']);
            $allSupportComplete[$key]['support_chat']=$supportChat;
            
            $allSupportComplete[$key]['user']=User::where('id',$support->idUser)->first();
        }

        $data['allSupport']=$allSupportComplete;
  
        return view('dashboard.support.allSuport',$data);
    }

    public function addSupportContent(Request $request){
        $array=['error'=>''];

        $data=$request->only(['idSupport','content']);
        $errors=Validator::make($data,[
            'idSupport'=>'required',
            'content'=>'required'
        ]);

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            $supportChat=new SupportChat();
            $supportChat->idSupport=$data['idSupport'];
            $supportChat->content=$data['content'];
            $supportChat->typeUser=2;
            $supportChat->idUser=Auth::user()->id;
            $supportChat->registerDate=date('Y-m-d');
            $supportChat->registerTime=date('H:i:s');
            $supportChat->save();

            $this->sendEmailSupportAnswer($supportChat);
        }

        return redirect()->route('allSupports');
    }

    private function sendEmailSupportAnswer(SupportChat $supportChat){
        Mail::send(new SupportAnswer($supportChat));

    }

    public function supportResolved($idSupport){
        $support=Support::where('id',$idSupport)->first();
        
        if($support != null){
            $support->status=!$support->status;
            $support->save();

            $this->sendEmail($support);
        }

        return redirect()->route('allSupports');
    }

    private function sendEmail($support){
        
    }
}
