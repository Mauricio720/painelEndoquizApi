<?php

namespace App\Util;

use App\Mail\PremiumPlan;
use App\Models\PlanPayment;
use App\Models\User;
use App\Models\UserClientInfo;
use App\Util\Interfaces\PaymentInter;
use App\Util\Payer;
use App\Util\ItemPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class UserPlanPayment {
    private $loggedUser;
    private $planUser;

    public function __construct(){
        $this->loggedUser=Auth::guard('api')->user();
        if($this->loggedUser != null){
            $this->planUser=PlanPayment::where('idUser',$this->loggedUser->id)->first();
        
            if($this->planUser==null){
                $this->createPlan();
            }
        }
    }

    private function createPlan(){
        $planPayment=new PlanPayment();
        $planPayment->value=Config::get('premiumValue');
        $planPayment->date=date('Y-m-d');
        $planPayment->time=date('H:i:s');
        $planPayment->typePlan=1;
        $planPayment->idUser=$this->loggedUser->id;
        $planPayment->externalReference=md5(time().rand(0,99999));
        $planPayment->save();

        $this->planUser=$planPayment;
    }

    public function getTypePlan(){
        return $this->planUser->typePlan;
    }

    public function getId(){
        return $this->planUser->id;
    }

    public function getLinkMercadoPago(){
        return $this->planUser->linkMercadoPago;
    }

    public function setLinkMercadoPago($linkMercadoPago){
        $this->planUser->linkMercadoPago=$linkMercadoPago;
        $this->planUser->save();
    }

    public function doPaymentPremiumPlan(PaymentInter $externalPayment){
        
        $userInfo=UserClientInfo::where('id',$this->loggedUser->idUserClientInfo)->first();
        
        $payer=new Payer();
        $payer->setName($this->loggedUser->name);
        $payer->setLastName($this->loggedUser->lastname);
        $payer->setEmail($this->loggedUser->email);
        $payer->setStreet($userInfo!=null?$userInfo->street:'');
        $payer->setNumber($userInfo!=null?$userInfo->number:'');
        $payer->setCep($userInfo!=null?$userInfo->cep:'');

        $item=new ItemPayment();
        $item->setTitle("Plano Premium");
        $item->setDescription("Plano premium");
        $item->setUnitPrice($this->planUser->value);
        $item->setQuantity(1);

        $externalPayment->setPayerInfo($payer);
        $externalPayment->setPaymentItem($item);
        $externalPayment->setExternalReference($this->planUser->externalReference);

        $payment=new Payment($externalPayment);
        
        return $payment->doPayment();
    }

    public function verifyPlan($id){
        $plan=PlanPayment::where('id',$id)->first();
        if($plan != null){
            return true;
        }else{
            return false;
        }
    }

    public function updatedPlan($reference){
        $plan=PlanPayment::where('externalReference',$reference)->first();
        $plan->typePlan=2;
        $plan->save();
        $user=User::where('id',$plan->idUser)->first();
        Mail::send(new PremiumPlan($user));
    }

    public function backBasicPlan($reference){
        $plan=PlanPayment::where('externalReference',$reference)->first();
        $plan->typePlan=1;
        $plan->linkMercadoPago="";
        $plan->save();
    }

    public function verifyTotalQuestionAsk($totalQuestions,$totalQuestionsAnswered){
        $percentageQuestionAnswered=0;
        if($totalQuestions > 0 ){
            $percentageQuestionAnswered=($totalQuestionsAnswered*100)/$totalQuestions;
        }

        return floatVal(number_format($percentageQuestionAnswered,2));
    }

    public function updateQuestionsAnswered($totalQuestionsAnswered){
        $plan=PlanPayment::where('idUser',$this->loggedUser->id)->first();
        $plan->questionsAnswered=$totalQuestionsAnswered;
        $plan->save();
    }

}