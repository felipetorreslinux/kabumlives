@extends('home')
@section('title', 'Central')
@push('css')
<style>
#aprovadas{
    height:auto;
    overflow-x:hidden;
    overflow-y:auto;
}
</style>
@endpush
@section('content')
<div class="container">
    <div class="d-flex flex-column align-items-center p-2">
    <img src="/img/kabum.png" width="150">
    <span class="white-light">KabumLives</span>
    <small>Versão: 2.45</small>
</div>
<div class="d-flex flex-row align-items-center justify-content-center py-2">
    <button class="btn btn-info px-3 btn-credito">Crédito</button>
    <div class="mx-1"></div>
    <button class="btn btn-green px-3 btn-geradas">Geradas</button>
    <div class="mx-1"></div>
    <button class="btn btn-danger px-3">Logins</button>
</div>

<!-- Modal Crérdito -->
<div class="modal fade" id="modal-checker" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="modal-content">
            <div class="modal-body bg-dark">
                <span class="modal-title white">Instância - C. Crédito</span>
                <hr>
                <div class="">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex flex-column h-100 justify-content-between">
                                        <div class="d-flex flex-row align-items-center justify-content-between">
                                            <span>BRL</span>
                                            <small class="info">Online</small>
                                        </div>
                                        <div class="d-flex flex-column justify-content-between">
                                            <div class="d-flex flex-column">
                                                <span>Seridor Rede</span>
                                                <small class="grey">Cada <strong class="black">aprovada</strong> desconta <strong>1</strong> crédito.</small>
                                            </div>
                                        </div>
                                        <img src="/img/bandeiras.png" width="160">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="white-light">Lista para teste</label>
                                <textarea name="lista" id="lista" style="resize:none;" required placeholder="552223466453xxxx|02|2026|234" class="form-control rounded bg-dark white" cols="1" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-2">
                    <div class="card-body">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <span>Aprovadas</span>
                            <button class="btn btn-sm btn-secondary btn-copy">Copiar</button>
                        </div>
                        <hr>
                        <div id="aprovadas"></div>
                    </div>
                </div>
                <div class="d-flex flex-row align-items-center justify-content-between">
                    <button type="button" class="btn btn-default btn-fechar-cartao" data-dismiss="modal">Fechar</button>
                    <button type="button" onclick="enviarLista()" class="btn btn-info btn-enviar-cartao">Iniciar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Geradas -->
<div class="modal fade" id="modal-geradas" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="modal-content">
            <div class="modal-body bg-dark">
                <span class="modal-title white">Geradas</span>
                <hr>
                <div class="">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex flex-column h-100 justify-content-between">
                                        <div class="d-flex flex-row align-items-center justify-content-between">
                                            <span>BRL</span>
                                            <small class="info">Online</small>
                                        </div>
                                        <div class="d-flex flex-column justify-content-between">
                                            <div class="d-flex flex-column">
                                                <span>Seridor Rede</span>
                                                <small class="grey">Cada <strong class="black">aprovada</strong> desconta <strong>1</strong> crédito.</small>
                                            </div>
                                        </div>
                                        <img src="/img/bandeiras.png" width="160">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="white-light">Lista para teste</label>
                                <textarea name="lista" id="lista" style="resize:none;" required placeholder="552223466453xxxx|02|2026|234" class="form-control rounded bg-dark white" cols="1" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-2">
                    <div class="card-body">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <span>Aprovadas</span>
                            <button class="btn btn-sm btn-secondary btn-copy">Copiar</button>
                        </div>
                        <hr>
                        <div id="aprovadas"></div>
                    </div>
                </div>
                <div class="d-flex flex-row align-items-center justify-content-between">
                    <button type="button" class="btn btn-default btn-fechar-cartao" data-dismiss="modal">Fechar</button>
                    <button type="button" onclick="enviarLista()" class="btn btn-info btn-enviar-cartao">Iniciar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script src="/core/home.js"></script>
@endpush