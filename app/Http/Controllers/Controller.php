<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function newToken($login, $senha){
        return md5(uniqid(trim($login."".md5($senha)), true));
    }


    public static function getHumano(){
        $dados = ["acao"=>"gerar_pessoa","txt_qtde"=>1,"sexo"=>"I"];
        $ch = curl_init( "https://www.4devs.com.br/ferramentas_online.php" );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($dados));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        return $result;
    }

    public static function getBin($bin){
        $mg = curl_init();
        curl_setopt_array($mg, array(
        CURLOPT_URL => 'https://binlist.io/lookup/'.$bin.'/',
        CURLOPT_RETURNTRANSFER =>1,
        CURLOPT_FOLLOWLOCATION =>1,
        CURLOPT_COOKIEFILE => getcwd().'/bin.txt',
        CURLOPT_COOKIEJAR => getcwd().'/bin.txt', ));
        $dados_bin = curl_exec($mg);
        curl_close($mg);
        $bandeira = self::dados($dados_bin,'"scheme": "','"');
        $tipo = self::dados($dados_bin,'"type": "','"');
        $card = self::dados($dados_bin,'"category": "','"');
        $pais = self::dados($dados_bin,'"name": "','"');
        $banco = self::dados($dados_bin,'"bank": {"name": "','"');

        return [
            "bandeira"=>$bandeira,
            "tipo"=>$tipo,
            "card"=>$card,
            "pais"=>$pais,
            "banco"=>$banco,
        ];
    }

    public static function debitaSaldo($id){
        $saldo = DB::table('saldo_db')
        ->where('user', $id)
        ->first();
        if($saldo){
            $saldo_total = intval($saldo->saldo);
            if($saldo_total > 0){
                $novo_saldo = ($saldo_total - 1);
                DB::table('saldo_db')
                ->where('user', $id)
                ->update([
                    "saldo"=>$novo_saldo
                ]);
            }
            ob_flush();
            return response()->json([
                "message"=>"Você esta sem saldo"
            ], 400);
        
        }
        ob_flush();
        return response()->json([
            "message"=>"Você esta sem saldo"
        ], 400);
    }

    public static function dados($string, $start, $end){
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str[0];
    }
    
}
