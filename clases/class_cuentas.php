<?php
class cuentas{
	//CUENTAS
	private $mod;
	public $modt;
	public $modi;
	public function __construct(){
		include_once('class_bd.php');		
		$this->modt = "EST_CUENTA";
		$this->modi = "CCUENTA";
		$this->mod = new data($this->modt, $this->modi);
		$this->mod->fields = array (
			array ('public',	'CUENTA'),
			array ('public',	'TIPO'),
			array ('public',	'CBANCO'),
			array ('public',	'ESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
			array ('system',	"LPAD(CCUENTA,10,'0') AS CODIGO"),
			array ('system',	"(SELECT B.BANCO FROM EST_BANCO B WHERE B.CBANCO=EST_CUENTA.CBANCO) AS BANCO")
		);
	}
	//INSERTA
	public function new_count($data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->mod->fields[9]=array("0" => "public","1" => "USUARIO_CREADO");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->mod->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL REGISTRAR LA CUENTA";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//ACTUALIZA
	public function edit_count($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->mod->fields[9]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->mod->updateRecord($id,$data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL ACTUALIZAR LA CUENTA";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA
	public function list_count($status=false,$array=false,$bank=false){
		$st = ($status) ? "AND ESTATUS='$status'" : "" ;
		$ar = ($array) ? "AND CCUENTA NOT IN ($array)" : "" ;
		$bk = ($bank) ? "AND CBANCO = '$bank'" : "" ;
		$result = $this->mod->getRecords(false,"CCUENTA > 0 $st $ar $bk");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//OBTIENE UN REGISTRO
	public function get_count($id){
		$result = $this->mod->getRecord($id);
		return $result;
	}
}
?>