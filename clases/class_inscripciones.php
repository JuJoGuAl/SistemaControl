<?php
class inscripcion{
	//INSCRIPCION
	private $ins;
	public $inst;
	public $insi;
	//INSCRIPCION_DET
	private $insd;
	public $insdt;
	public $insdi;
	//INSCRIPCION_VALIDACION
	private $insv;
	public $insvt;
	public $insvi;
	//INSCRIPCION_VALIDACION2
	private $mod;
	public $modt;
	public $modi;
	//INSCRIPCION_REPORTE
	private $mod1;
	public $modt1;
	public $modi1;
	//INSCRIPCION_NOTAS
	private $mod2;
	public $modt2;
	public $modi2;
	public function __construct(){
		include_once('class_bd.php');		
		$this->inst = "EST_INSCRIPCION";
		$this->insi = "CINSCRIPCION";
		$this->ins = new data($this->inst, $this->insi);
		$this->ins->fields = array (
			array ('public',	'CCARRERA'),
			array ('public',	'CPERIODO'),
			array ('public',	'CALUMNO'),
			array ('public',	'FINSCRIPCION'),
			array ('public',	'CCONVENIO'),
			array ('system',	"LPAD(CINSCRIPCION,10,'0') AS CODIGO"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM FINSCRIPCION) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FINSCRIPCION) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FINSCRIPCION) AS FECHA_INS"),
			array ('system',	"(SELECT (F_PER || '-' || TPERIODO) FROM EST_PERIODO P WHERE P.CPERIODO=EST_INSCRIPCION.CPERIODO) AS PERIODO"),
			array ('system',	"(SELECT CONVENIO FROM EST_CONVENIO C WHERE C.CCONVENIO=EST_INSCRIPCION.CCONVENIO) AS CONVENIO"),
			array ('system',	"(SELECT APELLIDOS || ' ' || NOMBRES FROM ALUM_ALUMNOS A WHERE A.CALUMNO=EST_INSCRIPCION.CALUMNO) AS ALUMNO"),
			array ('system',	"(SELECT CEDULA FROM ALUM_ALUMNOS A WHERE A.CALUMNO=EST_INSCRIPCION.CALUMNO) AS CEDULA"),
			array ('system',	"(SELECT DESCRIPCION FROM EST_CARRERAS WHERE CCARRERA=EST_INSCRIPCION.CCARRERA) AS CARRERA"),
			array ('system',	"(SELECT SUM(M.UC) FROM EST_INSCRIPCION_DET ID INNER JOIN EST_MAT_CAR MC ON ID.CMATE=MC.CMATE INNER JOIN EST_MATERIAS M ON MC.CMATERIA=M.CMATERIA WHERE ID.CINSCRIPCION=EST_INSCRIPCION.CINSCRIPCION AND MC.CCARRERA=EST_INSCRIPCION.CCARRERA ) AS UCS"),
			array ('system',	"(SELECT COUNT(*) FROM EST_INSCRIPCION_DET ID INNER JOIN EST_MAT_CAR MC ON ID.CMATE=MC.CMATE INNER JOIN EST_MATERIAS M ON MC.CMATERIA=M.CMATERIA WHERE ID.CINSCRIPCION=EST_INSCRIPCION.CINSCRIPCION AND MC.CCARRERA=EST_INSCRIPCION.CCARRERA ) AS MATS"),
			array ('system',	'(USUARIO_CREADO) AS UCREA'),
			array ('system',	'(USUARIO_MODIFICACION) AS UMOD'),
			array ('system',	'(FECHA_CREADO) AS FCREA'),
			array ('system',	'(FECHA_MODIFICACION) AS FMOD')
		);
		$this->insdt = "EST_INSCRIPCION_DET";
		$this->insdi = "CINSCRIPCION_DET";
		$this->insd = new data($this->insdt, $this->insdi);
		$this->insd->fields = array (
			array ('public',	'CMATE'),
			array ('public',	'CSECCION'),
			array ('public',	'NOTA'),
			array ('system',	"LPAD(CINSCRIPCION,10,'0') AS CODIGO"),
			array ('system',	"LPAD(CINSCRIPCION_DET,10,'0') AS CODIGO_DET"),
			array ('system',	"(SELECT CODIGO FROM EST_MATERIAS M INNER JOIN EST_MAT_CAR MC ON M.CMATERIA=MC.CMATERIA WHERE MC.CMATE=EST_INSCRIPCION_DET.CMATE) AS MATE_CODIGO"),
			array ('system',	"(SELECT DESCRIPCION FROM EST_MATERIAS M INNER JOIN EST_MAT_CAR MC ON M.CMATERIA=MC.CMATERIA WHERE MC.CMATE=EST_INSCRIPCION_DET.CMATE) AS MATE_NOMBRE"),
			array ('system',	"(SELECT UC FROM EST_MATERIAS M INNER JOIN EST_MAT_CAR MC ON M.CMATERIA=MC.CMATERIA WHERE MC.CMATE=EST_INSCRIPCION_DET.CMATE) AS MATE_UC"),
			array ('system',	"(SELECT SEMESTRE FROM EST_MAT_CAR MC WHERE MC.CMATE=EST_INSCRIPCION_DET.CMATE) AS MATE_SEM"),
			array ('system',	"(SELECT SECCION FROM EST_SECCION S WHERE S.CSECCION=EST_INSCRIPCION_DET.CSECCION) AS MATE_SEC")
		);
		$this->instv = "EST_CARRERAS C";
		$this->insiv = "C.CCARRERA";
		$this->insv = new data($this->instv, $this->insiv);
		$this->insv->fields = array (
			array ('system',	"LPAD(C.CCARRERA,10,'0') AS CCARRERA"),
			array ('system',	'C.CODIGO'),
			array ('system',	'C.DESCRIPCION'),
			array ('system',	'C.ESTATUS')
		);
		$this->modt = "EST_MAT_CAR MC INNER JOIN EST_MATERIAS M ON MC.CMATERIA=M.CMATERIA INNER JOIN EST_CARRERAS C ON MC.CCARRERA=C.CCARRERA";
		$this->modi = "C.CCARRERA";
		$this->mod = new data($this->modt, $this->modi);
		$this->mod->fields = array (
			array ('system',	"LPAD(MC.CMATE,10,'0') AS MATE_CODE"),
			array ('system',	"LPAD(M.CMATERIA,10,'0') AS MAT_CODE"),
			array ('system',	'M.CODIGO AS MAT_COD'),
			array ('system',	'M.DESCRIPCION AS MAT_NAME'),
			array ('system',	'M.UC AS MAT_UC'),
			array ('system',	'MC.SEMESTRE AS MAT_SEM'),
			array ('system',	'(SELECT VALOR FROM ADM_PARAMETRO WHERE CPARAMETRO=5) AS MAX_UC')
		);
		$this->modt1 = "EST_INSCRIPCION I INNER JOIN EST_INSCRIPCION_DET ID ON I.CINSCRIPCION=ID.CINSCRIPCION INNER JOIN EST_CARRERAS C ON I.CCARRERA=C.CCARRERA";
		$this->modt1 .= " INNER JOIN EST_PERIODO P ON I.CPERIODO=P.CPERIODO INNER JOIN ALUM_ALUMNOS A ON I.CALUMNO=A.CALUMNO FULL JOIN EST_CONVENIO CO ON I.CCONVENIO=CO.CCONVENIO";
		$this->modt1 .= " INNER JOIN EST_SECCION S ON ID.CSECCION=S.CSECCION INNER JOIN EST_MAT_CAR MC ON ID.CMATE=MC.CMATE INNER JOIN EST_MATERIAS M ON MC.CMATERIA=M.CMATERIA";
		$this->modt1 .= " FULL JOIN EST_MAT_CAR_PROF MCP ON ID.CMATE=MCP.CMATE AND ID.CSECCION=MCP.CSECCION FULL JOIN PROF_PROFESORES PR ON MCP.CPROFESOR=PR.CPROFESOR";
		$this->modi1 = "ID.CINSCRIPCION_DET";
		$this->mod1 = new data($this->modt1, $this->modi1);
		$this->mod1->fields = array (
			array ('system',	"LPAD(I.CINSCRIPCION,10,'0') AS CINSCRIPCION"),
			array ('system',	"LPAD(A.CALUMNO,10,'0') AS CALUMNO"),
			array ('system',	"(A.APELLIDOS || ' ' || A.NOMBRES) AS ALUMNO"),
			array ('system',	"(A.CEDULA) AS CEDULA"),
			array ('system',	"(C.DESCRIPCION) AS CARRERA"),
			array ('system',	"(M.CODIGO) AS MATE_CODIGO"),
			array ('system',	"(M.DESCRIPCION) AS MATE_NOMBRE"),
			array ('system',	"(M.UC) AS MATE_UC"),
			array ('system',	"(MC.SEMESTRE) AS MATE_SEM"),
			array ('system',	"(S.SECCION) AS MATE_SEC"),
			array ('system',	"((P.F_PER || '-' || P.TPERIODO)) AS PERIODO")
		);
		$this->modt2 = "EST_INSCRIPCION_DET";
		$this->modi2 = "CINSCRIPCION_DET";
		$this->modt2 = new data($this->modt2, $this->modi2);
		$this->modt2->fields = array (
			array ('public',	'NOTA'),
			array ('public',	'CPROFESOR'),
			array ('system',	"LPAD(CINSCRIPCION_DET,10,'0') AS CODIGO_DET")
		);
	}
	//LISTA CARRERAS ACTIVAS
	public function list_cars(){
		$result = $this->insv->getRecords(false,"C.ESTATUS='1'");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//LISTA MATERIAS ACTIVAS DEL ALUMNO
	public function list_mat_alum($alum,$car,$array=false){
		$result = $this->insv->query("SELECT COUNT(*) AS META FROM EST_INSCRIPCION I WHERE I.CALUMNO = '$alum' AND I.CCARRERA= '$car'");
		//EL ALUMNO NO ES NUEVO, TIENE CARRERAS VISTAS
		if($result[0]['META']>0){
			$st = $array ? "AND MATE_CODE NOT IN ($array)":"";
			$materias = $this->insv->query("SELECT * FROM ALUM_MATERIAS_PENDIENTES('$alum','$car') WHERE MATE_CODE > 1 $st");
		}else{
			$st = $array ? "AND MC.CMATE NOT IN ($array)":"";
			$materias = $this->mod->getRecords(false,"C.ESTATUS='1' AND M.ESTATUS='1' AND MC.SEMESTRE='1' AND C.CCARRERA='$car' $st");
		}
		return $materias;
	}
	//LISTA MATERIAS ACTIVAS DE LA CARRERA
	public function list_mat_car($car,$array=false){
		$st = $array ? "AND MC.CMATE NOT IN ($array)":"";
		$materias = $this->mod->getRecords(false,"C.ESTATUS='1' AND M.ESTATUS='1' AND C.CCARRERA='$car' $st");
		return $materias;
	}
	//OBTENGO LA MATERIA SELECCIONADA
	public function get_mate($id){
		$result = $this->mod->getRecords(false,"MC.CMATE='$id'");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//INSERTA
	public function new_ins($data,$dets){
		//OBTENGO UN ID PARA INSERTAR
		//PASO COMO PARAMETRO EL TIPO DE DOCUMENTO
		$id=$this->ins->getGen("CORRELATIVO_CINSCRIPCION");
		//ASIGNO LOS CAMPOS MANUALMENTE
		$this->ins->fields[19]=array("0" => "public","1" => "USUARIO_CREADO");
		$data[]=$_SESSION['user_log'];
		$this->ins->fields[20]=array("0" => "public","1" => "CINSCRIPCION");
		$data[]=$id;
		$result=$this->ins->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL REGISTRAR LA CABECERA DE LA INSCRIPCION";
		}else{
			//CUENTO CADA VEZ QUE SE INGRESO UN CONCEPTO
			for ($i=0;$i<count($dets[0]);$i++){ 
				$detalles = array();    
				$detalles[]=$dets[0][$i];
				$detalles[]=$dets[1][$i];
				$detalles[]=0;// NOTA
				//ASIGNO LOS CAMPOS MANUALMENTE
				$this->insd->fields[10]=array("0" => "public","1" => "USUARIO_CREADO");
				$detalles[]=$_SESSION['user_log'];
				$this->insd->fields[11]=array("0" => "public","1" => "CINSCRIPCION");
				$detalles[]=$id;
				$result=$this->insd->insertRecord($detalles);
			}
			if(empty($result)){
				//NO SE TOCO
				$response['titulo']="ERROR";
				$response['texto']="OCURRIO UN ERROR AL REGISTRAR LOS DETALLES DE LA INSCRIPCION";
			}else{
				//LIMPIO EL HASH DE LA CLAVE
				$this->insd->query("UPDATE ADM_PARAMETRO SET VALOR='' WHERE CPARAMETRO='10' RETURNING CPARAMETRO");
				$response['titulo']="OK";
				$response['texto']=$id;
			}
		}
		return $response;
	}
	public function get_ins($id){
		$result="";
		$ins_c = $this->ins->getRecord($id);
		if(empty($ins_c)){
			$result=false;
		}else{
			$result['cab']=$ins_c;
			$ins_d = $this->insd->getRecords(false,"CINSCRIPCION='$id'");
			if(!empty($ins_d)){
				$result['det'] = $ins_d;
			}
		}
		return $result;
	}
	public function list_ins(){
		$response = $this->ins->getRecords();
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function check_ins($alum,$per,$car){
		$response = $this->ins->getRecords(false,"CALUMNO='$alum' AND CPERIODO='$per' AND CCARRERA='$car'");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function get_ins_alum($alum){
		$response = $this->mod1->getRecords(false,"A.CALUMNO='$alum'");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function get_ins_list($car,$sec,$mat){
		$fcar = ($car) ? "AND I.CCARRERA='$car'" : "" ;
		$fsec = ($sec) ? "AND ID.CSECCION='$sec'" : "" ;
		$fmat = ($mat) ? "AND MC.CMATERIA='$mat'" : "" ;
		$response = $this->mod1->getRecords("DISTINCT(LPAD(I.CINSCRIPCION,10,'0')) AS CINSCRIPCION,LPAD(A.CALUMNO,10,'0') AS CALUMNO,(A.APELLIDOS || ' ' || A.NOMBRES) AS ALUMNO,(A.CEDULA) AS CEDULA,((P.F_PER || '-' || P.TPERIODO)) AS PERIODO,(C.DESCRIPCION) AS CARRERA","I.CINSCRIPCION > 0 $fcar $fsec $fmat");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function list_alum_ins(){
		$response = $this->mod1->getRecords("DISTINCT(LPAD(A.CALUMNO,10,'0')) AS CODIGO,(A.APELLIDOS || ' ' || A.NOMBRES) AS NOMBRES,(A.CEDULA) AS CEDULA","I.CINSCRIPCION > 0");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function list_car_ins($alum){
		$falum = ($alum) ? "AND I.CALUMNO='$alum'" : "" ;
		$response = $this->mod1->getRecords("DISTINCT(LPAD(C.CCARRERA,10,'0')) AS CCARRERA, C.CODIGO AS CODIGO, C.DESCRIPCION AS DESCRIPCION","I.CINSCRIPCION > 0 $falum");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function list_mat_ins($car=false){
		$fcar = ($car) ? "AND I.CCARRERA='$car'" : "" ;
		$response = $this->mod1->getRecords("DISTINCT(LPAD(M.CMATERIA,10,'0')) AS CMATERIA, M.CODIGO AS CODIGO, M.DESCRIPCION AS DESCRIPCION","I.CINSCRIPCION > 0 $fcar");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function list_sec_ins($car=false){
		$fcar = ($car) ? "AND I.CCARRERA='$car'" : "" ;
		$response = $this->mod1->getRecords("DISTINCT(LPAD(S.CSECCION,10,'0')) AS CSECCION, S.SECCION AS DESCRIPCION","I.CINSCRIPCION > 0 $fcar");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function list_prof_ins(){
		$response = $this->mod1->getRecords("DISTINCT(LPAD(PR.CPROFESOR,10,'0')) AS CPROFESOR, PR.CEDULA AS CEDULA, (PR.APELLIDOS || ' ' || PR.NOMBRES) AS PROFESOR","I.CINSCRIPCION > 0 AND PR.CPROFESOR>0");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function search_ins_det($per=false,$alum=false,$car=false,$mat=false,$sec=false,$prof=false){
		$fper = ($per) ? "AND P.CPERIODO='$per'" : "" ;
		$falum = ($alum) ? "AND A.CALUMNO='$alum'" : "" ;
		$fcar = ($car) ? "AND C.CCARRERA='$car'" : "" ;
		$fmat = ($mat) ? "AND M.CMATERIA='$mat'" : "" ;
		$fsec = ($sec) ? "AND S.CSECCION='$sec'" : "" ;
		$fprof = ($prof) ? "AND PR.CPROFESOR='$prof'" : "" ;
		$response = $this->mod1->getRecords("DISTINCT(LPAD(ID.CINSCRIPCION_DET,10,'0')) AS CINSCRIPCION_DET,(A.APELLIDOS || ' ' || A.NOMBRES) AS ALUMNO,C.DESCRIPCION AS CARRERA,M.DESCRIPCION AS MATERIA,S.SECCION,ID.NOTA,MC.CMATE,S.CSECCION","I.CINSCRIPCION > 0 $fper $falum $fcar $fmat $fsec $fprof");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	//ASIGNA NOTAS
	public function nota_ins($data){
		//print_r($data[1]);
		for ($i=0;$i<count($data[0]);$i++){ 
			$detalles = array();    
			$id=$data[0][$i];
			$detalles[]=$data[1][$i];//NOTA
			$detalles[]=$data[2][$i];//PROFESOR
			//ASIGNO LOS CAMPOS MANUALMENTE
			$this->modt2->fields[4]=array("0" => "public","1" => "USUARIO_MODIFICACION");
			$detalles[]=$_SESSION['user_log'];
			$result=$this->modt2->updateRecord($id,$detalles);
		}
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL REGISTRAR LA NOTA DEL ALUMNO";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
}
?>