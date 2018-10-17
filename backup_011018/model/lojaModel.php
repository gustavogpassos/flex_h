<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 27/02/2018
 * Time: 09:51
 */
require_once "conexao.php";


class LojaModel extends Conexao
{
    public $filial;

    /**
     * LojaModel constructor.
     * função construtora que instancia as conexões necessárias para realizar as consultas
     */
    function __construct()
    {
        parent::__construct();
        try {
            $this->filial = new PDO(
                'pgsql:host=192.168.' . $_SESSION['cd_filial'] . '.1;dbname=solidus', 'postgres', 'postgres');

            echo '<script>console.log("Conectado à base da filial ' . $_SESSION['cd_filial'] . ' com sucesso.");</script>';
        } catch (Exception $exception) {
            echo '<script>console.log("Não foi possível estabelecer conexão com a filial ' . $_SESSION['cd_filial'] . '");</script>';
        }
    }



    // **************************************************************************** //
    // SEÇÃO DAS OPERAÇÕES REALIZADAS NA BASE DE DADOS DO SISTEMA FLEX - 172.16.0.5 //
    // **************************************************************************** //

    /**
     * insere dados do formulario de flex pela função postgres insertflex()
     *
     * @param $dados
     * @return array
     */
    function insertFlex($dados)
    {
        $insert = "SELECT insertflex(:cd_filial, :cd_supefili, :nm_supefili, :nr_pedido, :cd_cliente, :nm_cliente," .
            ":cd_condpgto, :nm_condpgto, :cd_tipocobr, :nm_tipocobr, :observacao);";

        $query = $this->flex->prepare($insert);
        if ($query->execute($dados)) {
            return $query->fetch();
        } else {
            print_r($query->errorInfo());
        }
    }

