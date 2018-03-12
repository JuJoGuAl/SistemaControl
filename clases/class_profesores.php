<?php
class profesores{	
	//PROFESORES
	private $db;
	public $table;
	public $Id;
	//PROF_PROFESION
	private $pf;
	public $pft;
	public $pfi;
	//PROF_DEDICACION
	private $pd;
	public $pdt;
	public $pdi;
	//PROF_CATEGORIA
	private $pc;
	public $pct;
	public $pci;
	//PROF_MATERIA
	private $db1;
	public $table1;
	public $Id1;
	public function __construct(){
		include_once('class_bd.php');
		$this->table = "PROF_PROFESORES";
		$this->tId = "CPROFESOR";
		$this->db = new data($this->table, $this->tId);
		$this->db->fields = array (
			array ('public',	'CEDULA'),
			array ('public',	'APELLIDOS'),
			array ('public',	'NOMBRES'),
			array ('public',	'F_NAC'),
			array ('public',	'CPAIS'),
			array ('public',	'CESTADO'),
			array ('public',	'CCIUDAD'),
			array ('public',	'SEXO', 'M'),
			array ('public',	'CIVIL','SOLTERO'),
			array ('public',	'DIRECCION', ''),
			array ('public',	'TELEFONOS', ''),
			array ('public',	'CESTATUS',"5"),
			array ('public',	'CPROFESION'),
			array ('public',	'CDEDICACION'),
			array ('public',	'CCATEGORIA'),
			array ('public',	'CCONDICION'),
			array ('public',	'OBSERVACION'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
			array ('system',	'(SELECT NOMBRE FROM EST_ESTATUS WHERE EST_ESTATUS.CESTATUS=PROF_PROFESORES.CESTATUS) as STATUS_NAME'),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F_NAC) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F_NAC) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F_NAC) AS FECHA_NAC"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM FECHA_CREADO) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FECHA_CREADO) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FECHA_CREADO) AS FECHA_CREADO"),
			array ('system',	"LPAD(CPROFESOR,10,'0') AS CPROFESOR"),
			array ('system',	"(APELLIDOS || ' ' || NOMBRES) AS PROFESOR")
		);
		$this->pft = "PROF_PROFESION";
		$this->pfi = "CPROFESION";
		$this->pf = new data($this->pft, $this->pfi);
		$this->pf->fields = array (
			array ('system',	$this->pfi),//IBASE NO ME ACEPTA ESPACIO EN BLANCOS EN LOS AUTOINCREMENT
			array ('public',	'PROFESION'),
			array ('public',	'ESTATUS','1'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
		$this->pdt = "PROF_DEDICACION";
		$this->pdi = "CDEDICACION";
		$this->pd = new data($this->pdt, $this->pdi);
		$this->pd->fields = array (
			array ('system',	$this->pdi),//IBASE NO ME ACEPTA ESPACIO EN BLANCOS EN LOS AUTOINCREMENT
			array ('public',	'DEDICACION'),
			array ('public',	'ESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
		$this->pct = "PROF_CATEGORIA";
		$this->pci = "CCATEGORIA";
		$this->pc = new data($this->pct, $this->pci);
		$this->pc->fields = array (
			array ('system',	$this->pci),//IBASE NO ME ACEPTA ESPACIO EN BLANCOS EN LOS AUTOINCREMENT
			array ('public',	'CATEGORIA'),
			array ('public',	'ESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')
		);
		$this->table2 = "EST_MAT_CAR_PROF";
		$this->tId2 = "CMAT_CAR_PROF";
		$this->db2 = new data($this->table2, $this->tId2);
		$this->db2->fields = array (
			array ('system',	"LPAD(CMAT_CAR_PROF,10,'0') AS CMAT_CAR_PROF"),
			array ('system',	"LPAD(CMATE,10,'0') AS CMATE"),
			array ('system',	"LPAD(CPROFESOR,10,'0') AS CPROFESOR"),
			array ('system',	"LPAD(CSECCION,10,'0') AS CSECCION"),
			array ('public',	'CMATE'),
			array ('public',	'CPROFESOR'),
			array ('public',	'CSECCION'),
			array ('system',	"(SELECT P.APELLIDOS || ' ' || P.NOMBRES FROM PROF_PROFESORES P WHERE P.CPROFESOR=EST_MAT_CAR_PROF.CPROFESOR) AS PROFESOR"),
			array ('system',	"(SELECT S.SECCION FROM EST_SECCION S WHERE S.CSECCION=EST_MAT_CAR_PROF.CSECCION) AS SECCION"),
			array ('system',	"(SELECT C.DESCRIPCION FROM EST_CARRERAS C INNER JOIN EST_MAT_CAR MC ON C.CCARRERA=MC.CCARRERA WHERE MC.CMATE=EST_MAT_CAR_PROF.CMATE) AS CARRERA"),
			array ('system',	"(SELECT C.DESCRIPCION FROM EST_MATERIAS C INNER JOIN EST_MAT_CAR MC ON C.CMATERIA=MC.CMATERIA WHERE MC.CMATE=EST_MAT_CAR_PROF.CMATE) AS MATERIA"),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION')

		);
	}
	//CREA UN PROFESOR
	public function new_profesor($data){
		//OBLIGO A LA CI CON LA MASCARA QUE NECESITO
		$ced=substr($data[0],0,1).str_pad(substr($data[0],1,9),9,'0',STR_PAD_LEFT);
		$response=array();
		$chec_ced = $this->db->getRecords("","CEDULA='$ced'");
		if(empty($chec_ced)){
			//LA CEDULA NO EXISTE PROCEDO A INSERTARLO
			//ASIGNO PUBLIC EL NUEVO CAMPO
			$this->db->fields[25]=array("0" => "public","1" => "USUARIO_CREADO");
			//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
			$data[]=$_SESSION['user_log'];
			$profe=$this->db->insertRecord($data);
			if(empty($profe)){
				//NO SE INSERTO
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al registrar el profesor";
			}else{
				$response['titulo']="OK";
			}
		}else{
			//LA CI EXISTE DEVUELVO ERROR
			$response['titulo']="ERROR";
			$response['texto']='La cedula <strong>'.$ced.'</strong> ya se encuentra registrada en el sistema!';
		}
		return $response;
	}
	//EDITA UN PROFESOR
	public function edit_profesor($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->db->fields[26]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$profe=$this->db->updateRecord($id,$data);
		if(empty($profe)){
			//NO SE INSERTO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al actualizar el profesor";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA LOS PROFESORES
	public function list_profesors($status=false){
		$st = ($status) ? "CESTATUS='$status'" : "" ;
		$prof = $this->db->getRecords(false,$st);
		if(empty($prof)){
			return false;
		}else{
			return $prof;
		}
	}
	//OBTIENE UN PROFESOR
	public function get_profesor($id){
		$prof = $this->db->getRecord($id);
		if(empty($prof)){
			return false;
		}else{
			return $prof;
		}
	}
	//BUSCA UNA CI DE ALUMNO EN LA BD
	public function get_ced($ced,$code){
		$ced=strtoupper(substr($ced,0,1).str_pad(substr($ced,1,9),9,'0',STR_PAD_LEFT));
		if($code>0){
			$filter="CEDULA='$ced' AND CPROFESOR<> '$code'";
		}else{
			$filter="CEDULA='$ced'";
		}
		$alm = $this->db->getRecords(false,$filter);
		if(empty($alm)){
			return false;
		}else{
			return true;
		}
	}
	//LISTA LAS PROFESIONES
	public function list_prof($status=false){
		$st = ($status) ? "ESTATUS='$status'" : "" ;
		$response = $this->pf->getRecords(false,$st);
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	//LISTA LAS DEDICACIONES
	public function list_ded($status=false){
		$st = ($status) ? "ESTATUS='$status'" : "" ;
		$response = $this->pd->getRecords(false,$st);
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	//LISTA LAS CATEGORIAS
	public function list_cat($status=false){
		$st = ($status) ? "ESTATUS='$status'" : "" ;
		$response = $this->pc->getRecords(false,$st);
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	//LISTA LOS PROFESORES
	public function list_prof_car($mate=false,$sec=false){
		$fmate = ($mate) ? "AND CMATE='$mate'" : "" ;
		$fsec = ($sec) ? "AND CSECCION='$sec'" : "" ;
		$result = $this->db2->getRecords(false,"CMAT_CAR_PROF >0 $fmate $fsec");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//LISTA LOS PROFESORES CON MATERIAS
	public function list_prof_mat($mate=false,$sec=false){
		$fmate = ($mate) ? "AND CMATE='$mate'" : "" ;
		$fsec = ($sec) ? "AND CSECCION='$sec'" : "" ;
		$data = $this->db2->getRecords("CPROFESOR,(SELECT P.APELLIDOS || ' ' || P.NOMBRES FROM PROF_PROFESORES P WHERE P.CPROFESOR=EST_MAT_CAR_PROF.CPROFESOR) AS PROFESOR","CMAT_CAR_PROF >0 $fmate $fsec");
		$result=array();
		$prof=array();
		$prof['CPROFESOR']=-1;
		$prof['PROFESOR']='SELECCIONE';
		array_push($result, $prof);
		if(!empty($data)){
			foreach ($data as $key => $value){
				array_push($result, $value);
			}
		}
		return $result;
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//AGREGA UN CRUCE PROFESOR-MATERIA-SECCION
	public function new_prof_mat($data){
		//OBLIGO A LA CI CON LA MASCARA QUE NECESITO
		$response=array();
		$cmate=$data[0];
		$cprof=$data[1];
		$csec=$data[2];
		$chec_reg = $this->db2->getRecords(false,"CMATE='$cmate' AND CPROFESOR='$cprof' AND CSECCION='$csec'");
		if(empty($chec_reg)){
			//REGISTRO NO EXISTE
			//ASIGNO PUBLIC EL NUEVO CAMPO
			$this->db2->fields[15]=array("0" => "public","1" => "USUARIO_CREADO");
			//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
			$data[]=$_SESSION['user_log'];
			$registro=$this->db2->insertRecord($data);
			if(empty($registro)){
				//NO SE INSERTO
				$response['titulo']="ERROR";
				$response['texto']="ERROR AL REGISTRAR LA MATERIA PARA EL PROFESOR";
			}else{
				$response['titulo']="OK";
			}
		}else{
			//EL REGISTRO EXISTE
			$response['titulo']="ERROR";
			$response['texto']='EL REGISTRO QUE INTENTA CREAR YA EXISTE EN LA BASE DE DATOS';
		}
		return $response;
	}
	//BORRA UN CRUCE
	public function del_prof_mat($id){
		$result=$this->db2->deleteRecord($id);
		if($result){
			$response['titulo']="OK";
		}else{
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL ELIMINAR EL CRUCE";			
		}
		return $response;
	}
}
?>