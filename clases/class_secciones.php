<?php
class secciones{	
	//SECCIONES
	private $db;
	public $table;
	public $Id;
	public function __construct(){
		include_once('class_bd.php');
		$this->table = "EST_SECCION";
		$this->tId = "CSECCION";
		$this->db = new data($this->table, $this->tId);
		$this->db->fields = array (
			array ('public',	'SECCION'),
			array ('public',	'CCARRERA'),
			array ('public',	'SEMESTRE'),
			array ('public',	'CUPOS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
			array ('system',	'(SELECT T1.DESCRIPCION FROM EST_CARRERAS T1 WHERE T1.CCARRERA=EST_SECCION.CCARRERA) AS CARRERA'),
			array ('system',	"LPAD(CSECCION,10,'0') AS CSECCION")
		);
	}
	//NUEVO SECCION
	public function new_seccion($data){
		$seccion=strtoupper($data[0]);
		$response=array();
		$check_data = $this->db->getRecords("","SECCION='$seccion'");
		if(empty($check_data)){
			//NO EXISTE LO INSERTO
			//ASIGNO PUBLIC EL NUEVO CAMPO
			$this->db->fields[5]=array("0" => "public","1" => "USUARIO_CREADO");
			//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
			$data[]=$_SESSION['user_log'];
			$query=$this->db->insertRecord($data);
			if(empty($query)){
				//NO SE INSERTO
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al registrar la seccion";
			}else{
				$response['titulo']="OK";
			}
		}else{
			//EXISTE
			$response['titulo']="ERROR";
			$response['texto']='La Seccion <strong>'.$seccion.'</strong> ya se encuentra registrada en el sistema!';
		}
		return $response;
	}
	//ACTUALIZO SECCION
	public function edit_seccion($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->db->fields[5]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$query=$this->db->updateRecord($id,$data);
		if(empty($query)){
			//NO SE ACTUALIZO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al actualizar la seccion";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//OBTIENE LAS SECCIONES
	public function get_secciones($carrera=false){
		$car = ($carrera) ? "CCARRERA='$carrera'" : "" ;
		$data = $this->db->getRecords(false,$car);
		if(empty($data)){
			return false;
		}else{
			return $data;
		}
	}
	//BUSCA UNA SECCION
	public function get_sec($sec,$code){
		$seccion=strtoupper($sec);
		if($code>0){
			$filter="SECCION='$seccion' AND CSECCION <> '$code'";
		}else{
			$filter="SECCION='$seccion'";
		}
		$data = $this->db->getRecords(false,$filter);
		if(empty($data)){
			return false;
		}else{
			return true;
		}
	}
	//OBTIENE UNA SECCION
	public function get_seccion($id){
		$result="";
		$data = $this->db->getRecord($id);
		if(empty($data)){
			$result=false;
		}else{
			$result=$data;
		}
		return $result;
	}
	//OBTIENE LAS SECCIONES DE UNA CARRERA
	public function get_sec_car($car,$cmate,$cper,$sec){
		$data = $this->db->query("SELECT LPAD(S.CSECCION,10,'0') AS CSECCION, S.SECCION, S.CUPOS, LPAD(S.CCARRERA,10,'0') AS CCARRERA, ((S.CUPOS)-(SELECT COUNT(ID.CINSCRIPCION_DET) FROM EST_INSCRIPCION I INNER JOIN EST_INSCRIPCION_DET ID ON I.CINSCRIPCION=ID.CINSCRIPCION WHERE I.CPERIODO='$cper' AND ID.CMATE='$cmate' AND ID.CSECCION=S.CSECCION)) AS DISP FROM EST_SECCION S WHERE S.CCARRERA='$car' AND S.SEMESTRE='$sec'");
		$result=array();
		$seccion=array();
		$seccion['CSECCION']=-1;
		$seccion['SECCION']='SELECCIONE';
		$seccion['CUPOS']=1;
		$seccion['CCARRERA']='A';
		$seccion['DISP']=1;
		array_push($result, $seccion);
		if(!empty($data)){
			foreach ($data as $key => $value){
				if($data[$key]['DISP']>0){
					array_push($result, $value);
				}
			}
		}
		return $result;
	}
	//LISTA SECCIONES (CARRERA Y SEMESTRE)
	public function lis_sec_car_sem($car=false,$sem=false){
		$fcar = ($car) ? "AND CCARRERA='$car'" : "" ;
		$fsem = ($sem) ? "AND SEMESTRE='$sem'" : "" ;
		$result = $this->db->getRecords(false,"CSECCION > 0 $fcar $fsem");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
}
?>