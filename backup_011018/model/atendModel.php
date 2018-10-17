<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 27/02/2018
 * Time: 09:51
 */
require_once "conexao.php";

class AtendModel extends Conexao
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * retorna lista de solicitações de todas as filiais pelo status;
     * Status : 1-Novo, 2-Em atendimento, 3-Encerrado;
     * @param $cdFilial
     * @return array
     */
    function getListaFlex($status, $filiais)
    {
        $param = array('status' => $status);
        if (isset($_SESSION['cd_usuario']) && $_SESSION['tp_usuario'] == 'regional' && $filiais != 0) {
            $select = "SELECT * FROM flex_formulario WHERE status=:status and cd_filial in (" . $filiais . ") ORDER BY cd_flex DESC";
        }else{
            if($status != 1){
                $select = "SELECT * FROM flex_formulario WHERE status=:status and cd_atendente=:cd_usuario ORDER BY cd_flex DESC";
                $param['cd_usuario']=$_SESSION['cd_usuario'];
            }else {
                $select = "SELECT * FROM flex_formulario WHERE status=:status ORDER BY cd_flex DESC";
            }
        }

        $query = $this->flex->prepare($select);
        $query->execute($param);
        return $query->fetchAll();
    }


    /**
     * retorna os dados de um registro de formulario flex pela chave recebida por parametro
     *
     * @param $cdFlex
     * @return array
     *
     */
    public function getFlex($cdFlex)
    {
        //echo '<script>console.log("estou na função getFlex() no model");</script>';
        $select = "SELECT * FROM flex_formulario WHERE cd_flex=:cdFlex";
        $query = $this->flex->prepare($select);
        if ($query->execute(array('cdFlex' => $cdFlex))) {
            return $query->fetch();
        } else {
            return $query->errorInfo();
        }
    }

    /**
     * retorna todos os produtos relacionados a uma solicitação de flex baseado na chave
     * passada por parametro
     *
     * @param $cdFlex
     * @return array
     */
    function getProdutos($cdFlex)
    {
        $select = "SELECT * FROM flex_produto WHERE cd_flex=:cdFlex";
        $query = $this->flex->prepare($select);
        if ($query->execute(array('cdFlex' => $cdFlex))) {
            return $query->fetchAll();
        } else {
            return $query->errorInfo();
        }
    }

    public function assumir($cd_flex, $cd_usuario)
    {
        $update = "update flex_formulario set status=2, cd_atendente=:cd_usuario where cd_flex=:cd_flex";
        $query = $this->flex->prepare($update);
        if ($query->execute(array('cd_usuario' => $cd_usuario, 'cd_flex' => $cd_flex))) {
            return true;
        } else {
            return $query->errorInfo();
        }
    }

    public function gravarSenha($cd_flex, $cd_produto, $senha, $obs)
    {
        $update = "select gravasenha(:nr_senha, :cd_flex, :cd_produto, :observacao)";
        $query = $this->flex->prepare($update);
        if ($query->execute(array('nr_senha' => $senha, 'cd_flex' => $cd_flex, 'cd_produto' => $cd_produto, 'observacao' => $obs))) {
            return true;
        } else {
            return $query->errorInfo();
        }

    }
}