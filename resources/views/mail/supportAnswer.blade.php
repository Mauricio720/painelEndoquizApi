@component('mail::message')
<h1>Resposta ao seu pedido de suporte </h1>
<h2>Email: {{$user->email}}</h2>
<h2>Assunto: {{$support->subject}}</h2>
<p>{{$supportChat->content}}</p>

@endcomponent