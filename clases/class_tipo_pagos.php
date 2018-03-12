<?php
class pagos{	
	//TIPOS_PAGOS
	private $mod;
	public $table;
	public $Id;
	//CUENTAS
	private $mod1;
	public $table1;
	public $Id1;

	public function __construct(){
		include_once('class_bd.php');
		$this->table = "EST_TIPO_PAGO";
		$this->tId = "CTIPO_PAGO";
		$this->mod = new data($this->table, $this->tId);
		$this->mod->fields = array (
			array ('public',    'TIPO_PAGO'),
			array ('public',    'CCUENTA'),
			array ('public',	'ESTATUS'),
			array ('system',	'USUARIO_CREADO'),
			array ('system',	'USUARIO_MODIFICACION'),
			array ('system',	"LPAD(CTIPO_PAGO,10,'0') AS CODIGO"),
			array ('system',	"(SELECT C.CUENTA FROM EST_CUENTA C WHERE C.CCUENTA=EST_TIPO_PAGO.CCUENTA) AS CUENTA"),
			array ('system',	"(SELECT B.BANCO FROM EST_BANCO B INNER JOIN EST_CUENTA C ON B.CBANCO=C.CBANCO WHERE C.CCUENTA=EST_TIPO_PAGO.CCUENTA) AS BANCO")
		);
		$this->table1 = "EST_CUENTA C INNER JOIN EST_BANCO B ON C.CBANCO=B.CBANCO";
		$this->tId1 = "C.CCUENTA";
		$this->mod1 = new data($this->table1, $this->tId1);
		$this->mod1->fields = array (
			array ('system',	"LPAD(C.CCUENTA,10,'0') AS CODIGO_CUENTA"),
			array ('system',    "(CASE WHEN (TIPO='C') THEN 'CORRIENTE' ELSE CASE WHEN (TIPO='A') THEN 'AHORRO' ELSE '-' END END) || ' - ' || (B.BANCO) AS TIPO"),
		);
	}
	//INSERTA
	public function new_pagos($data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->mod->fields[9]=array("0" => "public","1" => "USUARIO_CREADO");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->mod->insertRecord($data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL REGISTRAR EL TIPO DE PAGO";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//ACTUALIZA
	public function edit_pagos($id,$data){
		//ASIGNO PUBLIC EL NUEVO CAMPO
		$this->mod->fields[9]=array("0" => "public","1" => "USUARIO_MODIFICACION");
		//PASO EL VALOR MANUALMENTE DEL ULTIMO CAMPO AGREGADO
		$data[]=$_SESSION['user_log'];
		$result=$this->mod->updateRecord($id,$data);
		if(empty($result)){
			//NO SE TOCO
			$response['titulo']="ERROR";
			$response['texto']="OCURRIO UN ERROR AL ACTUALIZAR EL TIPO DE PAGO";
		}else{
			$response['titulo']="OK";
		}
		return $response;
	}
	//LISTA
	public function list_pagos($status=false,$array=false,$bank=false){
		$st = ($status) ? "AND ESTATUS='$status'" : "" ;
		$ar = ($array) ? "AND CCUENTA NOT IN ($array)" : "" ;
		$bk = ($bank) ? "AND CBANCO = '$bank'" : "" ;
		$result = $this->mod->getRecords(false,"CCUENTA > 0 $st $ar $bk");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//LISTA
	public function list_cuentas(){
		$result = $this->mod1->getRecords(false,"C.ESTATUS='1' AND B.ESTATUS='1'");
		if(empty($result)){
			return false;
		}else{
			return $result;
		}
	}
	//OBTIENE UN REGISTRO
	public function get_pagos($id){
		$result = $this->mod->getRecord($id);
		return $result;
	}
}
?>