@extends('dashboard.base')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"> 
                <div class="row">
                    <div class="col-6">
                        <h5>Usuários Painel</h5>
                    </div>
                    <div class="col-6 text-right">
                        <a class="btn btn-outline-success" href="{{route('addUser')}}">
                            <i class="cil-plus"><span> Adicionar</span> </i>
                        </a>
                    </div>
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

            <table class="table table-bordered">
                <thead class="table-bordered ">
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
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->lastname}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{date('d/m/Y',strtotime($user->registerDate))}}</td>
                            <td>{{$user->registerTime}}</td>
                            <td>
                                <a class="btn btn-outline-success" href="{{route('editUser',['idUser'=>$user->id])}}">
                                    <i class="cil-pencil mr-2"><span> Editar</span> </i>
                                </a>

                                <a class="btn btn-outline-danger btnDelete" href="{{route('deleteUser',['idUser'=>$user->id])}}"
                                    msg="Tem certeza que quer deletar esse usuário?">
                                    <i class="cil-trash mr-2"></i>Deletar</span> 
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
