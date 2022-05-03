@extends('dashboard.base')
@extends('layouts.modal')
@section('css')
    <link rel="stylesheet" href="{{asset('css/painel/classified.min.css')}}">   
@endsection

@section('content')
    
    <form action="" method="post" class="w-100 flex-column" id="formClassified" 
        enctype="multipart/form-data" style="display: none;">
        @csrf

        <input type="hidden" name="idClassifiedSubtopic" id="idClassifiedSubtopic">
        <input type="hidden" name="idClassifiedData" id="idClassifiedData">
        <input type="hidden" name="idTopic" id="idTopic" value="{{$topic->id}}">

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
            <input class="form-control" type="text" name="name" 
                id="name" placeholder="Nome" required>
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
                        <h5>{{$topic->name}} - Subtópicos</h5>
                    </div>
                    <div class="col-6 text-right">
                        <button id="btnSubtopic" class="btn btn-outline-success" data-toggle="modal" 
                        data-target="#modalAcoes" data-toggle="tooltip">
                        <i class="cil-plus"><span> Adicionar Subtópico</span> </i>
                    </button>
                    </div>
                </div>
            </div>

            <div class="card-header">
                <form class="formFilter" method="get">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <input class="form-control" type="text" name="classifiedSubtopic" 
                                    value="{{$classifiedSubtopic != ""?$classifiedSubtopic:''}}" placeholder="Digite o titulo do subtópico">
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
                    @foreach ($allClassifiedSubtopics as $classifiedSubtopic)
                    <div class="classified">
                        <div class="classified__item">
                            <div class="classified__slot d-flex justify-content-center mr-4">
                                <h5 class="classifiedSubtopicName p-2 w-100">{{$classifiedSubtopic['classifiedSubtopic']->name}}</h5>
                            </div>

                            <div class="classified__slot d-flex flex-column align-items-start 
                                justify-content-center">
                                <div class="row m-0">
                                    <strong class="mr-1">Data Registrado:</strong>
                                    <span>{{date('d/m/Y',strtotime($classifiedSubtopic['classifiedSubtopic']->registerDate))." - ".$classifiedSubtopic['classifiedSubtopic']->registerTime}}</span>
                                </div>
                            
                                <div class="row m-0">
                                    <strong class="mr-1">Registrado por:</strong>
                                    <span>{{$classifiedSubtopic['user']->name}}</span>
                                </div>
                            </div>
                            
                            <div class="classified__slot infoClassified">
                                <input type="hidden" class="imgSrc" value="{{$classifiedSubtopic['classifiedSubtopic']->image}}">
                                <input type="hidden" class="linkVideo" value="{{$classifiedSubtopic['classifiedSubtopic']->linkVideo}}">

                                <button class="btn btn-outline-dark m-1 btnSeeMore">
                                    Ver Conteúdo
                                </button>
                                
                                <button class="btn btn-outline-success m-1 btnEditClassifiedSubtopic" idClassifiedSubtopic="{{$classifiedSubtopic['classifiedSubtopic']->id}}"
                                    data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip">
                                    Editar
                                </button>
                        
                                <a class="btn btn-outline-danger m-1 btnDelete" 
                                    href="{{route('deleteSubtopicClassified',['idSubtopic'=>$classifiedSubtopic['classifiedSubtopic']->id])}}"
                                    msg="Tem certeza que deseja deletar esse subtópico?">
                                    Excluir
                                </a>
                            </div>
                        </div>
                        <div class="card classified__subtopics">
                            <div class="card-header bg-dark "> 
                                <div class="row">
                                    <div class="col-6">
                                        <h5 style="color: white">Conteúdo</h5>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button class="btn btn-light p-2 btnContent" 
                                            idClassifiedSubtopic="{{$classifiedSubtopic['classifiedSubtopic']->id}}" data-toggle="modal" 
                                            data-target="#modalAcoes" data-toggle="tooltip">
                                            <i class="cil-plus"><span> Adicionar Contéudo</span> </i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-dark overflow-auto">
                                @foreach ($classifiedSubtopic['classifiedData'] as $classifiedData)
                                    <div class="row border-bottom p-1 m-2 contentRow">
                                        <div class="col-4 contentName">{{$classifiedData->content}}</div>
                                        <div class="col-4">
                                            <div class="row m-0">
                                                <strong class="mr-1">Data Registrado:</strong>
                                                <span>{{date('d/m/Y',strtotime($classifiedData->registerDate))." - ".$classifiedData->registerTime}}</span>
                                            </div>
                                        </div>
                                        <div class="col-4 text-right infoClassified">
                                            <input type="hidden" class="imgSrc" value="{{$classifiedData->image}}">
                                            <input type="hidden" class="linkVideo" value="{{$classifiedData->linkVideo}}">
                                            
                                            <button class="btn btn-outline-success p-1 m-1 btnEditContent" idClassifiedData="{{$classifiedData->id}}" 
                                                data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip">Editar</button>
                                            
                                            <a class="btn btn-outline-danger p-1 m-1 btnDelete" 
                                                href="{{route('deleteContentClassified',['idContent'=>$classifiedData->id])}}"
                                                msg="Tem certeza que deseja deletar esse contéudo?">
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
        </div>
        <div class="card-footer">
            {{$allClassifiedSubtopicsPagination->links()}}
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        ADD_CLASSIFIED_SUBTOPIC_URL="{{route('addSubtopicClassified')}}";
        EDIT_CLASSIFIED_SUBTOPIC_URL="{{route('editSubtopicClassified')}}";
        ADD_SUBTOPIC_CLASSIFIED_CONTENT_URL="{{route('addContentClassified')}}";
        EDIT_SUBTOPIC_CLASSIFIED_CONTENT_URL="{{route('editContentClassified')}}";
    </script>
    
    <script src="{{ asset('js/painel/classified_content.min.js') }}"></script>

@endsection