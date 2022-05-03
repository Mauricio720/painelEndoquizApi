@extends('dashboard.base')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h5>Informações do usuário aplicativo</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Básico</h5>
                            </div>
                            <div class="card-body">
                                <div class="input-info__group">
                                    <div class="input-info__title">Nome</div>
                                    <div class="input__info">
                                        {{$user->name}}
                                    </div>
                                </div>

                                <div class="input-info__group">
                                    <div class="input-info__title">Apelido</div>
                                    <div class="input__info">
                                        {{$user->nickname!=""?$user->nickname:'Não informado'}}
                                    </div>
                                </div>

                                <div class="input-info__group">
                                    <div class="input-info__title">Sobrenome</div>
                                    <div class="input__info">
                                        {{$user->lastname}}
                                    </div>
                                </div>

                                <div class="input-info__group">
                                    <div class="input-info__title">Email</div>
                                    <div class="input__info">
                                        {{$user->email}}
                                    </div>
                                </div>

                                <div class="input-info__group">
                                    <div class="input-info__title">Registro</div>
                                    <div class="input__info">
                                        {{date('d/m/Y',strtotime($user->registerDate))." - ".date('H:i:s',strtotime($user->registerTime))}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($userInfo != null)
                            <div class="card">
                                <div class="card-header">
                                    <h5>Endereço</h5>
                                </div>

                                <div class="card-body">
                                    <div class="input-info__group">
                                        <div class="input-info__title">Rua</div>
                                        <div class="input__info">
                                            {{$userInfo->street}}
                                        </div>
                                    </div>

                                    <div class="input-info__group">
                                        <div class="input-info__title">Numero</div>
                                        <div class="input__info">
                                            {{$userInfo->number}}
                                        </div>
                                    </div>

                                    <div class="input-info__group">
                                        <div class="input-info__title">Bairro</div>
                                        <div class="input__info">
                                            {{$userInfo->neighboorhood}}
                                        </div>
                                    </div>

                                    <div class="input-info__group">
                                        <div class="input-info__title">Cidade</div>
                                        <div class="input__info">
                                            {{$userInfo->city}}
                                        </div>
                                    </div>

                                    <div class="input-info__group">
                                        <div class="input-info__title">Estado</div>
                                        <div class="input__info">
                                            {{$userInfo->state}}
                                        </div>
                                    </div>

                                    <div class="input-info__group">
                                        <div class="input-info__title">Cep</div>
                                        <div class="input__info">
                                            {{$userInfo->cep}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif    
                    </div>

                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Hospital</h5>
                            </div>
                           <div class="card-body">
                                <div class="input-info__group">
                                    <div class="input-info__title">Hospital que atua</div>
                                    <div class="input__info">
                                        {{$userInfo->hospitalWork}}
                                    </div>
                                </div>

                                <div class="input-info__group">
                                    <div class="input-info__title">Onde fez residência</div>
                                    <div class="input__info">
                                        {{$userInfo->residenceLocal}}
                                    </div>
                                </div>
                           </div>
                        </div>

                       <div class="card">
                            <div class="card-header">
                                <h5>Plano</h5>
                            </div>
                            <div class="card-body">
                                 <div class="input-info__group">
                                     <div class="input-info__title">Valor</div>
                                     <div class="input__info">
                                         {{$paymentInfo->value}}
                                     </div>
                                 </div>
 
                                 <div class="input-info__group">
                                     <div class="input-info__title">Tipo Plano</div>
                                     <div class="input__info">
                                         {{$paymentInfo->typePlan==1?'Gratuito':'Premium'}}
                                     </div>
                                 </div>

                                 <div class="input-info__group">
                                    <div class="input-info__title">Limite questões</div>
                                    <div class="input__info">
                                        {{$paymentInfo->limitQuestions!=""?$paymentInfo->limitQuestions."%":'Sem Limite'}}
                                    </div>
                                </div>

                                <div class="input-info__group">
                                    <div class="input-info__title">Questões respondidas</div>
                                    <div class="input__info">
                                        {{$paymentInfo->questionsAnswered}}
                                    </div>
                                </div>

                                <div class="input-info__group">
                                    <div class="input-info__title">Status</div>
                                    <div class="input__info">
                                        @if($paymentInfo->status==0)
                                            Sem tentativa
                                        @elseif($paymentInfo->status==1)
                                            Pendente
                                        @elseif($paymentInfo->status==2)        
                                            Pago
                                        @elseif($paymentInfo->status==3)
                                            Cancelado 
                                        @endif
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection