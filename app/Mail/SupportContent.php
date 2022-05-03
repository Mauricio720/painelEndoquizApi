<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SupportChat;
use App\Models\Support;
use App\Models\User;

class SupportContent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        
        $this->subject('Adição de conteúdo ao suporte!');
        $this->to(env('MAIL_USERNAME'),$user->name);
        
        $data['user']=$user;
        $data['support']=$support;
        $data['supportChat']=$this->supportChat;

        return $this->markdown('mail.supportContent',$data);
    }
}
