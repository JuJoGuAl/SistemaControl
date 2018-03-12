<?php
class carreras{
	//CARRERAS
	private $car;
	public $cart;
	public $cari;
	public function __construct(){
		include_once('class_bd.php');		
		$this->cart = "EST_CARRERAS";
		$this->cari = "CCARRERA";
		$this->car = new data($this->cart, $this->cari);
		$this->car->fields = array (
			array ('system',	"LPAD(CCARRERA,10,'0') AS CCARRERA"),
			array ('public',	'CODIGO'),
			array ('public',	'DESCRIPCION'),
			array ('public',	'ESTATUS',"1"),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
	}
	//INSERTA LA CARRERA
	public function new_car($data){
		$response=array();
		$code=$data[0];
		$chec_cod = $this->car->getRecords("","CODIGO='$code'");
		if(empty($chec_cod)){
			//EL CODIGO NO EXISTE, PROCEDO A INSERTARLO
			//ASIGNO PUBLIC EL NUEVO CAMPO
			$this->car->fields[4]=array("0" => "public","1" => "USUARIO_CREADO");
			//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
			$data[]=$_SESSION['user_log'];
			$result=$this->car->insertRecord($data);
			if(empty($result)){
				//NO SE INSERTO
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al registrar la Carrera";
			}else{
				$response['titulo']="OK";
			}
		}else{
			//LA CI EXISTE DEVUELVO ERROR
			$response['titulo']="ERROR";
			$response['texto']='El codigo de Carrera <strong>'.$code.'</strong> ya se encuentra registrado en el sistema!';
		}
		return $response;
	}
	//ACTUALIZA LA CARRERA
	public function edit_car($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->car->fields[4]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->car->updateRecord($id,$data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al actualizar la carrera";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA LAS CARRERAS
	public function get_cars($status=false,$array=false){
		$st = $status ? $array ? "ESTATUS='$status' AND CCARRERA NOT IN ($array)":"ESTATUS='$status'":"";
		//$st = $status ? "ESTATUS='$status'"  : "";
		//$ar = $array ? "ESTATUS='$status' AND ac.cart_comercial NOT IN ($array)"  : "ESTATUS='$status'";
		$result = $this->car->getRecords(false,$st);
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//OBTIENE UNA CARRERA
	public function get_car($id){
		$result="";
		$cars = $this->car->getRecord($id);
		if(empty($cars)){
			$result=false;
		}else{
			$result=$cars;
		}
		return $result;
	}
	//LISTA CARRERAS ACTIVAS
	public function list_cars(){
		$result = $this->car->getRecords(false,"ESTATUS='1'");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
}
?>