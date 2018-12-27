<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 26/02/2018
 * Time: 11:26
 */
require_once "./model/lojaModel.php";
require_once "./controller/adminController.php";
require_once "./ws/webservice.php";

class LojaController
{
    private $model;
    private $admin;
    private $ws;

    function __construct()
    {
        $this->model = new LojaModel();
        $this->admin = new AdminController();
        $this->ws - new Webservice();
    }

    public function index()
    {
        $listaFlex = 0;
        $status = 1;
        if(isset($_GET['filter'])){
            if($_GET['filter'] == 'atendimento'){
                $status = 2;
            }else if($_GET['filter'] == 'respondido'){
                $status = 3;
            }
        }
        $this->admin->logs("Listando soliciatções da filial ".$_SESSION['cd_filial'], $_SESSION['cd_filial']);
        $listaFlex = $this->model->getFlexFilial($_SESSION['cd_filial'], $status);
        require "./view/loja/list.php";
    }

    public function novo()
    {
        $action = 'home.php?action=gravar';
        if (isset($_POST['nr_pedido'])) {
            $dados = $this->model->getPedido($_POST['nr_pedido']);
            if (count($dados) == 1) {
                echo "<script>window.alert('Pedido não encontrado!Verifique.');</script>";
            } else {
                $dados['produtos'] = $this->model->getProdutosPedido($_POST['nr_pedido']);
                $listMotivos = $this->model->getListaMotivos();

            }
        }
        require "./view/loja/new.php";
        $action = "home.php?action=gravar";

/*
else if ($dados['dt_pedido'] != date('Y-m-d', time())) {
                echo "<script>window.alert('Data do pedido diferente do dia atual. Verifique.');</script>";
                $dados = false;
            }
*/
    }

    public function gravar()
    {

        $produtos['cd_produto']=$_POST['cd_produto'];
        $produtos['nm_produto']=$_POST['nm_produto'];
        $produtos['fl_foralinha']=$_POST['fl_foralinha'];
        $produtos['nr_tabpreco']=$_POST['nr_tabpreco'];
        $produtos['vl_total']=$_POST['vl_total'];
        $_POST['vl_desc'] = str_replace('.','',$_POST['vl_desc']);
        $produtos['vl_desc']=str_replace(',','.',$_POST['vl_desc']);
        $produtos['nodesc']=$_POST['nodesc'];
        unset($_POST['cd_produto']);
        unset($_POST['nm_produto']);
        unset($_POST['fl_foralinha']);
        unset($_POST['nr_tabpreco']);
        unset($_POST['vl_total']);
        unset($_POST['vl_desc']);
        unset($_POST['nodesc']);
        if($cd_flex = $this->model->insertFlex($_POST)){
            if($this->model->insertprodutos($produtos,$cd_flex)){
                //print_r($produtos);
                echo "<script>window.alert('Solicitação registrada com sucesso!');</script>";
                $this->admin->logs("Solicitacao ".$cd_flex." da filial ".$_SESSION['cd_filial']." registrada", $_SESSION['cd_filial']);
                $this->detalhe($cd_flex);
            }else{
                $this->model->deleteFlex($cd_flex[0]);
                echo "<script>window.alert('Não foi possível registrar a solicitação.Tente novamente.');</script>";
                $this->admin->logs("Solicitacao ".$cd_flex." da filial ".$_SESSION['cd_filial']." não foi registrada", $_SESSION['cd_filial']);
            }
        }
    }


    /**
     * função que obtem os dados de uma solicitação e exibe em uma pagina específica
     * @param int $cd_flex
     */
    public function detalhe($cd_flex = 0)
    {
        $param = 0;
        if ($cd_flex != 0) {
            $param = $cd_flex;
        } else if (isset($_GET['idflex'])) {
            $param = $_GET['idflex'];
        }
        //echo '<script>console.log("tenho o parametro.");</script>';
        if ($dados = $this->model->getFlex($param)) {
            $dados['produtos'] = $this->model->getProdutos($param);
            require "./view/loja/det.php";
        } else {
            $this->index();
        }
    }


}