<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 27/12/2018
 * Time: 09:06
 */

class Webservice
{
    public function __construct(){}

    public function getSenhaWS($params)
    {
        $array = array(
            'filial_id' => $params['cd_filial'],
            'numero_pedido' => $params['nr_pedido'],
            'cliente_id' => $params['cd_cliente'],
            'produto_id' => $params['cd_produto'],
            'condicao_pagamento_id' => $params['cd_condpgto'],
            'tipo_cobranca_id' => $params['cd_tipocobr'],
            'valor_produto' => $params['vl_desc'],
            'tabela_preco_id' => $params['nr_tabpreco']
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


        return curl_exec($ch);
    }


    public function getPrecoMinWS($params)
    {
        //set_time_limit(5);
        $array = array(
            'filial_id' => $params['cd_filial'],
            'produto_id' => $params['cd_produto']
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

        return curl_exec($ch);
    }
}