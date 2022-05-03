@extends('dashboard.base')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"> 
                <div class="row">
                    <div class="col-6">
                        <h5>Usuários Aplicativo</h5>
                    </div>
                    <div class="col-6"></div>
                </div>
            </div>

            <div class="card-header">
                <form class="formFilter" method="get">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <input class="form-control" type="text" name="name" 
                                    value="{{$name != ""?$name:''}}" placeholder="Digite o nome do usuário">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="lastname" 
                                    value="{{$lastname != ""?$lastname:''}}" placeholder="Digite o sobrenome do usuário">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="email" name="email" 
                                    value="{{$email != ""?$email:''}}" placeholder="Digite o email do usuário">
                            </div>
                        </div>
                        <div class="col-8">
                            
                        </div>
                    </div>
                    
                    <input class="btn btn-success w-25" type="submit" value="Filtrar">
                </form>
            </div>

            <div class="card-body">
                <table class="table table-bordered bg-white">
                    <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Sobrenome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Data Registro</th>
                        <th scope="col">Hora Registro</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($allUsers as $user)
                            <tr class="{{$user->blocked?'table-danger':''}}">
                                <td>{{$user->name}}</td>
                                <td>{{$user->lastname}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{date('d/m/Y',strtotime($user->registerDate))}}</td>
                                <td>{{$user->registerTime}}</td>
                                <td>
                                    <a class="btn btn-outline-primary" href="{{route('userPaymentPlan',['idUser'=>$user->id])}}">
                                        <i class="cid-more mr-2"></i><span> Ver mais</span> 
                                    </a>
                                <a class="btn btn-outline-danger" href="{{route('blockedUserMobile',['idUser'=>$user->id])}}">
                                        <i class="mr-2"></i><span>{{$user->blocked?'Desbloquear':'Bloquear'}}</span> 
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(count($allUsers) == 0)
            <center><h5>Sem usuários registrados</h5></center>
            @endif
            <div class="card-footer">
                {{$allUsers->links()}}
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection
