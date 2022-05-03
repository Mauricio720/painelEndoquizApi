@extends('dashboard.base')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h5>Editar Usuário Painel</h5>
                    </div>
                    <div class="col-6 text-right">
                        <a class="btn btn-outline-success" href="{{route('allUsers')}}">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
            <form class="form" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$user->id}}">
                <div class="row m-2 mt-4">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input class="form-control" type="text" name="name" 
                                placeholder="Digite o nome do Usuário" 
                                value={{$user->name}}>
                        </div>

                        <div class="form-group">
                            <label for="lastname">Sobrenome</label>
                            <input class="form-control" type="text" name="lastname" 
                                placeholder="Digite o sobrenome do usuário"
                                value={{$user->lastname}}>
                        </div>

                        <div class="form-group">
                            <label for="nickname">Apelido</label>
                            <input class="form-control" type="text" name="nickname" 
                                placeholder="Digite o apelido do usuário (opcional)"
                                value={{$user->nickname}}>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email" 
                                placeholder="Digite o email do Usuário"
                                value={{$user->email}}>
                        </div>

                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input class="form-control" type="password" name="password" 
                                placeholder="Digite a senha do Usuário"
                                value={{$user->password}}>
                        </div>

                        <!--<div class="form-group">
                            <label for="permission">Permissão</label>
                            <select class="form-control" name="permission" id="">
                                <option value="">Escolha a permissão do Usuário</option>
                                <option value="1" {{$user->permissions==1?'selected':''}}>Adm</option>
                                <option value="2" {{$user->permissions==2?'selected':''}}>Secundário</option>
                            </select>
                        </div>-->
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-center">
                    <input class="btn btn-outline-success w-25" type="submit" value="Salvar">
                </div>
                
                @if($errors->any())
                    <div class="card-footer">
                        <div class="alert alert-danger alert-dismissible fade show text-center">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            {{$errors->first()}}
                        </div>
                    </div>
                @endif    
            </form>
        </div>
    </div>
@endsection    