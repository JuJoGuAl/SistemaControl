<?php
class convenios{
	//CONVENIOS
	private $bd;
	public $table;
	public $campo;
	public function __construct(){
		include_once('class_bd.php');		
		$this->table = "EST_CONVENIO";
		$this->campo = "CCONVENIO";
		$this->bd = new data($this->table, $this->campo);
		$this->bd->fields = array (
			array ('public',	'CONVENIO'),
			array ('public',	'ESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
			array ('system',	"LPAD(CCONVENIO,10,'0') AS CCONVENIO")
		);
	}
	//INSERTA
	public function new_con($data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->bd->fields[6]=array("0" => "public","1" => "USUARIO_CREADO");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->bd->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL REGISTRAR EL CONVENIO";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//ACTUALIZA
	public function edit_con($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->bd->fields[6]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->bd->updateRecord($id,$data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL ACTUALIZAR EL CONVENIO";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA
	public function list_con($status=false){
		$st = ($status) ? "AND ESTATUS='$status'" : "";
		$result = $this->bd->getRecords(false,"CCONVENIO > 0 $st");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//OBTIENE UN REGISTRO
	public function get_con($id){
		$result = $this->bd->getRecord($id);
		return $result;
	}
}
?>