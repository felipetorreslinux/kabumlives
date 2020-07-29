<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        if(!session('logado')){return redirect('login');}
        return view('home.home');
    }
}
