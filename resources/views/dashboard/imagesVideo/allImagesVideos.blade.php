@extends('dashboard.base')
@extends('layouts.modal')

@section('content')

<form method="post" class="w-100 flex-column" id="formImagesVideos" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="hidden" name="id" id="id">

    <div class="form-group w-100">
        <div class="uploadArea w-100">
            <div class="uploadArea__title">Clique ou arraste a imagem</div>
            <div class="uploadAreaDrop">
                <div class="uploadAreaDrop__img">
                    <img class="questionImg" src="{{asset('storage/general_icons/file.png')}}" width="100%">
                </div>
                <div class="uploadAreaDrop__descriptionFile"></div>
            </div>
            <input name="imgFile" id="imgFile" type="file" class="uploadInput"/>
        </div>
    </div>

    <div class="form-group w-100">
        <input type="url" class="form-control" name="linkVideo" placeholder="Digite o link do video">
    </div>
    
    <div class="form-group w-100">
        <input class="form-control" type="text" name="title" id="title" 
            placeholder="Digite o titulo" required>
    </div>

    <div class="form-group w-100">
        <textarea class="form-control" name="description" required rows="5" placeholder="Digite a descrição" ></textarea>
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
                    <div class="btn btn-outline-success p-2" data-toggle="modal" id="btnAddVideoImage"
                        data-target="#modalAcoes" data-toggle="tooltip">
                        <i class="cil-plus"><span> Adicionar</span> </i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-header">
            <form class="formFilter" method="get">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <input class="form-control" type="text" name="title" placeholder="Digite o Titulo">
                        </div>
                        
                        <div class="form-group">
                            <input class="form-control" type="text" name="description" placeholder="Digite a Descrição">
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
            @foreach ($allImagesVideo as $imageVideo)
                <div class="card border-dark">
                    <div class="card-header bg-dark text-white">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="imageVideoTitle">{{$imageVideo->title}}</h3>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <button class="btn btn-info p-1 m-1 btnEditImageVideo" data-toggle="modal" 
                                    data-target="#modalAcoes" data-toggle="tooltip" idImageVideo="{{$imageVideo->id}}">
                                    Editar
                                </button>
                                <a href="{{route('deleteImageVideo',['id'=>$imageVideo->id])}}" 
                                    class="btn btn-danger p-1 m-1 btnDelete" 
                                    msg="Tem certeza que deseja deletar esse card image/video?">
                                    Excluir
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if ($imageVideo->image != "")
                                <div class="col-{{$imageVideo->linkVideo != ""?'6':'12'}}">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-center">
                                            <img class="img" src="{{asset('storage/imagesVideos/'.$imageVideo->image)}}" 
                                                width="{{$imageVideo->linkVideo != ""?'75%':'50%'}}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($imageVideo->linkVideo != "")
                                <div class="col-{{$imageVideo->image != ""?'6':'12'}} linkVideo">
                                
                                </div>
                            @endif
                            </div>
                        </div>
                        <div class="card-footer bg-dark text-white">
                            <div class="form-group w-100 text-center description" style="font-size: 16px">
                                {{$imageVideo->description}}
                            </div>
                        </div>   
                </div><br><br>
            @endforeach
        </div>
        <div class="card-footer">
            {{$allImagesVideo->links()}}
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        ADD_IMAGEVIDEO_URL="{{route('addImagesVideos')}}";
        EDIT_IMAGEVIDEO_URL="{{route('editImagesVideos')}}";
    </script>
    <script src="{{asset('js/painel/videoImage.min.js') }}"></script>
@endsection