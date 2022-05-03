<?php

namespace App\Mail;

use App\Models\Support;
use App\Models\SupportChat;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SupportRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $supportChat;
    
    public function __construct(SupportChat $supportChat)
    {
        $this->supportChat=$supportChat;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data=[];
        $user=User::where('id',$this->supportChat->idUser)->first();
        $support=Support::where('id',$this->supportChat->idSupport)->first();
        
        $this->subject('Pedido de Suporte!');
        $this->to(env('MAIL_USERNAME'),$user->name);
        
        $data['user']=$user;
        $data['support']=$support;
        $data['supportChat']=$this->supportChat;

        return $this->markdown('mail.supportRequest',$data);
    }
}
