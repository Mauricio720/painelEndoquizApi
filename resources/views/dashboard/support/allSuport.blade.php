@extends('dashboard.base')
@extends('layouts.modal')
@section('css')
    <link rel="stylesheet" href="{{asset('css/painel/support.min.css')}}">   
@endsection

@section('content')
    <form id="supportForm" action="{{route('answeredSupport')}}" method="post">
        @csrf
        <input type="hidden" name="idSupport"id="idSupport">
        <input type="hidden" name="content" id="answer">
    </form>

    <div class="card">
        <div class="card-header">
            <h5>Pedidos de Suporte</h5>    
        </div>       
        
        <div class="card-header">
            <form class="formFilter" method="get">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <input class="form-control" placeholder="Assunto" type="text" 
                                name="subject" value="{{$subject!=""?$subject:''}}">
                        </div>
                       
                        <div class="form-group">
                            <input class="form-control" type="date" 
                                name="registerDate" value="{{$registerDate!=""?$registerDate:''}}">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="status">
                                <option value="">Situação do suporte</option>
                                <option value="1">Não Resolvido</option>
                                <option value="2">Resolvido</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-8">
                        
                    </div>
                </div>
                
                <input class="btn btn-success w-25" type="submit" value="Filtrar">
            </form>
        </div>
        
        <div class="card-body">
            @foreach ($allSupport as $support)
            <div class="support {{$support['support']->status==1?'border-warning':'border-success'}}">
                <div class="support__item">
                    <div class="support__slot d-flex justify-content-center mr-4">
                        <h5 class="classifiedName p-2 w-100">{{$support['support']->subject}}</h5>
                    </div>

                    <div class="support__slot d-flex flex-column align-items-start 
                        justify-content-center">
                        <div class="row m-0">
                            <strong class="mr-1">Data Registrado:</strong>
                            <span>{{date('d/m/Y',strtotime($support['support']->registerDate))." - ".$support['support']->registerTime}}</span>
                        </div>
                    
                        <div class="row m-0">
                            <strong class="mr-1">Registrado por:</strong>
                            <span>{{$support['user']->name}}</span>
                        </div>
                    </div>
                    
                    <div class="support__slot">
                        <button class="btn btn-outline-dark m-1 btnSeeMore">
                            Ver Conteúdo
                        </button>
                    </div>
                </div>

                <div class="support_container mt-2">
                    <div class="form-group">
                       @foreach ($support['support_chat'] as $supportChat)
                       <div class="row m-0">
                            <div class="col-6">
                                @if($supportChat->typeUser==1)
                                    <div class="card">
                                        <div class="card-header bg-dark text-white">
                                            <div class="row">
                                                <div class="col-4">{{$supportChat->name}}</div>
                                                <div class="col-4">{{date('d/m/Y',strtotime($supportChat->registerDate))}}</div>
                                                <div class="col-4">{{$supportChat->registerTime}}</div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {{$supportChat->content}}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-6 justify-content-end">
                                @if($supportChat->typeUser==2)
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <div class="row">
                                                <div class="col-4">{{$supportChat->name}}</div>
                                                <div class="col-4">{{date('d/m/Y',strtotime($supportChat->registerDate))}}</div>
                                                <div class="col-4">{{$supportChat->registerTime}}</div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {{$supportChat->content}}

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                       @endforeach
                    </div>
                </div>
                <div class="form-group mt-2" style="height: 280px">
                    <label for="content">Responder</label>
                    <textarea required class="form-control h-50" name="answer">{{$support['support']->answer}}</textarea>

                    <div class="row p-2">
                        <div class="col-6">
                            <button idSupport="{{$support['support']->id}}" class="btn btn-success btnAsk">Responder</button>
                        </div>
                        <div class="col-6 text-right">
                            @if($support['support']->status==1)
                                <a href="{{route('supportResolved',['idSupport'=>$support['support']->id])}}">Não Resolvido</a>
                            @else 
                                <a href="{{route('supportResolved',['idSupport'=>$support['support']->id])}}">Resolvido</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card-footer">
            {{$allSupportPagination->links()}}
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/painel/support.min.js') }}"></script>
@endsection
