<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Util\MercadoPagoPayment;
use App\Util\Payment;
use App\Util\UserPlanPayment;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notificationMercadoPago(Request $request){
        $reference=$request->input('id');
        $_GET['topic'];
        $content=file_get_contents('mplog.txt'); 
        

        $userPlan=new UserPlanPayment();
        $mercadoPago=new MercadoPagoPayment(Config::get('tokenPagamento'));
        $mercadoPago->setExternalReference($reference);
        
        $payment=new Payment($mercadoPago);
        $info=$payment->notificationPayment();
        $externalReference=$info['externalReference'];
        $status=$info['status'];

        $newContent="Reference: ".$reference." Topic: ".$_GET['topic']." data:".date('d/m/Y H:i:s')
            ." status: ".$status;
        $newContent=$content."\n".$newContent;
        file_put_contents('mplog.txt',$newContent);
        
        if($status=='approved'){
            $userPlan->updatedPlan($externalReference);
        }else{
            $userPlan->backBasicPlan($externalReference);
        }
    }
}
