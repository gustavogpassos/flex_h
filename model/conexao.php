<?php


/**
* 
*/
class Conexao {
    public $solidus;
    public $flex;

    function __construct() {
        $this->estabeleceConexao();
    }

    public function estabeleceConexao() {
        try {
            $dsnsolidus = 'pgsql:host=192.168.0.9;dbname=solidus';
            $this->solidus = new PDO(
                $dsnsolidus,'postgres','postgres'
            );
            $dsnflex = 'pgsql:host=localhost;dbname=flex_homolog';
            $this->flex = new PDO(
                $dsnflex,'postgres','postgres'
            );
            echo '<script>console.log("'.$dsnflex.'");</script>';

        } catch (PDOException $exc) {
            echo '<script>window.alert("Houve um problema com a conexão ao banco de dados. Entre em contato com o administrador do sistema!");</script>';
            exit;
        }
    }
}






?>