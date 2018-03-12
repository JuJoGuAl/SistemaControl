<?php
class turnos{
	//TURNOS
	private $tu;
	public $tut;
	public $tui;
	public function __construct(){
		include_once('class_bd.php');		
		$this->tut = "EST_TURNO";
		$this->tui = "CTURNO";
		$this->tu = new data($this->tut, $this->tui);
		$this->tu->fields = array (
			array ('system',	$this->tui, "''"),
			array ('public',	'TURNO'),
			array ('public',	'ESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
	}
	//INSERTA EL REGISTRO
	public function new_turno($data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->tu->fields[3]=array("0" => "public","1" => "USUARIO_CREADO");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->tu->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al regiturar el turno";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//ACTUALIZA EL REGISTRO
	public function edit_turno($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->tu->fields[3]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->tu->updateRecord($id,$data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al actualizar el turno";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA REGISTROS
	public function get_turnos(){
		$response = $this->tu->getRecords();
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	//OBTIENE UN REGISTRO
	public function get_turno($id){
		$result="";
		$response = $this->tu->getRecord($id);
		if(empty($response)){
			$result=false;
		}else{
			$result=$response;
		}
		return $result;
	}
}
?>