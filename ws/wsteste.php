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

$ch = curl_init('http://192.168.0.19/liberacao_desconto/liberacoes/busca_valores_minimos');

curl_setopt($ch, CURLOPT_USERPWD, "webservice:112bcb0f6c4f37272b60cd570244e298");

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(

        'Content-Type: application/json',

        'Content-Length: ' . strlen($json))

);

echo curl_exec($ch);