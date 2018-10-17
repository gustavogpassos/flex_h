<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 17/01/2018
 * Time: 14:37
 */

class MainController
{
    function __construct()
    {
        if($_SESSION['tp_usuario'] == 'loja') {
            $ctrNome = 'lojaController';
        }else{
            $ctrNome = 'atendController';
        }
        //echo '<script>console.log("Estamos aqui");</script>';
        if (!isset($_GET['action']))
            $acao = 'index';
        else
            $acao = $_GET['action'];
        $arq = './controller/' . $ctrNome . '.php';
        if (file_exists($arq)) {
            require $arq;
            $controlador = new $ctrNome();
            if (method_exists($controlador, $acao)) {
                $controlador->$acao();
            }else{
                $controlador->index();
            }
        } else {
            echo "<script>window.alert('Parâmetros inválidos. Finalizando sessão.');</script>";
            header('Location: index.php?logout');
        }

    }
}