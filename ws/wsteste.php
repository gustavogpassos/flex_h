<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 27/12/2018
 * Time: 11:39
 */

$array = array(
    'filial_id' => 55,
    'produto_id' => '67262'
);

$json = json_encode($array);

$ch = curl_init('http://192.168.0.19/solidus/liberacao_desconto/busca_valores_minimos/busca_valor_minimo_venda');

curl_setopt($ch, CURLOPT_USERPWD, "webservice:112bcb0f6c4f37272b60cd570244e298");

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(

        'Content-Type: application/json',

        'Content-Length: ' . strlen($json))

);

echo curl_exec($ch);

/*
$array = array(
    'filial_id' => 55,
    'numero_pedido' => '123123',
    'cliente_id' => 354033,
    'produto_id' => '67262',
    'condicao_pagamento_id' => 215,
    'tipo_cobranca_id' => 60,
    'valor_produto' => 299,
    'tabela_preco_id' => 1
);

$json = json_encode($array);

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

*/