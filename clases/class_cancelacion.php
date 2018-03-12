<?php
class cancelacion{
	//CANCELACION
	private $mod;
	public $modt;
	public $modi;
	//CANCELACION_DET
	private $mod2;
	public $mod2t;
	public $mod2i;
	//CANCELACION_FAC
	private $mod3;
	public $mod3t;
	public $mod3i;
	//CANCELACION_RECIBO
	private $mod4;
	public $mod4t;
	public $mod4i;
	//TRANSACCIONES_PAGOS
	private $mod5;
	public $mod5t;
	public $mod5i;
	public function __construct(){
		include_once('class_bd.php');		
		$this->modt = "EST_CANCELACION";
		$this->modi = "CCANCELACION";
		$this->mod = new data($this->modt, $this->modi);
		$this->mod->fields = array (
			array ('public',	'MONTO'),
			array ('public',	'FCANCELACION'),
			array ('public',	'CCLIENTE'),
			array ('public',	'ESTATUS'),
			array ('system',	"LPAD(CCANCELACION,10,'0') AS CODIGO"),
			array ('system',	"LPAD(CCLIENTE,10,'0') AS CODE_CLIENTE"),
			array ('system',	"(SELECT APELLIDOS || ' ' || NOMBRES FROM ALUM_ALUMNOS A WHERE A.CALUMNO=EST_CANCELACION.CCLIENTE) AS CLIENTE"),
			array ('system',	"(SELECT CEDULA FROM ALUM_ALUMNOS A WHERE A.CALUMNO=EST_CANCELACION.CCLIENTE) AS CEDULA"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM FCANCELACION) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FCANCELACION) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FCANCELACION) AS FECHA_CANCELACION"),
			array ('system',	"(MONTO) AS MONTO_CANCELACION"),
			array ('system',	"(SELECT COUNT(*) FROM EST_CANCELACION_FAC CF WHERE CF.CCANCELACION=EST_CANCELACION.CCANCELACION) AS FACTURAS"),
			array ('system',	'(USUARIO_CREADO) AS UCREA'),
			array ('system',	'(USUARIO_MODIFICACION) AS UMOD'),
			array ('system',	'(FECHA_CREADO) AS FCREA'),
			array ('system',	'(FECHA_MODIFICACION) AS FMOD')
		);
		$this->mod2t = "EST_CANCELACION_DET";
		$this->mod2i = "CCANCELACION_DET";
		$this->mod2 = new data($this->mod2t, $this->mod2i);
		$this->mod2->fields = array (
			array ('system',	"LPAD(CCANCELACION,10,'0') AS CODIGO"),
			array ('public',	'MONTO'),
			array ('public',	'CTIPO'),
			array ('public',	'DOCUMENTO'),
			array ('public',	'FECHA_PAGO'),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM FECHA_PAGO) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FECHA_PAGO) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FECHA_PAGO) AS FECHA_DET"),
			array ('system',	"(SELECT TIPO_PAGO FROM EST_TIPO_PAGO P WHERE P.CTIPO_PAGO=EST_CANCELACION_DET.CTIPO) AS TIPO"),
			array ('system',	"(MONTO) AS MONTO_PAGO")
		);
		$this->mod3t = "EST_CANCELACION_FAC";
		$this->mod3i = "CCANCELACION_FAC";
		$this->mod3 = new data($this->mod3t, $this->mod3i);
		$this->mod3->fields = array (
			array ('system',	"LPAD(CCANCELACION,10,'0') AS CODIGO"),
			array ('public',	'CFACTURA'),
			array ('public',	'MONTO'),
			array ('system',	"(MONTO) AS MONTO_FACTURA"),
			array ('system',	"LPAD(CFACTURA,10,'0') AS CODE_FACTURA"),
			array ('system',	"(SELECT (F_PER || '-' || TPERIODO) FROM EST_PERIODO P INNER JOIN EST_FACTURA F ON P.CPERIODO=F.CPERIODO AND F.TIPO='FAC' WHERE F.CFACTURA=EST_CANCELACION_FAC.CFACTURA) AS PERIODO"),
			array ('system',	"(SELECT MONTO_NETO FROM EST_FACTURA F WHERE F.CFACTURA=EST_CANCELACION_FAC.CFACTURA AND F.TIPO='FAC') AS NETO_FACTURA"),
			array ('system',	"(SELECT SUM(F.MONTO_NETO - F.MONTO_ABONADO) FROM EST_FACTURA F WHERE F.CFACTURA=EST_CANCELACION_FAC.CFACTURA AND F.TIPO='FAC') AS SALDO_FACTURA"),
			array ('system',	"(SELECT SUBSTRING(100 + EXTRACT(DAY FROM FFACTURA) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM FFACTURA) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM FFACTURA) FROM EST_FACTURA F  WHERE F.CFACTURA=EST_CANCELACION_FAC.CFACTURA AND F.TIPO='FAC') AS FECHA_FACTURA")
		);
		$this->mod4t = "EST_CANCELACION C INNER JOIN EST_CANCELACION_DET CD ON C.CCANCELACION=CD.CCANCELACION INNER JOIN EST_TIPO_PAGO TP ON CD.CTIPO=TP.CTIPO_PAGO";
		$this->mod4i = "C.CCANCELACION";
		$this->mod4 = new data($this->mod4t, $this->mod4i);
		$this->mod4->fields = array (
			array ('system',	"LPAD(C.CCANCELACION,10,'0') AS CODIGO"),
			array ('system',	"(C.MONTO) AS MONTO_TOTAL"),
			array ('system',	"(C.ESTATUS) AS ESTATUS"),
			array ('system',	"(CD.MONTO) AS MONTO"),
			array ('system',	"LPAD(CD.CTIPO,10,'0') AS CTIPO"),
			array ('system',	"(TP.TIPO_PAGO) AS TIPO_PAGO"),
			array ('system',	"(CD.DOCUMENTO) AS DOCUMENTO"),
			array ('system',	"(CD.FECHA_PAGO) AS FECHA")
		);
		$this->mod5t = "EST_CANCELACION C INNER JOIN ALUM_ALUMNOS A ON C.CCLIENTE=A.CALUMNO INNER JOIN EST_CANCELACION_DET CD ON C.CCANCELACION=CD.CCANCELACION";
		$this->mod5t .= " INNER JOIN EST_TIPO_PAGO TP ON CD.CTIPO=TP.CTIPO_PAGO INNER JOIN EST_CUENTA CP ON TP.CCUENTA=CP.CCUENTA INNER JOIN EST_BANCO B ON CP.CBANCO=B.CBANCO";
		$this->mod5i = "C.CCANCELACION";
		$this->mod5 = new data($this->mod5t, $this->mod5i);
		$this->mod5->fields = array (
			array ('system',	"LPAD(C.CCANCELACION,10,'0') AS CCANCELACION"),
			array ('system',	"SUBSTRING(100 + EXTRACT(DAY FROM C.FCANCELACION) FROM 2 FOR 2) || '/' || SUBSTRING(100 + EXTRACT(MONTH FROM C.FCANCELACION) FROM 2 FOR 2) || '/' || EXTRACT(YEAR FROM C.FCANCELACION) AS FECHA_CANCELACION"),
			array ('system',	"(B.BANCO) AS BANCO"),
			array ('system',	"(A.APELLIDOS || ' ' || A.NOMBRES) AS CLIENTE"),
			array ('system',	"(A.CEDULA) AS CEDULA"),
			array ('system',	"(C.ESTATUS) AS ESTATUS"),
			//array ('system',	"(C.MONTO) AS MONTO_TOTAL"),
			//array ('system',	"(CD.MONTO) AS MONTO"),
			//array ('system',	"LPAD(CD.CTIPO,10,'0') AS CTIPO"),
			//array ('system',	"(TP.TIPO_PAGO) AS TIPO_PAGO"),
			//array ('system',	"(CD.DOCUMENTO) AS DOCUMENTO"),
			//array ('system',	"(CD.FECHA_PAGO) AS FECHA"),
			array ('system',	"(SUM(CD.MONTO)) AS MONTO")
		);
	}
	//INSERTA
	public function new_canc($data,$dets,$facs){
		//OBTENGO UN ID PARA INSERTAR
		//PASO COMO PARAMETRO EL TIPO DE DOCUMENTO
		$id=$this->mod->getGen("CORRELATIVO_CCANCELACION");
		//ASIGNO LOS CAMPOS MANUALMENTE
		$this->mod->fields[16]=array("0" => "public","1" => "USUARIO_CREADO");
		$data[]=$_SESSION['user_log'];
		$this->mod->fields[17]=array("0" => "public","1" => "CCANCELACION");
		$data[]=$id;
		$result=$this->mod->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al registrar la Cabecera de la Cancelacion";
		}else{
			//CUENTO CADA VEZ QUE SE INGRESO UNA FACTURA
			for ($i=0;$i<count($dets[0]);$i++){ 
				$detalles = array();    
				$detalles[]=$dets[0][$i];
				$detalles[]=$dets[1][$i];
				$detalles[]=$dets[2][$i];
				$detalles[]=$dets[3][$i];
				//ASIGNO LOS CAMPOS MANUALMENTE
				$this->mod2->fields[9]=array("0" => "public","1" => "USUARIO_CREADO");
				$detalles[]=$_SESSION['user_log'];
				$this->mod2->fields[10]=array("0" => "public","1" => "CCANCELACION");
				$detalles[]=$id;
				$result=$this->mod2->insertRecord($detalles);
			}
			if(empty($result)){
				//NO SE TOCO
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al Registrar los Detalles de la Cancelacion";
			}else{
				//CUENTO CADA VEZ QUE SE INGRESO UNA FORMA DE PAGO
				for ($i=0;$i<count($facs[0]);$i++){ 
					$formas = array();
					$monto=$facs[1][$i];
					$factura=$facs[0][$i];
					$formas[]=$factura;
					$formas[]=$monto;
					//ASIGNO LOS CAMPOS MANUALMENTE
					$this->mod3->fields[9]=array("0" => "public","1" => "USUARIO_CREADO");
					$formas[]=$_SESSION['user_log'];
					$user=$_SESSION['user_log'];
					$this->mod3->fields[10]=array("0" => "public","1" => "CCANCELACION");
					$formas[]=$id;
					$result=$this->mod3->insertRecord($formas);
					$this->mod->query("UPDATE EST_FACTURA SET MONTO_ABONADO=MONTO_ABONADO+$monto,USUARIO_MODIFICACION='$user' WHERE CFACTURA='$factura' AND TIPO='FAC' RETURNING CFACTURA");
				}
				if(empty($result)){
					//NO SE TOCO
					$response['titulo']="ERROR";
					$response['texto']="Ocurrió un error al Registrar los Detalles de Pago";
				}else{
					$response['titulo']="OK";
					$response['texto']=$id;
				}
			}
		}
		return $response;
	}
	//ANULA
	public function dev_canc($id){
		$result=$this->mod->query("SELECT CFACTURA, MONTO FROM EST_CANCELACION_FAC WHERE CCANCELACION='$id'");
		$user=$_SESSION['user_log'];
		foreach ($result as $key => $value){
			$monto=$value['MONTO'];
			$fac=$value['CFACTURA'];
			$result=$this->mod->query("UPDATE EST_FACTURA SET MONTO_ABONADO=MONTO_ABONADO-$monto, USUARIO_MODIFICACION='$user' WHERE CFACTURA='$fac' AND TIPO='FAC'RETURNING CFACTURA");
		}
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="Ocurrió un error al Devolver saldo a la(s) Factura(s)";
		}else{
			$result=$this->mod->query("UPDATE EST_CANCELACION SET ESTATUS='ANULADO', USUARIO_MODIFICACION='$user' WHERE CCANCELACION='$id' RETURNING CCANCELACION");
			if(empty($result)){
				$response['titulo']="ERROR";
				$response['texto']="Ocurrió un error al Actualizar la Cancelación";
			}else{
				$response['titulo']="OK";
				$response['texto']=$id;
			}
		}
		return $response;
	}
	//OBTIENE UNA CANCELACION
	public function get_canc($id){
		$result="";
		$cab = $this->mod->getRecord($id);
		if(empty($cab)){
			$result=false;
		}else{
			$result['cab']=$cab;
			$det = $this->mod2->getRecords(false,"CCANCELACION='$id'");
			$fac = $this->mod3->getRecords(false,"CCANCELACION='$id'");
			if(!empty($det)){
				$result['det'] = $det;
			}
			if(!empty($fac)){
				$result['fac'] = $fac;
			}
		}
		return $result;
	}
	public function list_canc($status=false){
		$st = $status ? "ESTATUS='$status'":"";
		$response = $this->mod->getRecords(false,$st);
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
	public function cierre_caja($fecha){
		$response['cab'] = $this->mod4->getRecords("DISTINCT(LPAD(C.CCANCELACION,10,'0')) AS CODIGO,(C.MONTO) AS MONTO_TOTAL,(C.ESTATUS) AS ESTATUS","C.FCANCELACION='$fecha'");
		//$response['cab'] = $this->mod->getRecords(false,"FCANCELACION='$fecha'");
		if(empty($response['cab'])){
			return false;
		}else{
			foreach ($response['cab'] as $key => $value){
				$codigos[]=$value['CODIGO'];
			}
			$codigos="(".implode(",",$codigos).")";
			$response['det'] = $this->mod4->getRecords(false,"C.CCANCELACION IN $codigos");
			return $response;
		}
	}
	public function transacciones($alu=false,$ban=false,$f1=false,$f2=false){
		$falu = ($alu) ? "AND A.CALUMNO='$alu'" : "" ;
		$fban = ($ban) ? "AND B.CBANCO='$ban'" : "" ;
		$ff1 = ($f1) ? "AND C.FCANCELACION BETWEEN '$f1' AND '$f2'" : "" ;
		$response = $this->mod5->getRecords(false,"C.CCANCELACION > 0 $falu $fban $ff1 GROUP BY 1,2,3,4,5,6","1");
		if(empty($response)){
			return false;
		}else{
			return $response;
		}
	}
}
?>