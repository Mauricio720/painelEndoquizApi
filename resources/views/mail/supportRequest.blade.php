@component('mail::message')
<h1>Pedido de suporte - {{$user->name."  ".$user->lastname}} </h1>
<h2>Email: {{$user->email}}</h2>
<h2>Assunto: {{$support->subject}}</h2>
<p>{{$supportChat->content}}</p>

@endcomponent