<?php


//print_r($_GET);

$array = array(
    'filial_id' => $_GET['cd_filial'],
    'numero_pedido' => $_GET['nr_pedido'],
    'cliente_id' => $_GET['cd_cliente'],
    'produto_id' => $_GET['cd_produto'],
    'condicao_pagamento_id' => $_GET['cd_condpgto'],
    'tipo_cobranca_id' => $_GET['cd_tipocobr'],
    'valor_produto' => $_GET['vl_desc'],
    'tabela_preco_id' => $_GET['nr_tabpreco']
);

//print_r($array);

$json = json_encode($array);

//print_r($json);

$ch = curl_init('http://192.168.0.19/solidus/liberacao_desconto/liberacoes/senha_liberacao');

curl_setopt($ch, CURLOPT_USERPWD, "webservice:112bcb0f6c4f37272b60cd570244e298");

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(

        'Content-Type: application/json',

        'Content-Length: ' . strlen($json))

);


echo curl_exec($ch);


//print_r(var_dump($response));

?>
