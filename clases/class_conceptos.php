<?php
class conceptos{
	//CONCEPTOS
	private $mod;
	public $modt;
	public $modi;
	public function __construct(){
		include_once('class_bd.php');		
		$this->modt = "EST_CONCEPTO";
		$this->modi = "CCONCEPTO";
		$this->mod = new data($this->modt, $this->modi);
		$this->mod->fields = array (
			array ('public',	'CONCEPTO'),
			array ('public',	'PRECIO'),
			array ('public',	'ESTATUS'),
			array ('public',	'SISTEMA'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
			array ('system',	"LPAD(CCONCEPTO,10,'0') AS CCONCEPTO")
		);
	}
	//INSERTA
	public function new_concep($data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->mod->fields[8]=array("0" => "public","1" => "USUARIO_CREADO");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->mod->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al registrar el Concepto";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//ACTUALIZA
	public function edit_concep($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->mod->fields[8]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->mod->updateRecord($id,$data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al actualizar el Concepto";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA
	public function list_concep($status=false,$array=false,$sistema='-1'){
		$st = ($status) ? "AND ESTATUS='$status'" : "" ;
		$ar = ($array) ? "AND CCONCEPTO NOT IN ($array)" : "" ;
		$sys = ($sistema<>'-1') ? "AND SISTEMA = '$sistema'" : "" ;
		//$st = $status ? $array ? "ESTATUS='$status' AND CCONCEPTO NOT IN ($array)":"ESTATUS='$status'":"";
		$result = $this->mod->getRecords(false,"CCONCEPTO > 0 $st $ar $sys");
		//$result = $this->mod->getRecords();
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//OBTIENE UN REGISTRO
	public function get_concep($id){
		$result = $this->mod->getRecord($id);
		return $result;
	}
}
?>