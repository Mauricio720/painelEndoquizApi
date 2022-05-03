@extends('dashboard.base')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5>Adicionar Usuário Painel</h5>
            </div>
            <form class="form" method="post">
                @csrf
                <div class="row m-2 mt-4">
                    <div class="col-6">
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" 
                                placeholder="Digite o nome do Usuário" 
                                value={{old('name')}}>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="text" name="lastname" 
                                placeholder="Digite o sobrenome do usuário"
                                value={{old('lastname')}}>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="text" name="nickname" 
                                placeholder="Digite o apelido do usuário (opcional)"
                                value={{old('nickname')}}>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <input class="form-control" type="email" name="email" 
                                placeholder="Digite o email do Usuário"
                                value={{old('email')}}>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="password" name="password" placeholder="Digite a senha do Usuário">
                        </div>

                        <!--
                        <div class="form-group">
                            <select class="form-control" name="permission" id="">
                                <option value="">Escolha a permissão do Usuário</option>
                                <option value="1" {{old('permission')==1?'selected':''}}>Adm</option>
                                <option value="2" {{old('permission')==2?'selected':''}}>Secundário</option>
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