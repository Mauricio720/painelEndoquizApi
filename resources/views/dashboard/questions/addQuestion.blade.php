@extends('dashboard.base')
@section('css')
    <link rel="stylesheet" href="{{asset('css/painel/subject.min.css')}}">   
    <link rel="stylesheet" href="{{asset('css/painel/questions.min.css')}}">   
@endsection


@section('content')
    <form action="{{route('addQuestion')}}" id="questionForm" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="question">
        <input type="hidden" name="subject_subtopics">
        <input type="hidden" name="alternatives">
        <input type="hidden" name="videoLink">
        <input type="file" name="imageFile" id="imageFile" style="display: none;">
        <input type="hidden" name="justifyContent">
        <input type="file" name="imageFileJustify" id="imageFileJustify" style="display: none;">
        <input type="hidden" name="videoLinkJustify">
        <input type="hidden" name="premiumQuestion">
    </form>

    <div class="alternative__row">
        <div class="d-flex w-100">
            <div class="alternativeRow__slot alternativeRow__slot--input">
                <textarea type="text" class="alternativeRow__input" 
                    placeholder="Digite a alternativa..."></textarea>
            </div>
            <div class="alternativeRow__slot justify-content-end align-items-center mr-3">
                <div class="alternativeRow__btn" title="alternativa correta" style="background-color: green">
                    <img src="{{asset('storage/general_icons/checkbox.png')}}" width="100%">
                </div>
                <div class="alternativeRow__btn d-flex justify-content-center align-center" title="alternativa incorreta">X</div>
                <div class="alternativeRow__btn d-flex justify-content-center align-items-center"  
                    title="deletar alternativa" style="background-color: red">
                    <img src="{{asset('storage/general_icons/delete.png')}}" width="18" height="18">
                </div>
            </div>
        </div>
        <div class="alternative__row--alert d-none">Texto da alternativa é obrigatório!</div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h4>Adicionar Questão</h4>
            </div>
            <div class="card-body">
                <div class="card" id="cardQuestion">
                    <div class="card-header bg-dark text-white">
                        <h4>Escreva a questão:</h4>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control questionInput"  cols="30" rows="10"></textarea>
                        <div class="alert alert-danger alert-dismissible fade show d-none mt-2" id="alertQuestion" role="alert">
                            <center>Conteúdo da questão é obrigatório.</center>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                  
                </div> <br><br>

                <div class="card" id="cardSubject">
                    <div class="card-header bg-dark text-white">
                        <h4>Selecione os assuntos</h4>
                    </div>
                    <div class="card-body">
                        <div class="subject__container">
                            @foreach ($allSubject as $key =>$subjectItem)
                                <div class="subject">
                                    <div class="subject__item">
                                        <div class="subject__slot d-flex justify-content-center mr-4">
                                            <h5 class="subjectName  p-2 w-100">{{$subjectItem['subject']->name}}</h5>
                                        </div>

                                        <div class="subject__slot justify-content-end">
                                            <button class="btn btn-outline-info m-1 btnSeeMore">
                                                Ver Assuntos
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body overflow-auto">
                                        @foreach ($subjectItem['subtopics'] as $subtopic)
                                            <div class="row border-bottom p-1 m-2 subtopicRow">
                                                <div class="col-2">
                                                    <input type="checkbox" class="subtopicCheck" name="subtopicsCheck" 
                                                        id="{{$subtopic->id}}" {{$subtopicChoose==$subtopic->id?'checked':''}}>
                                                </div>
                                                <div class="col-10 subtopicName">{{$subtopic->name}}</div>
                                                
                                            </div>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="alert alert-danger alert-dismissible fade show d-none mt-2" id="alertSubject" role="alert">
                            <center>Selecione pelo menos um assunto.</center>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div><br><br>

                <div class="card" id="cardAlternative">
                    <div class="card-header bg-dark text-white">
                        <div class="row">
                            <div class="col-6">
                                <h4>Adicione as alternativas:</h4>
                            </div>    

                            <div class="col-6 text-right">
                                <button class="btn btn-success p-2" id="btnAddAlternative">
                                    <i class="cil-plus"><span> Adicionar</span> </i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body alternativeContainer">
                        
                    </div>

                    <div class="alert alert-danger alert-dismissible fade show d-none mt-2" id="alertAlternativesIsCorrect" role="alert">
                        <center>Pelo menos uma alternativa tem que estar correta.</center>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="alert alert-danger alert-dismissible fade show d-none mt-2" id="alertAlternatives" role="alert">
                        <center>Adicione pelo menos duas alternativas.</center>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div><br><br>

                <div class="card" id="cardImage">
                    <div class="card-header bg-dark text-white">
                        <h4>Adicione alguma imagem:</h4>
                    </div>
                    <div class="card-body">
                        <div class="uploadArea">
                            <div class="uploadArea__title">Clique ou arraste a imagem</div>
                            <div class="uploadAreaDrop">
                                <div class="uploadAreaDrop__img">
                                    <img class="questionImg" src="{{asset('storage/general_icons/file.png')}}" width="100%">
                                </div>
                                <div class="uploadAreaDrop__descriptionFile"></div>
                            </div>
                            <input name="questionImgFile" id="questionImgFile" type="file" class="uploadInput"/>
                        </div>
                    </div>
                </div><br><br>
           
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h4>Adicione algum link de video:</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input class="form-control" type="link" id="videoLink" placeholder="Digite o link do video">
                        </div>
                    </div>
                </div><br><br>
                
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h4>Justificativa</h4>
                    </div>
                    <div class="card-body">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h4>Escreva a justificativa:</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <textarea id="justifyContent" class="form-control" name="justifyContent" cols="30" rows="8"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h4>Adicione alguma imagem para justificativa:</h4>
                            </div>
                            <div class="card-body">
                                <div class="uploadArea">
                                    <div class="uploadArea__title">Clique ou arraste a imagem</div>
                                    <div class="uploadAreaDrop">
                                        <div class="uploadAreaDrop__img">
                                            <img class="questionImg" src="{{asset('storage/general_icons/file.png')}}" width="100%">
                                        </div>
                                        <div class="uploadAreaDrop__descriptionFile"></div>
                                    </div>
                                    <input name="justifyFile" id="justifyFile" type="file" class="uploadInput"/>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h4>Adicione algum link de video para justificativa:</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <input class="form-control" type="link" id="videoLinkJustify" placeholder="Digite o link do video">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h4>Questão Gratuita?</h4>
                    </div>
                    <div class="card-body d-flex">
                        <div class="form-group m-2">
                           <input type="radio" name="premium" value="0">
                           <label>Sim</label>
                        </div>

                        <div class="form-group m-2">
                            <input type="radio" name="premium" checked value="1">
                            <label>Não</label>
                         </div>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <button class="btn btn-outline-success w-25" id="btnAddQuestion">Adicionar</button>
                </div>
            </div>
        </div>
    </div> 

   
    <button class="btn btn-success d-none" id="goToAddBtn">Ir para o botão adicionar</button>
@endsection

@section('javascript')
    <script src="{{ asset('js/painel/questions.min.js') }}"></script>
@endsection
