<?php
class EST_MATERIAS{
	private $mat;
	public $matt;
	public $mati;

	private $matcar;
	public $matcart;
	public $matcari;

	private $matpres;
	public $matprest;
	public $matpresi;
	public function __construct(){
		include_once('class_bd.php');		
		$this->matt = "EST_MATERIAS";
		$this->mati = "CMATERIA";
		$this->mat = new data($this->matt, $this->mati);
		$this->mat->fields = array (
			array ('system',	"LPAD(CMATERIA,10,'0') AS CMATERIA"),
			array ('public',	'CODIGO'),
			array ('public',	'DESCRIPCION'),
			array ('public',	'HT'),
			array ('public',	'HP'),
			array ('public',	'UC'),
			array ('public',	'CUPO1'),
			array ('public',	'CUPO2'),
			array ('public',	'VER'),
			array ('public',	'NOTAS'),
			array ('public',	'ESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
		$this->matcart = "EST_MAT_CAR";
		$this->matcari = "CMATE";
		$this->matcar = new data($this->matcart, $this->matcari);
		$this->matcar->fields = array (
			array ('system',	"LPAD(CMATE,10,'0') AS CMATE"),
			array ('public',	'CMATERIA'),
			array ('public',	'CCARRERA'),
			array ('public',	'SEMESTRE'),
			array ('system',	'(SELECT T1.DESCRIPCION FROM EST_MATERIAS T1 WHERE T1.CMATERIA=EST_MAT_CAR.CMATERIA) AS MATERIA'),
			array ('system',	"(SELECT LPAD(T1.CMATERIA,10,'0') FROM EST_MATERIAS T1 WHERE T1.CMATERIA=EST_MAT_CAR.CMATERIA) AS CODE_MATERIA"),
			array ('system',	"(SELECT CODIGO FROM EST_MATERIAS T1 WHERE T1.CMATERIA=EST_MAT_CAR.CMATERIA) AS MATERIA_CODE"),
			array ('system',	'(SELECT T1.DESCRIPCION FROM EST_CARRERAS T1 WHERE T1.CCARRERA=EST_MAT_CAR.CCARRERA) AS CARRERA'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
			array ('system',	"LPAD(CMATERIA,10,'0') AS ID_MATERIA")
		);
		$this->matprest = "EST_MAT_PRES";
		$this->matpresi = "CPRESLA";
		$this->matpres = new data($this->matprest, $this->matpresi);
		$this->matpres->fields = array (
			array ('system',	$this->matpresi, "''"),
			array ('public',	'CMATERIA'),
			array ('public',	'CPRESLACION'),
			array ('public',	'CCARRERA'),
			array ('public',	'CTIPO'),
			array ('public',	'CANT'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
	}
	//INSERTA MATERIA
	public function new_mat($data){
		$response=array();
		$code=$data[0];
		$chec_cod = $this->mat->getRecords("","CODIGO='$code'");
		if(empty($chec_cod)){
			//EL CODIGO NO EXISTE, PROCEDO A INSERTARLO
			//ASIGNO PUBLIC EL NUEVO CAMPO
			$this->mat->fields[11]=array("0" => "public","1" => "USUARIO_CREADO");
			//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
			$data[]=$_SESSION['user_log'];
			$result=$this->mat->insertRecord($data);
			if(empty($result)){
				//NO SE INSERTO
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al registrar la Materia";
			}else{
				$response['titulo']="OK";
			}
		}else{
			//LA CI EXISTE DEVUELVO ERROR
			$response['titulo']="ERROR";
			$response['texto']='El codigo de Materia <strong>'.$code.'</strong> ya se encuentra registrado en el sistema!';
		}
		return $response;
	}
	//ACTUALIZA LA MATERIA
	public function edit_mat($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->mat->fields[11]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->mat->updateRecord($id,$data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al actualizar la materia";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA LAS MATERIAS
	public function get_mats($status=false){
		$st = ($status) ? "ESTATUS='$status'" : "" ;
		$result = $this->mat->getRecords(false,$st);
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//OBTIENE UNA CARRERA
	public function get_mat($id){
		$result="";
		$mats = $this->mat->getRecord($id);
		if(empty($mats)){
			$result=false;
		}else{
			$result[0]=$mats;
			$mat_car = $this->matcar->getRecords(false,"CMATERIA='$id'");
			if(!empty($mat_car)){
				$result[1] = $mat_car;
			}
		}
		return $result;
	}
	//BORRA UNA RELACION MAT_CAR
	public function del_mat_car($idmat){
		$result=$this->matcar->deleteRecord($idmat);
		if($result){
			$response['titulo']="OK";
		}else{
			$response['titulo']="ERROR";
			$response['texto']="No se pudo eliminar la Relación Materia - Carrera";			
		}
		return $response;
	}
	//CREA UNA RELACION MAT_CAR
	public function new_mat_car($data){
		$response=array();
		$cmat=$data[0];
		$ccar=$data[1];
		$chec_mats = $this->matcar->getRecords("","CMATERIA='$cmat' AND CCARRERA='$ccar'");
		if(empty($chec_mats)){
			//ASIGNO PUBLIC EL NUEVO CAMPO
			$this->matcar->fields[12]=array("0" => "public","1" => "USUARIO_CREADO");
			//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
			$data[]=$_SESSION['user_log'];
			$result=$this->matcar->insertRecord($data);
			if(empty($result)){
				//NO SE INSERTO
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al registrar la Relacion";
			}else{
				$response['titulo']="OK";
			}
		}else{
			$response['titulo']="ERROR";
			$response['texto']='La relación Materia - Carrera ya existe en el Sistema!';
		}
		return $response;
	}
	//OBTIENE UNA PRESLACION DE MATERIAS - CARRERAS
	public function get_mat_pres($mat,$car){
		$result="";
		$mats = $this->matpres->getRecords(false,"CMATERIA='$mat' AND CCARRERA='$car'");
		if(empty($mats)){
			$result=false;
		}else{
			$result=$mats;
		}
		return $result;
	}
	//BORRA UNA PRESLACION
	public function del_mat_pres($idpres){
		$result=$this->matpres->deleteRecord($idpres);
		if($result){
			$response['titulo']="OK";
		}else{
			$response['titulo']="ERROR";
			$response['texto']="No se pudo eliminar la Preslacion";			
		}
		return $response;
	}
	//LISTA LAS MATERIAS DISPONIBLES EN UNA PRESLACION
	public function list_mat_pres($car,$sem,$array=false){
		$ar = $array ? "CCARRERA='$car' AND SEMESTRE < $sem AND CMATERIA NOT IN ($array)" :"CCARRERA='$car' AND SEMESTRE < $sem";
		$result = $this->matcar->getRecords(false,$ar);
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//OBTIENE UNA RELACION CARRERA - MATERIA
	public function get_presla($id){
		$result="";
		$mats = $this->matcar->getRecord($id);
		if(empty($mats)){
			$result=false;
		}else{
			$result=$mats;
		}
		return $result;
	}
	//CREA UNA PRELACION
	public function new_presla($data)
	{
		$response=array();
		$cmat=$data[0];
		$cpres=$data[1];
		$ccar=$data[2];
		$chec_pres = $this->matpres->getRecords("","CMATERIA='$cmat' AND CPRESLACION='$cpres' AND CCARRERA='$ccar'");
		//echo "materia: ".$cmat." prelacion: ".$cpres." carrera: ".$ccar;
		//print_r($chec_pres);
		if(empty($chec_pres)){
			//ASIGNO PUBLIC EL NUEVO CAMPO
			$this->matpres->fields[6]=array("0" => "public","1" => "USUARIO_CREADO");
			//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
			$data[]=$_SESSION['user_log'];
			$result=$this->matpres->insertRecord($data);
			if(empty($result)){
				//NO SE INSERTO
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al registrar la Prelación";
			}else{
				$response['titulo']="OK";
			}
		}else{
			$response['titulo']="ERROR";
			$response['texto']='La Prelación que esta intentando crear ya existe en el Sistema!';
		}
		return $response;
	}
	//LISTA LAS MATERIAS DISPONIBLES DE UNA CARRERA O TODAS
	public function list_mats($car=false,$group=false){
		$carrerra = $car ? "CCARRERA='$car'":"";
		$campos = ($group) ? "DISTINCT(LPAD(CMATERIA,10,'0')) AS ID_MATERIA,(SELECT T1.DESCRIPCION FROM EST_MATERIAS T1 WHERE T1.CMATERIA=EST_MAT_CAR.CMATERIA) AS MATERIA,(SELECT CODIGO FROM EST_MATERIAS T1 WHERE T1.CMATERIA=EST_MAT_CAR.CMATERIA) AS MATERIA_CODE" : false ;
		$result = $this->matcar->getRecords($campos,$carrerra);
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//OBTIENE UNA MATERIA-CARRERA SEGUN LA MATERIA
	public function get_mat_car($id){
		$result = $this->matcar->getRecords(false,"CMATERIA='$id'");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
}
?>