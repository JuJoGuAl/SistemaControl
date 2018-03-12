<?php
class alumnos{	
	//ALUMNOS
	private $db;
	public $table;
	public $Id;
	//PLANTEL_ALUMNO_EGRESADO
	private $db2;
	public $table2;
	public $Id2;
	//ALUM_DOCUMENTOS
	private $db3;
	public $table3;
	public $Id3;
	//ALUM_DOCUMENTOS_ALUMNO
	private $db4;
	public $table4;
	public $Id4;
	//SALDO_ALUMNOS
	private $db5;
	public $table5;
	public $Id5;
	//DEBIDO A QUE EN EL CONSTRUCT NO PUEDO COLOCAR CAMPOS QUE SOLO SE EDITEN, O SE LEAN, PROCEDO A COLOCAR UCREA EN INSERT Y UMODIFICA EN UPDATE
	public function __construct(){
		include_once('class_bd.php');
		$this->table = "ALUM_ALUMNOS";
		$this->tId = "CALUMNO";
		$this->db = new data($this->table, $this->tId);
		$this->db->fields = array (
			array ('public',	'CEDULA'),
			array ('public',	'APELLIDOS'),
			array ('public',	'NOMBRES'),
			array ('public',	'F_NAC'),			
			array ('public',	'CPAIS'),
			array ('public',	'CESTADO'),
			array ('public',	'CCIUDAD'),
			array ('public',	'ETNIA', 'N/A'),
			array ('public',	'SEXO', 'M'),
			array ('public',	'CIVIL','SOLTERO'),
			array ('public',	'DIRECCION', ''),
			array ('public',	'TELEFONOS', ''),
			array ('public',	'CESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
			array ('system',	'(SELECT NOMBRE FROM EST_ESTATUS WHERE EST_ESTATUS.CESTATUS=ALUM_ALUMNOS.CESTATUS) as STATUS_NAME'),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F_NAC) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F_NAC) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F_NAC) AS FECHA_NAC"),
			array ('system',	"LPAD(CALUMNO,10,'0') AS CALUMNO"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM FECHA_CREADO) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FECHA_CREADO) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FECHA_CREADO) AS FECHA_REG"),
		);			
		$this->table2 = "ALUM_PLANTEL_EGRE";
		$this->tId2 = "CPLANTEL";
		$this->db2 = new data($this->table2, $this->tId2);
		$this->db2->fields = array (
			array ('system',	$this->tId2),
			array ('public',	'CALUMNO'),
			array ('public',	'PLANTEL'),
			array ('public',	'CPAIS'),
			array ('public',	'CESTADO'),
			array ('public',	'CCIUDAD'),
			array ('public',	'ESPECIALIDAD'),
			array ('public',	'TIPO'),
			array ('public',	'NO_PLANTEL'),
			array ('public',	'NO_INSCRIPCION'),
			array ('public',	'FECHA_CERTIFICACION'),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM FECHA_CERTIFICACION) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FECHA_CERTIFICACION) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FECHA_CERTIFICACION) AS FEC_CERTIFICACION"),
			array ('public',	'FECHA_EXP_TITULO'),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM FECHA_EXP_TITULO) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FECHA_EXP_TITULO) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FECHA_EXP_TITULO) AS FEC_EXP_TITULO"),
			array ('public',	'NO_REGISTRO'),
			array ('public',	'GRADUA_YEAR')
		);
		$this->table3 = "ALUM_DOCUMENTOS";
		$this->tId3 = "CDOCUMENTO";
		$this->db3 = new data($this->table3, $this->tId3);
		$this->db3->fields = array (
			array ('system',	$this->tId3, "''"),
			array ('public',	'DESCRIPCION'),
			array ('public',	'ESTATUS','1')
		);
		$this->table4 = "ALUM_DOCUMENTOS_ALUMNOS";
		$this->tId4 = "CALUMNO";
		$this->db4 = new data($this->table4, $this->tId4);
		$this->db4->fields = array (
			array ('public',	'CALUMNO'),
			array ('public',	'CDOCUMENTO')
		);
		$this->mod4t = "ALUM_ALUMNOS A FULL JOIN EST_FACTURA F ON A.CALUMNO=F.CALUMNO";
		$this->mod4i = "A.CEDULA";
		$this->mod4 = new data($this->mod4t, $this->mod4i);
		$this->mod4->fields = array (
			array ('system',	"LPAD(A.CALUMNO,10,'0') AS CODIGO"),
			array ('system',	"A.CEDULA"),
			array ('system',	"(A.APELLIDOS || ' ' || A.NOMBRES) AS NOMBRES"),
			//array ('system',	'CASE WHEN () THEN SUM(F.MONTO_NETO - F.MONTO_ABONADO) AS SALDO')
			array ('system',	"SUM(CASE WHEN (F.TIPO='FAC' AND F.ESTATUS='PROCESADA') THEN (F.MONTO_NETO - F.MONTO_ABONADO) ELSE 0 END) AS SALDO")
		);
	}
	//NUEVO ALUMNO
	public function new_alumnos($data,$docs,$plantel){
		//OBLIGO A LA CI CON LA MASCARA QUE NECESITO
		$ced=substr($data[0],0,1).str_pad(substr($data[0],1,9),9,'0',STR_PAD_LEFT);
		$response=array();
		$chec_ced = $this->db->getRecords("","CEDULA='$ced'");
		if(empty($chec_ced)){
			//LA CEDULA NO EXISTE PROCEDO A INSERTARLO
			//ASIGNO PUBLIC EL NUEVO CAMPO
			$this->db->fields[13]=array("0" => "public","1" => "USUARIO_CREADO");
			//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
			$data[]=$_SESSION['user_log'];
			$alum=$this->db->insertRecord($data);
			if(empty($alum)){
				//NO SE INSERTO
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al registrar el alumno";
			}else{
				$alm = $this->db->getRecords("CALUMNO","CEDULA='$ced'");
				$response['titulo']="OK";
				$alum_id=$alm[0]['CALUMNO'];
				for ($i=0;$i<count($docs);$i++){
					$ALUM_DOCUMENTOS[] = $alum_id;
					$ALUM_DOCUMENTOS[] = $docs[$i];
					$this->db4->insertRecord($ALUM_DOCUMENTOS);
					$ALUM_DOCUMENTOS = "";
				}
				if(!empty($plantel)){//SI EL ARRAY DE PLANTEL ESTA VACIO NO INSERTA NADA
					$pla=array();
					$pla[] = $alum_id;
					//ARMO UN ARRAY PASANDO EL ALUMNO Y LOS DATOS DEL PLANTEL
					foreach ($plantel as $key => $value){$pla[] = $value;}
					$this->db2->insertRecord($pla);
				}
			}
		}else{
			//LA CI EXISTE DEVUELVO ERROR
			$response['titulo']="ERROR";
			$response['texto']='La cedula <strong>'.$ced.'</strong> ya se encuentra registrada en el sistema!';
		}
		return $response;
	}
	//ACTUALIZO ALUMNO
	public function edit_alumnos($id,$data,$docs,$plantel){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->db->fields[13]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$alum=$this->db->updateRecord($id,$data);
		if(empty($alum)){
			//NO SE ACTUALIZO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al actualizar el alumno";
		}else{
			$this->db4->deleteRecords("CALUMNO='$id'");//BORRO TODO LOS DOCS DEL ALUMNO
			if(!empty($docs)){//SI UN DOC FUE SELECCIONADO ENTRA AL CICLO Y LO INSERTA
				for ($i=0;$i<count($docs);$i++){
					$ALUM_DOCUMENTOS[] = $id;
					$ALUM_DOCUMENTOS[] = $docs[$i];
					$this->db4->insertRecord($ALUM_DOCUMENTOS); 
					$ALUM_DOCUMENTOS = "";
				}
			}
			if(!empty($plantel)){//SI EL ARRAY DE PLANTEL ESTA VACIO NO INSERTA NADA
				$this->db2->insertUpdate($plantel,'CALUMNO');
			}
			$response['titulo']="OK";
		}
		return $response;
	}
	//OBTIENE LOS ALUMNOS DE LA BD
	public function get_alumnos(){
		$alm = $this->db->getRecords();
		if(empty($alm)){
			return false;
		}else{
			return $alm;
		}
	}
	//BUSCA UNA CI DE ALUMNO EN LA BD
	public function get_ced($ced,$code){
		$ced=strtoupper(substr($ced,0,1).str_pad(substr($ced,1,9),9,'0',STR_PAD_LEFT));
		if($code>0){
			$filter="CEDULA='$ced' AND CALUMNO <> '$code'";
		}else{
			$filter="CEDULA='$ced'";
		}
		$alm = $this->db->getRecords(false,$filter);
		if(empty($alm)){
			return false;
		}else{
			return $alm;
		}
	}
	//OBTIENE UN ALUMNO
	public function get_alumno($id){
		$result="";
		$alm = $this->db->getRecord($id);
		if(empty($alm)){
			$result=false;
		}else{
			$result[0]=$alm;
			$doc_alum = $this->db4->getRecords(false,"CALUMNO='$id'");
			$pla_alum = $this->db2->getRecords(false,"CALUMNO='$id'");
			if(!empty($doc_alum)){
				$result[1] = $doc_alum;
			}
			if(!empty($pla_alum)){
				$result[2] = $pla_alum;
			}
		}
		return $result;
	}
	//LISTA LOS ALUM_DOCUMENTOS QUE ESTEN ACTIVOS
	public function list_docs(){
		$docs = $this->db3->getRecords(false,"ESTATUS='1'");
		if(empty($docs)){
			return false;
		}else{
			return $docs;
		}
	}
	//LISTA ALUMNOS ACTIVOS
	public function list_alumnos(){
		$alm = $this->db->getRecords(false,"CESTATUS='1'");
		if(empty($alm)){
			return false;
		}else{
			return $alm;
		}
	}
	public function list_alum_sald(){
		$response = $this->mod4->getRecords(false,"A.CESTATUS='1' GROUP BY 1,2,3");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
}
?>