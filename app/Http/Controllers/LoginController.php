<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function home(){
        if(session('logado')){return redirect('/');}

        return view('login');
    }

    public function login(Request $request){
        

        $login = $request->input('login');
        $senha = $request->input('senha');

        $query = DB::table('user_db')
        ->where('login', trim($login))
        ->where('senha', md5(trim($senha)))
        ->first();

        if($query){

            if($query->logado == 1){
                return back()->with([
                    "message"=>"Você esta logado em outro local. Não posso deixar você entrar",
                    "type"=>"alert-danger",
                    "visible"=>"d-block"
                ]);
            }

            $status = intval($query->status);

            if($status == 1){

                $saldo = DB::table('saldo_db')
                ->where('user', intval($query->id))
                ->first();

                $request->session()->put('logado', true);
                $request->session()->put('id', $query->id);
                $request->session()->put('nome', trim($query->nome));
                $request->session()->put('saldo', $saldo->saldo);
                $request->session()->put('token', trim($query->token));
                
                return redirect('/');
            }

            $request->session()->flush();

            return back()->with([
                "message"=>"Usuário Desativado",
                "type"=>"alert-danger",
                "visible"=>"d-block"
            ]);

        }

        $request->session()->flush();

        return back()->with([
            "message"=>"Login ou senha inválidos",
            "type"=>"alert-danger",
            "visible"=>"d-block"
        ]);
    }

    public function cadastro(Request $request){
        
        $nome = $request->input('nome');
        $login = $request->input('login');
        $senha = $request->input('senha');
        
        $query = DB::table('user_db')
        ->where('login', trim($login))
        ->first();

        if($query){
            return back()->with([
                "message"=>"Login já cadastrado",
                "type"=>"alert-danger",
                "visible"=>"d-block"
            ]);
        }

        $token = Controller::newToken($login, $senha);

        DB::table('user_db')
        ->insert([
            "login"=>trim($login),
            "nome"=>trim($nome),
            "senha"=>md5(trim($senha)),
            "token"=>trim($token),
            "status"=>1,
            "logado"=>0
        ]);

        $id = DB::getPdo()->lastInsertId();

        DB::table('saldo_db')
        ->insert([
            "user"=>intval($id),
            "saldo"=>0
        ]);

        return back()->with([
            "message"=>"Cadastro realizado com sucesso",
            "type"=>"alert-success",
            "visible"=>"d-block"
        ]);
    }

    public function sair(Request $request){
       
        $query = DB::table('user_db')
        ->where('id', intval(session('id')))
        ->first();

        if($query){
            $query = DB::table('user_db')
            ->where('id', intval(session('id')))
            ->update([
                "logado"=>0
            ]);
            $request->session()->flush();
            return redirect('login');
        }

        $request->session()->flush();
        return redirect('login');
    }
}
