<?php

use Illuminate\Support\Facades\Route;

Route::prefix('login')->group(function(){
    Route::get('/', 'LoginController@home');
    Route::post('/entrar', 'LoginController@login');
    Route::post('/novo-cadastro', 'LoginController@cadastro');
    Route::get('/sair', 'LoginController@sair');
});

Route::prefix('cartao')->group(function(){
    Route::get('/consulta', 'CartaoController@cartao');
});


Route::get('/', 'HomeController@home');





