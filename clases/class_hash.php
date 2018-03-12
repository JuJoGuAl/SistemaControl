<?php
class hash{
	//HASH
	private $bd;
	public $table;
	public $campo;
	public function __construct(){
		include_once('class_bd.php');		
		$this->table = "ADM_PARAMETRO";
		$this->campo = "CPARAMETRO";
		$this->bd = new data($this->table, $this->campo);
		$this->bd->fields = array (
			array ('system',	'CPARAMETRO')
		);
	}
	//INSERTA
	public function new_hash($len){
		$user=$_SESSION['user_log'];
		//PIDO UN HASH
		$new_hash = generate_hash($len);
		//TOMO EL HASH Y LO CODIFICO, PARA GUARDARLO EN LA BD
		$new_hash2 = md5($new_hash);
		$hash = $this->bd->query("UPDATE ADM_PARAMETRO SET VALOR='$new_hash2', USUARIO_MODIFICACION='$user' WHERE CPARAMETRO=10 RETURNING CPARAMETRO");
		if(empty($hash)){
			//NO SE GENERO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL GENERAR EL HASH";
		}else{
			$response['titulo']="OK";
			$response['texto']=$new_hash;
		}
		return $response;
	}
}
?>