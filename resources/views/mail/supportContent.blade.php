@component('mail::message')
<h1> {{$user->name."  ".$user->lastname}} adicionou mais um conteúdo ao supporte</h1>
<h2>Email: {{$user->email}}</h2>
<h2>Assunto: {{$support->subject}}</h2>
<p>{{$supportChat->content}}</p>

@endcomponent