    /**
     * insere os produtos referentes ao formulario de flex pela função postgres insertproduto()
     *
     * @param $produtos
     * @param $idFlex
     * @return bool
     */
    function insertprodutos($produtos, $cdFlex)
    {
        $bool = false;


        foreach ($produtos['cd_produto'] as $coluna => $array) {

            $insert = "select insertproduto(
                                :cd_flex,
                                :cd_produto,
                                :nm_produto,
                                :fl_foralinha,
                                :vl_minimo,
                                :vl_desconto,
                                :nr_tab_oferta);";
            $query = $this->flex->prepare($insert);

            $rs = $query->execute(array('cd_flex' => $cdFlex[0],
                'cd_produto' => $produtos['cd_produto'][$coluna],
                'nm_produto' => $produtos['nm_produto'][$coluna],
                'fl_foralinha' => $produtos['fl_foralinha'][$coluna],
                'vl_minimo' => $produtos['vl_total'][$coluna],
                'vl_desconto' => $produtos['vl_desc'][$coluna],
                'nr_tab_oferta' => $produtos['nr_tabpreco'][$coluna]));
            if ($rs) {
                $bool = true;
            } else {
                print_r($this->flex->errorInfo());
                $bool = false;
            }
        }
        return $bool;
    }


    /**
     * exclui o registro da solicitação na tabela flex_formulario em caso de erro
     * ao realizar a gravação dos dados na tabela flex_produtos
     *
     * @param $cdFlex
     * @return array
     *
     */
    function deleteFlex($cd_flex)
    {
        $select = "DELETE FROM flex_formulario WHERE cd_flex=:cd_flex";
        $query = $this->flex->prepare($select);
        if ($query->execute(array('cd_flex' => $cd_flex))) {
            return true;
        } else {
            print_r($this->flex->errorInfo());
            return false;
        }
    }


    /**
     * retorna lista de solicitações de uma filial pelo status;
     * Status : 1-Novo, 2-Em atendimento, 3-Encerrado;
     * @param $cdFilial
     * @return array
     */
    function getFlexFilial($cdFilial, $status)
    {
        $select = "SELECT * FROM flex_formulario WHERE cd_filial=:cdFilial AND status=:status ORDER BY cd_flex DESC";
        $query = $this->flex->prepare($select);
        $query->execute(array('cdFilial' => $cdFilial, 'status' => $status));
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
        echo '<script>console.log("estou na função getFlex() no model");</script>';
        $select = "SELECT * FROM flex_formulario WHERE cd_flex=:cdFlex";
        $query = $this->flex->prepare($select);
        $query->execute(array('cdFlex' => $cdFlex));
        //print_r($query);
        //print_r($query->fetch());
        return $query->fetch();
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
        $query->execute(array('cdFlex' => $cdFlex));
        return $query->fetchAll();

    }

    /**
     * retorna quantas solicitações estao registradas com base no status
     * @param $status
     * @return mixed
     */
    function flexCount($status, $cd_filial)
    {
        $select = 'SELECT count(*) FROM flex_formulario WHERE status=:status AND cd_filial=:cd_filial';
        $query = $this->flex->prepare($select);
        $query->execute(array('status' => $status, 'cd_filial' => $cd_filial));
        return $query->fetch();
    }


    // ***************************************************************** //
    // SEÇÃO DAS CONSULTAS REALIZADAS NA BASE DA MATRIZ - SOLIDUS MATRIZ //
    // ***************************************************************** //

    /**
     * Função que busca os dados do pedido já gravado na base de dados da filial
     *
     * @param $nr_pedido
     * @return mixed
     */
    public function getPedido($nr_pedido)
    {
        $select = "SELECT 
                        nf.cd_filial,
                        nf.nr_pedido,
                        nf.cd_cliente,
                        nf.cd_condpgto,
                        nf.cd_tipocobr,
                        cli.nm_fornecedor AS nm_cliente,
                        con.nm_condpgto,
                        cob.nm_tipocobr,
                        sup.nm_supefili,
                        sup.cd_supefili,
                        nf.dt_pedido,
                        nf.cd_atualizacao,
                        nf.cd_cancelamento
                        FROM
                         nfsc nf,
                         adm_forn cli,
                         condpgto con,
                         tipocobr cob,
                         supefili sup,
                         adm_fili fil
                        WHERE
                        nf.cd_cliente=cli.cd_fornecedor
                        AND nf.cd_condpgto=con.cd_condpgto
                        AND nf.cd_tipocobr=cob.cd_tipocobr
                        AND nf.cd_filial=fil.cd_filial
                        AND fil.cd_supefili=sup.cd_supefili
                        AND nf.cd_grupo_oper='V'
                        AND nf.nr_pedido=:pedido
                        AND nf.cd_filial=:cd_filial";
        $query = $this->filial->prepare($select);
        $query->execute(array('pedido' => $nr_pedido,'cd_filial'=>$_SESSION['cd_filial']));
        return $query->fetch();
    }

    /**
     * retorna os produtos pertencentes ao pedido para o qual a solicitação será registrada
     *
     * @param $nr_pedido
     * @return array
     */
    public function getProdutosPedido($nr_pedido)
    {
        $select = "SELECT 
                      nfsi.cd_produto,
                      nfsi.nr_tabpreco,
                      adm_prod.nm_produto, 
                      adm_prod.fl_fora_de_linh as fl_foralinha, 
                      nfsi.vl_total
                  FROM
                      nfsi,nfsc,adm_prod
                  WHERE 
                      nfsc.nr_chave=nfsi.nr_chave
                  AND nfsi.cd_produto=adm_prod.cd_produto
                  AND nfsc.nr_pedido=:nr_pedido
                  AND nfsc.cd_filial=:cd_filial";
        $query = $this->filial->prepare($select);
        $query->execute(array('nr_pedido' => $nr_pedido, 'cd_filial'=>$_SESSION['cd_filial']));
        return $query->fetchAll();
    }

    /**
     * retorna o email do supervisor regional pertencente a filial informada
     * @param $cdFilial
     * @return mixed
     */
    function getEmailSuper($cdFilial)
    {
        $select = 'SELECT lower(sup.e_mail) AS email FROM supefili sup, adm_fili fil WHERE fil.cd_supefili=sup.cd_supefili AND fil.cd_filial=:cd_filial';
        $query = $this->solidus->prepare($select);
        $query->execute(array('cd_filial' => $cdFilial));
        return $query->fetch();
    }





    // ***************************************************************** //
    // SEÇÃO DAS CONSULTAS REALIZADAS NA BASE DA FILIAL - SOLIDUS FILIAL //
    // ***************************************************************** //


    function testefilial()
    {
        $sql = "SELECT nr_pedido,cd_cliente,vl_tot_nota, dt_pedido FROM nfsc WHERE nr_pedido=468731";
        $query = $this->filial->query($sql);
        return $query->fetch();
    }

}