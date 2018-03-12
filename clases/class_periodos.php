<?php
class periodos{
	//PERIODOS
	private $pe;
	public $pet;
	public $pei;
	public function __construct(){
		include_once('class_bd.php');		
		$this->pet = "EST_PERIODO";
		$this->pei = "CPERIODO";
		$this->pe = new data($this->pet, $this->pei);
		$this->pe->fields = array (
			array ('system',	$this->pei, "''"),
			array ('public',	'F_PER'),
			array ('public',	'TPERIODO'),
			array ('public',	'F_INI'),
			array ('public',	'F_FIN'),
			array ('public',	'F_GRAD',),
			array ('public',	'PROMO',''),
			array ('public',	'F_INIR'),
			array ('public',	'F_FINR'),
			array ('public',	'F_INII'),
			array ('public',	'F_FINI'),
			array ('public',	'MINSCRIPCION'),
			array ('public',	'MSEMESTRE'),
			array ('public',	'ESTATUS'),
			array ('system',	"(F_PER || '-' || TPERIODO) AS PERIODO"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F_INI) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F_INI) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F_INI) AS FECHA_INI"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F_FIN) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F_FIN) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F_FIN) AS FECHA_FIN"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F_GRAD) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F_GRAD) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F_GRAD) AS FECHA_GRAD"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F_INIR) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F_INIR) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F_INIR) AS FECHA_INIR"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F_FINR) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F_FINR) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F_FINR) AS FECHA_FINR"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F_INII) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F_INII) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F_INII) AS FECHA_INII"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F_FINI) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F_FINI) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F_FINI) AS FECHA_FINI"),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
	}
	//INSERTA EL REGISTRO
	public function new_peri($data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->pe->fields[14]=array("0" => "public","1" => "USUARIO_CREADO");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->pe->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al regiturar el Periodo";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//ACTUALIZA EL REGISTRO
	public function edit_peri($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->pe->fields[14]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->pe->updateRecord($id,$data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al actualizar el Periodo";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA REGISTROS
	public function get_pers($status=false,$tipo=false){
		$st = $status ? "ESTATUS='$status'":"";
		$tip = $tipo ? " AND TPERIODO <> '$tipo'":"";
		$response = $this->pe->getRecords(false,$st.$tip);
		//$response = $this->pe->getRecords();
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	//OBTIENE UN REGISTRO
	public function get_per($id){
		$result="";
		$response = $this->pe->getRecord($id);
		if(empty($response)){
			$result=false;
		}else{
			$result=$response;
		}
		return $result;
	}
}
?>