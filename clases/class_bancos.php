<?php
class bancos{
	//BANCOS
	private $mod;
	public $modt;
	public $modi;
	public function __construct(){
		include_once('class_bd.php');		
		$this->modt = "EST_BANCO";
		$this->modi = "CBANCO";
		$this->mod = new data($this->modt, $this->modi);
		$this->mod->fields = array (
			array ('public',	'BANCO'),
			array ('public',	'ESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
			array ('system',	"LPAD(CBANCO,10,'0') AS CODIGO")
		);
	}
	//INSERTA
	public function new_bank($data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->mod->fields[6]=array("0" => "public","1" => "USUARIO_CREADO");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->mod->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL REGISTRAR EL BANCO";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//ACTUALIZA
	public function edit_bank($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->mod->fields[6]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->mod->updateRecord($id,$data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL ACTUALIZAR UN BANCO";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA
	public function list_bank($status=false,$array=false){
		$st = ($status) ? "AND ESTATUS='$status'" : "" ;
		$ar = ($array) ? "AND CBANCO NOT IN ($array)" : "" ;
		$result = $this->mod->getRecords(false,"CBANCO > 0 $st $ar");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//OBTIENE UN REGISTRO
	public function get_bank($id){
		$result = $this->mod->getRecord($id);
		return $result;
	}
}
?>