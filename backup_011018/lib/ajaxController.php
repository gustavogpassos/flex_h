<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 27/02/2018
 * Time: 16:39
 */

require_once "./controller/lojaController.php";

class ajaxController
{
    private $cont;
    public function __construct()
    {
        return "cheguei aqui";
        //exit();

        $this->cont = new lojaController();
        if(isset($_GET['action'])){
            return $this->getPedido($_GET['param']);
        }
    }

    public function getPedido($nrPedido){
        return $this->cont->getPedido($nrPedido);
    }


}