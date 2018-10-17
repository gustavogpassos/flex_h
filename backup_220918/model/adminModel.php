<?php


/**
* 
*/

require_once 'conexao.php';

class AdminModel extends Conexao
{
	
	function __construct()
	{
		parent::__construct();
	}


	function login($usuario){
		$select = 'select * from flex_usuario where usuario=:usuario';
		$query = $this->flex->prepare($select);
		if($query->execute(array('usuario'=>$usuario))){
			return $query->fetch();
		}else{
			return $query->errorInfo();
		}

	}
	function getFiliaisSup($usuario){
	    echo "<script>console.log('Estou no model, funcao get filiais sup');</script>";
	    $sql = "select adm_fili.cd_filial from adm_fili, supefili where supefili.cd_supefili=adm_fili.cd_supefili and supefili.e_mail like upper('%".$usuario."%')";
	    if($query = $this->solidus->query($sql)){
            echo "<script>console.log('estou no model, ja fiz o select');</script>";
            return $query->fetchAll();
        }else{
            echo "<script>console.log('estou no model, deu ruim, nao fiz o select');</script>";
            print_r($query->errorInfo());
        }
    }



}

?>