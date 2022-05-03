<?php

namespace App\Mail;

use App\Models\SupportChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Support;
use App\Models\User;

class SupportAnswer extends Mailable
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
        $support=Support::where('id',$this->supportChat->idSupport)->first();
        $user=User::where('id',$support->idUser)->first();

        
        $this->subject('Resposta ao seu pedido de suporte!');
        $this->to($user->email,$user->name);
        
        $data['user']=$user;
        $data['support']=$support;
        $data['supportChat']=$this->supportChat;

        return $this->markdown('mail.supportAnswer',$data);
    }
}
