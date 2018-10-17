<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 26/02/2018
 * Time: 11:26
 */

require_once "./model/atendModel.php";
require_once "./controller/adminController.php";

class AtendController
{
    private $model;
    private $admin;

    function __construct()
    {
        $this->model = new AtendModel();
        $this->admin = new AdminController();
    }


    /**
     * função index lista as solicitações registradas separadas por status
     *
     * Caso o usuario logado seja o regional, serao listadas apenas as solicitacoes das filiais da sua regiao
     */
    public function index()
    {
        $filiais = 0;
        $listaFlex = null;
        $status = 1;
        if (isset($_GET['filter'])) {
            if ($_GET['filter'] == 'atendimento') {
                $status = 2;
            } else if ($_GET['filter'] == 'respondido') {
                $status = 3;
            }
        }
        if ($_SESSION['tp_usuario'] == 'regional') {
            $filiais = implode(",", $_SESSION['filiais']);
        }
        $listaFlex = $this->model->getListaFlex($status, $filiais);
        require "./view/atend/list.php";
    }


    /**
     * função que obtem os dados de uma solicitação e exibe em uma pagina específica
     *
     * o parametro cd_flex é opcional e usado somente onde uma função é redirecionada para esta função
     * sendo obrigatório informar o parâmetro
     * Caso o parametro não esteja definido na chamada da função nem na variável $_GET será invocada a função index() para listar as solicitações
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
            //if ($_SESSION['tp_usuario'] == 'regional' && (isset($_SESSION['filiais']) && in_array($dados['cd_filial'], $_SESSION['filiais']))) {
                $dados['produtos'] = $this->model->getProdutos($param);
                require "./view/atend/det.php";

            /* enviado para produção sem a validação em 22/09 validar posteriormente

             } else if($dados['cd_atendente']==$_SESSION['cd_usuario'] && $dados['status']!=1) {

                echo "<script>window.alert('Solicitacao pertence a outro usuario');</script>";
                $this->index();
            }
            */
        } else {
            echo '<script>window.alert("Solicitação nao encontrada.");</script>';
            $this->index();
        }
    }

    public function assumir()
    {
        $sucesso = false;

        if (isset($_GET['idflex'])) {
            echo '<script>console.log("Tenho o parametro idflex");</script>';
            $this->admin->logs("Informada solicitacao " . $_GET['idflex'] . " para assumir.", '1', 'access');

            if (count($this->model->getFlex($_GET['idflex'])) > 0) {
                if ($this->model->assumir($_GET['idflex'], $_SESSION['cd_usuario'])) {
                    $sucesso = true;
                }
            } else {
                echo '<script>console.log("Vish. Essa solicitação não existe.");</script>';
                $this->admin->logs("A solicitacao " . $_GET['id_flex'] . " nao existe.", '1', 'access');
                $this->index();
            }
        }
        if ($sucesso) {
            $this->admin->logs("Solicitacao " . $_GET['id_flex'] . " assumida pelo atendente " . $_SESSION['cd_usuario'], '1', 'access');
            $this->detalhe($_GET['idflex']);
        } else {
            echo '<script>console.log("Deu ruim.");</script>';
            $this->admin->logs("A solicitacao " . $_GET['id_flex'] . " nao foi assumida.", '1', 'access');
        }

    }

    public function gravar()
    {
        $sucesso = 0;
        if (isset($_POST) && isset($_POST['cd_flex'])) {

            if (isset($_POST['cd_produto'])) {
                $produtos = $_POST['cd_produto'];
                $senhas = $_POST['nr_senha'];
                //echo '<script>console.log('.print_r($_POST['nr_senha']).');</script>';
                //echo '<script>console.log('.print_r($_POST['observacao']).');</script>';
                foreach ($produtos as $key => $value) {
                    if ($ret = $this->model->gravarSenha($_POST['cd_flex'], $produtos[$key], $senhas[$key], $_POST['obsmatriz'])) {
                        print_r($ret);
                        $sucesso += 1;
                    }
                }
            }
        }
        if ($sucesso == count($produtos)) {
            echo '<script>window.alert("Dados gravados com sucesso!");</script>';
            $this->admin->logs("Dados de senha da solicitacao " . $_POST['cd_flex'] . " gravados com sucesso.", '1', 'access');
            $this->detalhe($_POST['cd_flex']);
        } else {
            echo '<script>window.alert("Dados não foram gravados verifique");</script>';
            $this->admin->logs("Dados de senha da solicitacao " . $_POST['cd_flex'] . " não foram gravados, verifique.", '1', 'access');
        }
    }
}