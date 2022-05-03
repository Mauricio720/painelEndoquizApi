<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\PlanPayment;
use App\Models\User;
use App\Models\UserClientInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data=[];
        $data['allUsers']=User::where('type',1)->where('id','!=',Auth::user()->id)->paginate(10);
        $data['name']='';
        $data['lastname']='';
        $data['email']='';

        if($request->filled('name') || $request->filled('lastname') || $request->filled('email') ){
            $data=$request->only(['name','lastname','email']);
            $data['allUsers']=$this->filterUsers($data,1);
            $data['allUsersPagination']=$data['allUsers'];
        }

        return view('dashboard.users.allUsers',$data);
    }

    public function myProfile(Request $request){
        $data=[];
        $data=$request->only(['id','name','lastname','email','nickname','password']);


        if($request->filled(['id','name','nickname','lastname','email','password'])){
            $this->validatorUser($data,Auth::user()->id);
            $user=User::where('id',$data['id'])->first();
            $user->name=$data['name'];
            $user->lastname=$data['lastname'];
            $user->nickname=$request->filled('nickname')?$data['nickname']:'';
            $user->email=$data['email'];
            $user->password=Auth::user()->password==$data['password']?$data['password']:Hash::make($data['password']);
            $user->save();

            return redirect()->route('myProfile');
        }

        return view('dashboard.users.myProfile',$data);
    }

    public function add(Request $request){
        $data=$request->only(['name','lastname','email','nickname','password']);

        if($request->hasAny(['name','lastname','email','password'])){
            $this->validatorUser($data,Auth::user()->id);

            $user=new User();
            $user->name=$data['name'];
            $user->lastname=$data['lastname'];
            $user->nickname=$request->filled('nickname')?$data['nickname']:'';
            $user->email=$data['email'];
            $user->password=Hash::make($data['password']);
            $user->type=1;
            $user->registerDate=date('Y-m-d');
            $user->registerTime=date('H:i:s');
            $user->save();

            return redirect()->route('allUsers');
        }

        return view('dashboard.users.addUser',$data);
    }

    public function edit($id,Request $request){
        $data=$request->only(['id','name','lastname','email','nickname','password']);
        $data['user']=User::where('id',$id)->first();

        if($request->hasAny(['id','name','nickname','lastname','email','password'])){
            $this->validatorUser($data,$data['id']);
            
            $user=$data['user'];
            $user->name=$data['name'];
            $user->lastname=$data['lastname'];
            $user->nickname=$request->filled('nickname')?$data['nickname']:'';
            $user->email=$data['email'];
            $user->password=$data['user']->password==$data['password']?$data['password']:Hash::make($data['password']);
            $user->save();

            return redirect()->route('allUsers');
        }

        return view('dashboard.users.editUser',$data);
    }


    public function delete($id){
        $user=User::where('id',$id)->first();

        if($user != null){
            $user->delete();
        }
        
        return redirect()->route('allUsers');
    }

    private function validatorUser($data,$id){
        $correctNames=[
            'name'=>'nome',
            'lastname'=>'sobrenome',
            'nickname'=>'apelido',
            'password'=>'senha',
            'permission'=>'permissão'
        ];

        return Validator::make($data,[
            'name'=>['required','string','max:255'],
            'nickname'=>['string','max:255','nullable'],
            'lastname'=>['required','string','max:255'],
            'email'=>['required','email','string','max:255',Rule::unique('users')->ignore($id)],
            'password'=>['required','string','max:255'],
        
        ],[],$correctNames)->validate();
    }

    public function allUsersMobile(Request $request){
        $data=[];
        $data['allUsers']=User::where('type',2)->paginate(10);

        $data['name']='';
        $data['lastname']='';
        $data['email']='';

        if($request->filled('name') || $request->filled('lastname') || $request->filled('email') ){
            $data=$request->only(['name','lastname','email']);
            $data['allUsers']=$this->filterUsers($data,2);
        }

        return view('dashboard.users.usersMobile.allUsers',$data);
    }

    public function userPaymentPlan($id){
        $data=[];
        $data['user']=User::where('id',$id)->first();
        $data['userInfo']=UserClientInfo::where('id',$data['user']->idUserClientInfo)->first();
        $data['paymentInfo']=PlanPayment::where('idUser',$data['user']->id)->first();

        return view('dashboard.users.usersMobile.planUser',$data);
    }

    public function blockedUserMobile($id){
        $user=User::where('id',$id)->first();
        $user->blocked=!$user->blocked;
        $user->save();

        return redirect()->route('allUsersMobile');
    }

    private function filterUsers($data,$type){
        $painelUsers=User::query()->where('type',$type);
        
        if($data['name'] != ''){
            $painelUsers->where('name','LIKE','%'.$data['name'].'%');
        }
        if($data['lastname'] != ''){
            $painelUsers->where('lastname','LIKE','%'.$data['lastname'].'%');
        }

        if($data['email'] != ''){
            $painelUsers->where('email','LIKE','%'.$data['email'].'%');
        }

        return $painelUsers->where('id','!=',Auth::user()->id)->paginate(10);
    }
}
