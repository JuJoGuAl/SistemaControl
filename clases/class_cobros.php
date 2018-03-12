<?php
class cobros{
	//FACTURA
	private $mod;
	public $modt;
	public $modi;
	//FACTURA_DET
	private $mod2;
	public $mod2t;
	public $mod2i;
	//SALDOS_ALUMNOS
	private $mod3;
	public $mod3t;
	public $mod3i;
	//FACTURAS_ALUMNOS
	private $mod4;
	public $mod4t;
	public $mod4i;
	//TRANSACCIONES_COBROS
	private $mod5;
	public $mod5t;
	public $mod5i;
	public function __construct(){
		include_once('class_bd.php');		
		$this->modt = "EST_FACTURA";
		$this->modi = "CFACTURA";
		$this->mod = new data($this->modt, $this->modi);
		$this->mod->fields = array (
			//array ('system',	$this->modi),
			array ('public',	'CALUMNO'),
			array ('public',	'FFACTURA'),
			array ('public',	'CPERIODO'),
			array ('public',	'TIPO'),
			array ('public',	'ESTATUS'),
			array ('public',	'MONTO_BRUTO'),
			array ('public',	'MONTO_IMP'),
			array ('public',	'MONTO_DESC'),
			array ('public',	'MONTO_NETO'),
			array ('public',	'MONTO_ABONADO'),
			array ('public',	'CORIGEN'),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM FFACTURA) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FFACTURA) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FFACTURA) AS FECHA_FACTURA"),
			array ('system',	"(SELECT (F_PER || '-' || TPERIODO) FROM EST_PERIODO P WHERE P.CPERIODO=EST_FACTURA.CPERIODO) AS PERIODO"),
			array ('system',	"(SELECT APELLIDOS || ' ' || NOMBRES FROM ALUM_ALUMNOS A WHERE A.CALUMNO=EST_FACTURA.CALUMNO) AS ALUMNO"),
			array ('system',	"(SELECT CEDULA FROM ALUM_ALUMNOS A WHERE A.CALUMNO=EST_FACTURA.CALUMNO) AS CEDULA"),
			array ('system',	"(SELECT SUBSTRING(100 + EXTRACT(DAY FROM FFACTURA) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FFACTURA) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FFACTURA) FROM EST_FACTURA F WHERE F.CFACTURA=EST_FACTURA.CORIGEN AND F.TIPO='DEV') AS FECHA_ORIGEN"),
			array ('system',	"LPAD(CFACTURA,10,'0') AS CODIGO"),
			array ('system',	"LPAD(CORIGEN,10,'0') AS CODIGO_ORIGEN"),
			array ('system',	"(SELECT COUNT(*) FROM EST_FACTURA_DET FD WHERE FD.CFACTURA=EST_FACTURA.CFACTURA) AS CONCEPTOS"),
		);
		$this->mod2t = "EST_FACTURA_DET";
		$this->mod2i = "CFACTURA_DET";
		$this->mod2 = new data($this->mod2t, $this->mod2i);
		$this->mod2->fields = array (
			array ('system',	$this->mod2i),
			array ('system',	"LPAD(CFACTURA,10,'0') AS CODIGO"),
			array ('public',	'TIPO'),
			array ('public',	'CCONCEPTO'),
			array ('public',	'CANTIDAD'),
			array ('public',	'PRECIO'),
			array ('public',	'TOTAL'),
			array ('public',	'CORIGEN_DET'),
			array ('system',	"(SELECT CONCEPTO FROM EST_CONCEPTO C WHERE C.CCONCEPTO=EST_FACTURA_DET.CCONCEPTO) AS CONCEPTO")
		);
		//ESTA TABLA ES UN INNER JOIN Y TODOS SUS CAMPOS SON SYSTEM, LO QUE SIGNIFICA QUE LA UTILIZARE PARA LEER
		// Y NUNCA ESCRIBIRE SOBRE ELLA (SOLO SELECT)
		$this->mod3t = "EST_FACTURA F ";
		$this->mod3t .= "INNER JOIN ALUM_ALUMNOS A ON F.CALUMNO=A.CALUMNO";
		$this->mod3i = "A.CEDULA";
		$this->mod3 = new data($this->mod3t, $this->mod3i);
		$this->mod3->fields = array (
			array ('system',	"LPAD(A.CALUMNO,10,'0') AS CODIGO"),
			array ('system',	"A.CEDULA"),
			array ('system',	"(A.APELLIDOS || ' ' || A.NOMBRES) AS NOMBRES"),
			array ('system',	"SUM(CASE WHEN (F.TIPO='FAC' AND F.ESTATUS='PROCESADA') THEN (F.MONTO_NETO - F.MONTO_ABONADO) ELSE 0 END) AS SALDO")
		);
		$this->mod4t = "EST_FACTURA F ";
		$this->mod4t .= "INNER JOIN ALUM_ALUMNOS A ON F.CALUMNO=A.CALUMNO INNER JOIN EST_PERIODO E ON F.CPERIODO=E.CPERIODO";
		$this->mod4i = "A.CEDULA";
		$this->mod4 = new data($this->mod4t, $this->mod4i);
		$this->mod4->fields = array (
			array ('system',	"LPAD(F.CFACTURA,10,'0') AS CODIGO"),
			array ('system',	"LPAD(E.CPERIODO,10,'0') AS CPERIODO"),
			array ('system',	"(E.F_PER || '-' || E.TPERIODO) AS PERIODO"),
			array ('system',	'(F.MONTO_NETO) AS MONTO'),
			array ('system',	'(F.MONTO_NETO - F.MONTO_ABONADO) AS SALDO')
		);
		$this->mod5t = "EST_FACTURA F INNER JOIN EST_FACTURA_DET FD ON F.CFACTURA=FD.CFACTURA AND F.TIPO=FD.TIPO INNER JOIN ALUM_ALUMNOS A ON F.CALUMNO=A.CALUMNO";
		$this->mod5t .= " INNER JOIN EST_CONCEPTO C ON FD.CCONCEPTO=C.CCONCEPTO INNER JOIN EST_PERIODO P ON F.CPERIODO=P.CPERIODO";
		$this->mod5i = "F.CFACTURA";
		$this->mod5 = new data($this->mod5t, $this->mod5i);
		$this->mod5->fields = array (
			array ('system',	"LPAD(F.CFACTURA,10,'0') AS CFACTURA"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM F.FFACTURA) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM F.FFACTURA) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM F.FFACTURA) AS FECHA_FACTURA"),
			array ('system',	"((P.F_PER || '-' || P.TPERIODO)) AS PERIODO"),
			array ('system',	"(A.APELLIDOS || ' ' || A.NOMBRES) AS CLIENTE"),
			array ('system',	'(F.ESTATUS) AS ESTATUS'),
			array ('system',	"(CEDULA) AS CEDULA"),
			array ('system',	'(C.CONCEPTO) AS CONCEPTO'),
			array ('system',	'(FD.CANTIDAD) AS CANTIDAD'),
			array ('system',	'(FD.PRECIO) AS PRECIO'),
			array ('system',	'(FD.TOTAL) AS TOTAL')
		);
	}
	//INSERTA
	public function new_fac($data,$dets){
		//OBTENGO UN ID PARA INSERTAR
		//PASO COMO PARAMETRO EL TIPO DE DOCUMENTO
		$id=$this->mod->getGen("CORRELATIVO_CFACTURA_".$data[3]);
		//ASIGNO LOS CAMPOS MANUALMENTE
		$this->mod->fields[20]=array("0" => "public","1" => "USUARIO_CREADO");
		$data[]=$_SESSION['user_log'];
		$this->mod->fields[21]=array("0" => "public","1" => "CFACTURA");
		$data[]=$id;
		$result=$this->mod->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al registrar la Cabecera de la Factura";
		}else{
			//CUENTO CADA VEZ QUE SE INGRESO UN CONCEPTO
			for ($i=0;$i<count($dets[0]);$i++){
				$detalles = array();    
				$detalles[]=$data[3];
				$detalles[]=$dets[0][$i];
				$detalles[]=$dets[1][$i];
				$detalles[]=$dets[2][$i];
				$detalles[]=$dets[3][$i];
				$detalles[]=$dets[4][$i];
				//ASIGNO LOS CAMPOS MANUALMENTE
				$this->mod2->fields[10]=array("0" => "public","1" => "USUARIO_CREADO");
				$detalles[]=$_SESSION['user_log'];
				$this->mod2->fields[11]=array("0" => "public","1" => "CFACTURA");
				$detalles[]=$id;
				$result=$this->mod2->insertRecord($detalles);
			}
			if(empty($result)){
				//NO SE TOCO
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al Registrar los Detalles de la Factura";
			}else{
				$response['titulo']="OK";
				$response['texto']=$id;
			}
		}
		return $response;
	}
	//ACTUALIZA
	public function edit_fac($id,$tipo,$data){
		//ASIGNO LOS CAMPOS MANUALMENTE
		$this->mod->fields[20]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		$data[]=$_SESSION['user_log'];
		$where="CFACTURA = '$id' AND TIPO = '$tipo'";
		$result=$this->mod->updateRecord(1,$data,$where);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al Actualizar la Cabecera de la Factura";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	public function get_fac($id,$tipo){
		$response = $this->mod->getRecords(false,"CFACTURA='$id' AND TIPO='$tipo'");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function get_fac_det($id,$tipo){
		$response = $this->mod2->getRecords(false,"CFACTURA='$id' AND TIPO='$tipo'");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function list_fac($tipo,$status=false,$saldo=false){
		$st = $status ? "AND ESTATUS='$status'"  : "";
		$abo = $saldo ? "AND MONTO_ABONADO=0"  : "";
		$response = $this->mod->getRecords(false,"TIPO='$tipo' $st $abo");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function list_saldo(){
		//$response = $this->mod3->getRecords(false,"F.TIPO='FAC' AND F.ESTATUS='PROCESADA' AND (F.MONTO_NETO-F.MONTO_ABONADO)<>0 GROUP BY 1,2,3");
		$response = $this->mod3->getRecords(false,"A.CALUMNO>0 GROUP BY 1,2,3");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function list_facts($id){
		$response = $this->mod4->getRecords(false,"F.TIPO='FAC' AND F.ESTATUS='PROCESADA' AND (F.MONTO_NETO-F.MONTO_ABONADO)<>0 AND A.CALUMNO='$id'");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function transacciones($alu=false,$per=false,$f1=false,$f2=false){
		$falu = ($alu) ? "AND A.CALUMNO='$alu'" : "" ;
		$fper = ($per) ? "AND P.CPERIODO='$per'" : "" ;
		$ff1 = ($f1) ? "AND F.FFACTURA BETWEEN '$f1' AND '$f2'" : "" ;
		$response = $this->mod5->getRecords(false,"F.CFACTURA > 0 $falu $fper $ff1","1");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
}
?>