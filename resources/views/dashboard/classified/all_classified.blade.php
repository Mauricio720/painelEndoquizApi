@extends('dashboard.base')
@extends('layouts.modal')
@section('css')
    <link rel="stylesheet" href="{{asset('css/painel/classified.min.css')}}">   
@endsection

@section('content')
    
    <form action="" method="post" class="w-100 flex-column" enctype="multipart/form-data"
        id="formClassified" style="display: none;">
        @csrf
        <input type="hidden" name="idClassified" id="idClassified">
        <input type="hidden" name="idTopic" id="idTopic">

        <div class="card w-100">
            <div class="card-header bg-dark text-white">
                <h4>Adicione alguma imagem</h4>
            </div>
            <div class="card-body">
                <div class="uploadArea w-100">
                    <div class="uploadArea__title">Clique ou arraste a imagem</div>
                    <div class="uploadAreaDrop w-100">
                        <div class="uploadAreaDrop__img">
                            <img class="questionImg" src="{{asset('storage/general_icons/file.png')}}" width="100%">
                        </div>
                        <div class="uploadAreaDrop__descriptionFile"></div>
                    </div>
                    <input name="imageClassified" type="file" class="uploadInput"/>
                </div>
            </div>
        </div>

        <div class="form-group w-100">
            <input class="form-control" type="text" name="name" id="name" placeholder="Nome" required>
        </div>
        <div class="form-group w-100">
            <input class="form-control" type="text" name="linkVideo" id="linkVideo" placeholder="Link Video" required>
        </div>
    </form>


    <div class="container">
        <div class="card">
            <div class="card-header"> 
                <div class="row">
                    <div class="col-6">
                        <h5>Classificações</h5>
                    </div>
                    <div class="col-6 text-right">
                        <button id="btnClassified" class="btn btn-outline-success" data-toggle="modal" 
                        data-target="#modalAcoes" data-toggle="tooltip">
                        <i class="cil-plus"><span> Adicionar Classificação</span> </i>
                    </button>
                    </div>
                </div>
            </div>

            <div class="card-header">
                <form class="formFilter" method="get">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <input class="form-control" type="text" name="classified" 
                                    value="{{$classified != ""?$classified:''}}" placeholder="Digite o titulo do classificado">
                            </div>
                         </div>
                        <div class="col-6">
                            
                        </div>
                    </div>
                    
                    <input class="btn btn-success w-25" type="submit" value="Filtrar">
                </form>
            </div>

            @if($errors->any())
                <div class="card-header">
                    <div class="alert alert-danger alert-dismissible fade show text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{$errors->first()}}
                    </div>
                </div>
            @endif  

        <div class="card">
            <div class="card-body overflow-auto">
                @foreach ($allClassified as $classified)
                <div class="classified">
                    <div class="classified__item">
                        <div class="classified__slot d-flex justify-content-center mr-4">
                            <h5 class="classifiedName p-2 w-100">{{$classified['classified']->name}}</h5>
                        </div>

                        <div class="classified__slot d-flex flex-column align-items-start 
                            justify-content-center">
                            <div class="row m-0">
                                <strong class="mr-1">Data Registrado:</strong>
                                <span>{{date('d/m/Y',strtotime($classified['classified']->registerDate))." - ".$classified['classified']->registerTime}}</span>
                            </div>
                        
                            <div class="row m-0">
                                <strong class="mr-1">Registrado por:</strong>
                                <span>{{$classified['user']->name}}</span>
                            </div>
                        </div>
                        
                        <div class="classified__slot infoClassified">
                            <input type="hidden" class="imgSrc" value="{{$classified['classified']->image}}">
                            <input type="hidden" class="linkVideo" value="{{$classified['classified']->linkVideo}}">
                            
                            <button class="btn btn-outline-dark m-1 btnSeeMore">
                                Ver Tópicos
                            </button>
                            
                            <button class="btn btn-outline-success m-1 btnEditClassified" idClassified="{{$classified['classified']->id}}"
                                data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip">
                                Editar
                            </button>
                    
                            <a class="btn btn-outline-danger m-1 btnDelete" 
                                href="{{route('deleteClassified',['idClassified'=>$classified['classified']->id])}}"
                                msg="Tem certeza que deseja deletar esse classificado?">
                                Excluir
                            </a>
                        </div>
                    </div>
                    <div class="card classified__subtopics">
                        <div class="card-header bg-dark "> 
                            <div class="row">
                                <div class="col-6">
                                    <h5 style="color: white">Tópicos</h5>
                                </div>
                                <div class="col-6 text-right">
                                    <button class="btn btn-light p-2 btnTopics" 
                                        idClassified="{{$classified['classified']->id}}" data-toggle="modal" 
                                        data-target="#modalAcoes" data-toggle="tooltip">
                                        <i class="cil-plus"><span> Adicionar Tópico</span> </i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-dark overflow-auto">
                            @foreach ($classified['classifiedTopics'] as $topic)
                                <div class="row border-bottom p-1 m-2 topicRow">
                                    <div class="col-4 topicName">{{$topic->name}}</div>
                                    <div class="col-4">
                                        <div class="row m-0">
                                            <strong class="mr-1">Data Registrado:</strong>
                                            <span>{{date('d/m/Y',strtotime($topic->registerDate))." - ".$topic->registerTime}}</span>
                                        </div>
                                    </div>
                                    <div class="col-4 text-right infoClassified">
                                        <input type="hidden" class="imgSrc" value="{{$topic->image}}">
                                        <input type="hidden" class="linkVideo" value="{{$topic->linkVideo}}">
                                        
                                        <a class="btn btn-outline-info p-2 m-1" href="{{route('classifiedContent',['idTopic'=>$topic->id])}}">
                                            <i class="cil-plus"><span> Ver Mais</span></i>
                                        </a>

                                        <button class="btn btn-outline-success p-1 m-1 btnEditTopic" idTopic="{{$topic->id}}" 
                                            data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip">Editar</button>
                                        
                                        <a class="btn btn-outline-danger p-1 m-1 btnDelete" 
                                            href="{{route('deleteClassifiedTopic',['idTopic'=>$topic->id])}}"
                                            msg="Tem certeza que deseja deletar esse tópico?">
                                            Excluir
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card-footer">
            {{$allClassifiedPaginate->links()}}
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        ADD_CLASSIFIED_URL="{{route('addClassified')}}";
        EDIT_CLASSIFIED_URL="{{route('editClassified')}}";
        ADD_TOPIC_CLASSIFIED_URL="{{route('addClassifiedTopic')}}";
        EDIT_TOPIC_CLASSIFIED_URL="{{route('editClassifiedTopic')}}";
    </script>
    <script src="{{ asset('js/painel/classified.min.js') }}"></script>
@endsection