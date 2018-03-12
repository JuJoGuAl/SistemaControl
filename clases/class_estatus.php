<?php
class estatus{
	//STATUS
	private $st;
	public $stt;
	public $sti;
	public function __construct(){
		include_once('class_bd.php');		
		$this->stt = "EST_ESTATUS";
		$this->sti = "CESTATUS";
		$this->st = new data($this->stt, $this->sti);
		$this->st->fields = array (
			array ('system',	$this->sti, "''"),
			array ('public',	'NOMBRE'),
			array ('public',	'CLASE',"'A'"),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
	} //public function __construct()
	//INSERTA EL REGISTRO
	public function new_status($data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->st->fields[3]=array("0" => "public","1" => "USUARIO_CREADO");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$status=$this->st->insertRecord($data);
		if(empty($status)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al registrar el status";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//ACTUALIZA EL REGISTRO
	public function edit_status($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->st->fields[3]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$status=$this->st->updateRecord($id,$data);
		if(empty($status)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al actualizar el status";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA REGISTROS DE LA TABLA
	public function get_status(){
		$sts = $this->st->getRecords();
		if(empty($sts)){
			return false;
		}else{
			return $sts;
		}
	}
	//OBTIENE UN REGISTRO DE LA TABLA
	public function get_statu($id){
		$result="";
		$sts = $this->st->getRecord($id);
		if(empty($sts)){
			$result=false;
		}else{
			$result=$sts;
		}
		return $result;
	}
	//OBTIENE LOS ESTATUS
	public function list_st($class){
		$sts = $this->st->getRecords(false,"CLASE='$class'");
		if(empty($sts)){
			return false;
		}else{
			return $sts;
		}
	}
}
?>