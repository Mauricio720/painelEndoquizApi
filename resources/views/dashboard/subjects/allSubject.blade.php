@extends('dashboard.base')
@extends('layouts.modal')
@section('css')
    <link rel="stylesheet" href="{{asset('css/painel/subject.min.css')}}">   
@endsection

<form action="" method="post" class="w-100" id="formSubject" style="display: none;">
    @csrf
    <input type="hidden" name="idSubject" id="idSubject">
    <input type="hidden" name="idSubtopic" id="idSubtopic">
    <div class="form-group w-100">
        <input class="form-control" required type="text" name="name" placeholder="Digite o nome da area">
    </div>
</form>


@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4>Todas as áreas</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-outline-success p-2" id="addBtnSubject" 
                            data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip">
                            <i class="cil-plus"><span> Adicionar Área</span> </i>
                        </button>

                        <a class="btn btn-outline-info p-2"  href="{{route('addQuestion')}}">
                            <i class="cil-plus"><span> Adicionar Questão</span></i>
                        </a>
                        
                    </div>
                </div>
            </div>
            <div class="card-header">
                <form class="formFilter" method="get">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <input class="form-control" placeholder="Assunto" type="text" 
                                    name="subject" value="{{$subject!=""?$subject:''}}">
                            </div>
                        </div>
                        
                        <div class="col-8">
                            
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

            <div class="card-body">
                <div class="subject__container">
                    @foreach ($allSubject as $key =>$subjectItem)
                        <div class="subject">
                            <div class="subject__item">
                                <div class="subject__slot d-flex justify-content-center mr-4">
                                    <h5 class="subjectName  p-2 w-100">{{$subjectItem['subject']->name}}</h5>
                                </div>

                                <div class="subject__slot d-flex flex-column align-items-start 
                                    justify-content-center">
                                    <div class="row m-0">
                                        <strong class="mr-1">Data Registrado:</strong>
                                        <span>{{date('d/m/Y',strtotime($subjectItem['subject']->registerDate))." - ".$subjectItem['subject']->registerTime}}</span>
                                    </div>
                                
                                    <div class="row m-0">
                                        <strong class="mr-1">Registrado por:</strong>
                                        <span>{{$subjectItem['user']->name}}</span>
                                    </div>
                                </div>
                                
                                <div class="subject__slot">
                                    <button class="btn btn-outline-dark m-1 btnSeeMore">
                                        Ver Assuntos
                                    </button>
                                    
                                    <button class="btn btn-outline-success m-1 btnEditSubject" idSubject={{$subjectItem['subject']->id}}
                                        data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip">
                                        Editar
                                    </button>
                            
                                    <a class="btn btn-outline-danger m-1 btnDelete" 
                                        href="{{route('deleteSubject',['idSubject'=>$subjectItem['subject']->id])}}"
                                        msg="Tem certeza que deseja deletar essa área?">
                                        Excluir
                                    </a>
                                </div>
                            </div>

                            <div class="card subjectContainer__subtopics">
                                <div class="card-header bg-dark "> 
                                    <div class="row">
                                        <div class="col-6">
                                            <h5 style="color: white">Assuntos</h5>
                                        </div>
                                        <div class="col-6 text-right">
                                            <button class="btn btn-light p-2 btnSubtopics" 
                                                idSubject="{{$subjectItem['subject']->id}}" data-toggle="modal" 
                                                data-target="#modalAcoes" data-toggle="tooltip">
                                                <i class="cil-plus"><span> Adicionar Assunto</span> </i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body border-dark overflow-auto">
                                        @foreach ($subjectItem['subtopics'] as $subtopic)
                                            <div class="row border-bottom p-1 m-2 subtopicRow">
                                                <div class="col-4 subtopicName">{{$subtopic->name}}</div>
                                                <div class="col-8 text-right">
                                                    <a class="btn btn-outline-info p-2 m-1" href="{{route('addQuestion',['subtopicChoose'=>$subtopic->id])}}">
                                                        <i class="cil-plus"><span> Adicionar Questão</span></i>
                                                    </a>

                                                    <button class="btn btn-outline-success p-1 m-1 btnEditSubtopic" idSubtopic="{{$subtopic->id}}" 
                                                        data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip">Editar</button>
                                                    
                                                    <a class="btn btn-outline-danger p-1 m-1 btnDelete" 
                                                        href="{{route('deleteSubtopic',['idSubtopic'=>$subtopic->id])}}"
                                                        msg="Tem certeza que deseja deletar esse assunto?">
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
                {{$allSubjectPaginate->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script>
        const SUBJECT_ADD="{{route('addSubject')}}";
        const SUBJECT_EDIT="{{route('editSubject')}}";
        const SUBTOPIC_ADD="{{route('addSubtopic')}}";
        const SUBTOPIC_EDIT="{{route('editSubtopic')}}";
    </script>
    <script src="{{ asset('js/painel/subject.min.js') }}"></script>
@endsection


