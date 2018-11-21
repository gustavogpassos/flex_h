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
        } else {
            if ($status == 2) {
                $select = "SELECT * FROM flex_formulario WHERE status=:status and cd_atendente=:cd_usuario ORDER BY cd_flex DESC";
                $param['cd_usuario'] = $_SESSION['cd_usuario'];
            } else {
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
        $select = "SELECT form.*, mot.descricao as motivo
                       FROM flex_formulario form
                       left join flex_motivo mot on (form.cd_motivo=mot.cd_motivo)
                       WHERE cd_flex=:cdFlex";
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

    /**
     * Essa função é responsável por alterar a coluna status e cd_atendente da tabela flex_formulario
     *
     * @param $cd_flex
     * @param $cd_usuario
     * @return bool
     */
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

    /**
     * Essa função é responsável por gravar o numero da senha ou a palavra negado para os produtos da solicitação
     * Essa função altera a tabela flex_produto
     * @param $cd_flex
     * @param $cd_produto
     * @param $senha
     * @param $obs
     * @return bool
     */
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

    /**
     * Essa função retorna se há solicitações registradas para um determinado cliente (código) passado por parâmetro
     * O retorno desconsidera a solicitação que está sendo acessada e retorna somente as solicitações com numero diferente
     * @param $cd_cliente
     * @param $cd_flex
     * @return mixed
     */
    public function flexPorCliente($cd_cliente, $cd_flex)
    {
        $select = "select cd_flex from flex_formulario where cd_cliente=:cd_cliente and cd_flex!=:cd_flex and data_solicitacao >= current_date -30";
        $query = $this->flex->prepare($select);
        if ($query->execute(array('cd_cliente' => $cd_cliente, 'cd_flex' => $cd_flex))) {
            return $query->fetchAll();
        } else {
            return $query->errorInfo();
        }
    }

    public function getValorFlex($cd_supefili = 0)
    {
        $select = "SELECT
                    flex_formulario.cd_supefili as regional,
                    sum(flex_produto.vl_minimo - flex_produto.vl_desconto) as vl_flex,
                    flex_usuario.limite
                    FROM flex_formulario
                    JOIN flex_produto on ( flex_formulario.cd_flex=flex_produto.cd_flex )
                    JOIN flex_usuario on ( flex_usuario.cd_supefili=flex_formulario.cd_supefili)
                    WHERE flex_produto.nr_senha <> 'Negado'
                    AND flex_produto.nr_senha <> '0.00'
                    AND flex_produto.dt_liberacao=current_date ";
        if ($cd_supefili != 0) {
            $select .= "AND flex_formulario.cd_supefili=$cd_supefili ";
        }
        $select .= "GROUP BY flex_formulario.cd_supefili, flex_usuario.limite
                    ORDER BY flex_formulario.cd_supefili";

        $query = $this->flex->query($select);
        return $query->fetchAll();
    }
}