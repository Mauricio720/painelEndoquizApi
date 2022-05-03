@component('mail::message')
<h1>Recuperação de senha</h1>
<p>
    Olá {{$user->name}}, você solicitou uma troca de senha pelo esqueci a senha. 
    Segue abaixo o codigo necessário para essa recuperação.
</p>

<h1>Código: {{$user->remember_token}}</h1>
@endcomponent