<?php


/**
 *
 */

require './model/adminModel.php';

class AdminController
{

    private $model;

    function __construct()
    {
        $this->model = new AdminModel();
    }

    public function login($usuario, $senha)
    {
        $params = array();
        $existeusuario = true;

        if (stristr($usuario, 'vendas')) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['tp_usuario'] = 'loja';
            $_SESSION['cd_filial'] = substr($usuario, 6);
        } else {
            $user = $this->model->login($usuario);
            if (isset($user['usuario']) && $user['fl_ativo']=='s') {
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['cd_usuario'] = $user['cd_usuario'];
                $_SESSION['tp_usuario'] = $user['tp_usuario'];
                $_SESSION['nome_usuario'] = $user['nome'];
                if($user['tp_usuario']=='regional'){
                    $_SESSION['cd_supefili'] = $user['cd_supefili'];
                    $_SESSION['filiais'] = array();
                    if($list_temp=$this->model->getFiliaisSup($user['usuario'])){
                        foreach ($list_temp as $filial){
                            array_push($_SESSION['filiais'],$filial['cd_filial']);
                        }
                        $this->logs('Lista de filiais do regional '.$usuario.' carregada',1,'access');
                    }else{
                        $this->logs($_SESSION['filiais'],1,'error');
                        $existeusuario = false;
                    }
                }
            } else {
                $existeusuario = false;
            }
        }
        if ($this->ldapBind($usuario, $senha) && $existeusuario == true) {
            $_SESSION['logado'] = true;
        } else {
            $_SESSION['logado'] = false;
        }
        return $params;
    }

    public function ldapBind($usuario, $senha)
    {
        $bind = false;
        $ldaphost = "ldap://192.168.0.6";
        //$ldaphost = "ldap://200.215.160.94";
        $ldapport = 389;
        $ldapbind = null;

        $ds = @ldap_connect($ldaphost, $ldapport) or die("Could not connect to $ldaphost");
        @ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

        if ($ds) {
            $upasswd = $senha;
            $binddn = 'uid=' . $usuario . ",ou=Usuarios,dc=lojasvolpato,dc=local";

            try {
                $ldapbind = @ldap_bind($ds, $binddn, $upasswd);
                //echo '<script>console.log('.print_r($ldapbind).');</script>';
            } catch (Exception $e) {
                //echo '<script>console.log('.print_r($e).');</script>';
                $bind = false;
            }
            //check if ldap was sucessfull
            if ($ldapbind) {
                $bind = true;
            } else {
                $bind = false;
            }
        }
        return $bind;
    }

    public function logs($msg, $filial, $arquivo){
            $log = fopen('./logs/'.date('d-m-Y').'_fil'.$filial.'_'.$arquivo.'_log.txt','a');
            fwrite($log,"log - ".date('d-m-Y_H-i-s')." - ".$msg."\r\n");
            fclose($log);
    }
}

?>