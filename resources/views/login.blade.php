
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Uno do Brasil">
    <title>KabumLives - Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="/img/kabum.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="/css/forms/icheck/custom.css">
    
    <link rel="stylesheet" type="text/css" href="/css/app.min.css">
    
    <link rel="stylesheet" type="text/css" href="/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css" href="/css/core/colors/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css" href="/css/pages/login-register.min.css">

    <link rel="stylesheet" href="/fonts/feather/style.css">
    <link rel="stylesheet" href="/fonts/flag-icon-css/css/flag-icon.css">
    <link rel="stylesheet" href="/fonts/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/fonts/meteocons/style.css">
    <link rel="stylesheet" href="/fonts/simple-line-icons/style.css">
    <link rel="stylesheet" href="/fonts/simple-line-icons1/style.css">
    <link rel="stylesheet" href="/css/styles.css">
    
  </head>
  <body class="vertical-layout vertical-menu 1-column menu-expanded blank-page blank-page" data-menu="vertical-menu" data-col="1-column">
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="flexbox-container bg-black">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-md-3 col-10">
                            <div class="d-flex flex-column align-items-center py-1">
                                <img src="/img/kabum.png" width="150" class="img-fluid">
                                <span>KabumLives</span>
                            </div>
                            <form action="/login/entrar" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="card m-0">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="alert {{session('type') ?? ''}} {{session('visible') ?? 'd-none'}} text-center" role="alert">
                                                <span class="white">{{session('message') ?? ''}}</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="texto-label grey">Login</label>
                                                <input type="text" name="login" max="20" maxlength="20" required class="form-control" max="100" maxlength="100">
                                            </div>
                                            <div class="form-group">
                                                <label class="texto-label grey">Senha</label>
                                                <input type="text" onfocus="$(this).attr('type', 'password')" autocomplete="new-password" name="senha" required  max="10" maxlength="10" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center py-2">
                                    <small class="mb-1">Versão: 2.45</small>
                                    <button type="submit" class="btn btn-info">Entrar</button>
                                    <small class="mt-1">2018 - 2020 © Todos os Direitos Reservados</small>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    
    <!-- Modal Novo Cadastro -->
    <div class="modal fade" id="modal-cadastro">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form action="/login/novo-cadastro" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-content">
                    <div class="modal-body bg-dark">
                        <span class="modal-title white">Novo Cadastro</span> 
                        <hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="texto-label grey">Crie seu login</label>
                                    <input type="text" name="login" autocomplete="off" required  max="20" maxlength="20" class="form-control white bg-dark">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="texto-label grey">Seu nome</label>
                                    <input type="text" name="nome" required autocomplete="off" required max="50" maxlength="50" class="form-control white bg-dark">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="texto-label grey">Crie sua senha</label>
                                    <input type="text" name="senha" onfocus="$(this).attr('type', 'password')" autocomplete="new-password"  required  max="20" maxlength="20" class="form-control white bg-dark">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <p class="white-light font-small-2">Seja bem vindo ao KabumLives.<br>Saiba que não nos responsabilizamos pelos seus atos, com as informações vindas de nosso painel. Use com prudência e sabedoria.</p>
                            </div>
                        </div>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-info">Criar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>        
    </div>
    

    <script src="/js/vendors.min.js"></script>
    <script src="/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
    <script src="/js/forms/icheck/icheck.min.js"></script>
    <script src="/js/forms/validation/jqBootstrapValidation.js"></script>
    <script src="/js/core/app-menu.min.js"></script>
    <script src="/js/core/app.min.js"></script>
    <script src="/js/scripts/forms/form-login-register.min.js"></script>
    
  </body>
</html>