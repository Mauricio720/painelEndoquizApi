@extends('dashboard.base')
@extends('layouts.modal')

@section('css')
    <link rel="stylesheet" href="{{asset('css/painel/questions.min.css')}}">   
@endsection

<form action="" method="post" class="w-100" id="formQuestion" style="display: none;">
    @csrf
    <input type="hidden" name="idDefaultQuestion" id="idDefaultQuestion">
    <input type="hidden" name="idAlternative" id="idAlternative">

    <div class="form-group w-100">
        <textarea class="form-control" required type="text" name="content" rows="8" placeholder="Digite o nome do assunto">
        </textarea>
    </div>
</form>


@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h5>Questões</h5>
                    </div>
                    <div class="col-6 text-right">
                        <a href="{{route('addQuestionView')}}" class="btn btn-outline-success p-2">
                            <i class="cil-plus"><span> Adicionar Questão</span> </i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-header">
                <form class="formFilter" method="get">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <input class="form-control" type="text" name="question" 
                                    value="{{$question != ""?$question:''}}" placeholder="Digite a questão">
                            </div>
                            <div class="card" style="max-height: 180px">
                                <div class="card-header bg-dark text-white">
                                    Filtrar por assuntos:
                                </div>
                                <div class="card-body d-flex flex-column overflow-auto">
                                    @foreach ($allSubtopic as $subtopic)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{$subtopic->id}}" 
                                                name="checkSubtopic[]" {{in_array($subtopic->id,$subtopics)?'checked':''}}>
                                            <label class="form-check-label">
                                                {{$subtopic->name}}
                                            </label>
                                      </div>
                                    @endforeach
                                </div>
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
            <div class="card-body">
                @foreach ($default_questions as $question)
                    <div class="card default__question mt-4">
                       <div class="defaultQuestion__header bg-dark">
                           <h4>{{implode(', ',$question['default_question_subtopic'])}}</h4>
                        </div>
                       <div class="defaultQuestion__container mt-4">
                            <div class="defaultQuestion__slot defaultQuestion__questionText p-2">
                                {{$question['default_question']->question}}
                            </div>
                            <div class="defaultQuestion__slot d-flex justify-content-end align-items-center">
                                <button class="btn btn-outline-dark p-1 m-1 btnSeeMore">Ver Detalhes</button>
                                <a href="{{route('editQuestionView',['idDefaultQuestion'=>$question['default_question']->id])}}" class="btn btn-outline-success p-1 m-1">Editar Detalhes</a>
                                <button class="btn btn-outline-info p-1 m-1 btnEditQuestion" data-toggle="modal" 
                                    data-target="#modalAcoes" data-toggle="tooltip" idQuestion="{{$question['default_question']->id}}">
                                    Editar Questão
                                </button>
                                <a href="{{route('deleteDefaultQuestion',['idDefaultQuestion'=>$question['default_question']->id])}}" class="btn btn-outline-danger p-1 m-1 btnDelete" 
                                    msg="Tem certeza que deseja deletar essa questão?">
                                    Excluir
                                </a>
                            </div>
                        </div>
                        
                        @if($question['default_question']->image != "" || $question['default_question']->video != "")
                            <div class="card mt-4 cardImageVideo">
                                <div class="row">
                                    <div class="col-6">
                                        @if ($question['default_question']->image != "")
                                            <div class="card cardImage">
                                                    <img src="{{asset('storage/questionsImages/'.$question['default_question']->image)}}" width="100%">
                                            </div>
                                        @endif  
                                    </div>
                                    <div class="col-6">
                                        @if ($question['default_question']->video != "")
                                            <div class="card">
                                                <div class="card-body">
                                                    <object width="425" height="350">
                                                        <param name="movie" value="{{$question['default_question']->video}}" />
                                                        <embed src="{{$question['default_question']->video}}" type="application/x-shockwave-flash" width="425" height="350" />
                                                       
                                                    </object>                                                
                                                </div>
                                            </div>
                                        @endif   
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card mt-2 cardAlternatives">
                            <div class="card-header bg-dark "> 
                                <div class="row">
                                    <div class="col-6">
                                        <h5 style="color: white">Alternativas</h5>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end">
                                        <button class="btn btn-success p-1 m-1 btnReveal">Revelar alternativas certas</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-dark overflow-auto">
                                   @foreach ($question['default_alternatives'] as $key => $alternative)
                                    <div class="row defaultAlternative__row {{$key>0?'mt-4':''}}" isCorrect={{$alternative->is_correct}}>
                                        <div class="col-10 alternativeText">
                                            <h6>{{$alternative->description}}</h6>
                                        </div>
                                        <div class="col-2 text-right">
                                            <button class="btn btn-outline-success p-1 m-1 btnEditAlternative" idAlternative="{{$alternative->id}}" 
                                                data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip">Editar</button>
                                        </div>
                                    </div>
                                   @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="card-footer">
                    {{$default_questions_pagination->links()}}
                </div>
            </div>
        </div>  

    </div>
@endsection

@section('javascript')
    <script>
        UPDATE_QUESTION_URL="{{route('updateDefaultQuestionText')}}";
        UPDATE_ALTERNATIVE_URL="{{route('updateDefautAlternativeQuestionText')}}";
    </script>
    <script src="{{ asset('js/painel/all_questions.min.js') }}"></script>
@endsection