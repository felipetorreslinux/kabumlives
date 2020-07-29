<?php
use Illuminate\Support\Facades\DB;
$saldo = DB::table('saldo_db')->where('user', intval(session('id')))->first();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/img/logo_plin.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">

    <title>KabumLives | @yield('title', 'Central')</title>

    <link rel="stylesheet" href="/css/vendors.min.css">
    <link rel="stylesheet" href="/css/forms/selects/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="/css/forms/icheck/custom.css">

    <link rel="stylesheet" href="/css/app.css">
    
    <link rel="stylesheet" href="/css/core/menu/menu-types/vertical-menu.min.css">        

    <link rel="stylesheet" href="/fonts/simple-line-icons/style.css">
    <link rel="stylesheet" href="/fonts/simple-line-icons1/style.css">
    <link rel="stylesheet" href="/css/styles.css">

    @stack('css')

</head>
<body class="vertical-layout bg-black">

    <nav class="navbar navbar-expand-lg nax-shadow">
        <div class="container">
            <span class="navbar-brand align-items-center" href="/">
                <img src="/img/kabum.png" class="avatar p-0 m-0">
                <span>KabumLives</span>
            </span> 
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item d-none">
                        
                    </li>
                </ul>
                <ul class="navbar-nav mr-0 align-items-center">
                    <li class="nav-item mr-2">
                        <span class="btn btn-sm btn-info">
                            <div class="d-flex flex-row align-items-center justify-content-between">
                                <span class="m-0">Saldo</span>
                                <h4 class="m-0 white">{{$saldo->saldo ?? '0'}} <small>C</small></h4>
                            </div>
                        </span>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="d-flex flex-row align-items-center">
                                <img src="/img/kabum.png" class="avatar mr-1">
                                <span>{{session('nome')}}</span>
                            </div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <span class="dropdown-item" data-target="#modal-comprar" data-toggle="modal">Comprar crédito</span>
                            <span class="dropdown-item" data-target="#modal-senha" data-toggle="modal" href="#">Alterar senha</span>
                            <a class="dropdown-item" href="/login/sair">Sair</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="content app-content">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <footer class="footer fixed-bottom container">
        <div class="d-flex flex-row align-items-center justify-content-center">
            <small class="mr-1">2018 - 2020 © Desenvolvido por KabumLives - Todos os Direitos Reservados</small>
        </div>
    </footer>


    <!-- Modal Compra Crédito -->
    <div class="modal fade" id="modal-comprar">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body bg-dark">
                    <div class="">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <span class="white">Comprar Crédito</span>
                            <span class="white-light">1 Crédito = R$ 1,00</span>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="white-light">Valor</label>
                            <select name="valor" class="form-control" style="width:100%;">
                                <option value="">Selecione o valor</option>
                                <option value="50">50 Créditos</option>
                                <option value="100">100 Créditos</option>
                                <option value="150">150 Créditos</option>
                                <option value="200">200 Créditos</option>
                                <option value="300">300 Créditos</option>
                            </select>
                        </div>
                        <div class="d-flex flex-row align-items-center justify-content-between pt-1">
                            <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                            <button class="btn btn-info btn-comprar">Comprar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Senha -->
    <div class="modal fade" id="modal-senha">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body bg-dark">
                    <form action="/login/alterar-senha">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <span class="white">Alterar senha</span>
                            <span class="white-light"></span>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="white-light">Senha atual</label>
                            <input type="text" name="senha" onfocus="$(this).attr('type', 'password')" autocomplete="new-password"  required  max="20" maxlength="20" class="form-control white bg-dark">
                        </div>
                        <div class="form-group">
                            <label class="white-light">Nova senha</label>
                            <input type="text" name="senha" onfocus="$(this).attr('type', 'password')" autocomplete="new-password"  required  max="20" maxlength="20" class="form-control white bg-dark">
                        </div>
                        <div class="d-flex flex-row align-items-center justify-content-between pt-1">
                            <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-info">Alterar</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    
    <script src="/js/vendors.min.js"></script>
    <script src="/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
    <script src="/js/forms/select/select2.full.min.js"></script>
    <script src="/js/core/app-menu.min.js"></script>
    <script src="/js/core/app.min.js"></script>
    <script src="/js/bootstrap-notify.min.js"></script>
    @stack('js')

</body>
</html>
