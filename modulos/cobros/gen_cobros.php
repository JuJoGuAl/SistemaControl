<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_cobros.php");
include_once("./clases/functions.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$cobros = new cobros();
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->newBlock("form");
	$tpl->assign("form_title",'GENERAR UN COBRO');
	$tpl->assign("form_subtitle",'GENERE UN COBRO A UN ALUMNO UTILIZANDO ESTE MODULO');
	if($action==''){
		//REVISAR GMT
		$tpl->assign("FECHA_FACTURA",date('d/m/Y'));
		$tpl->assign("accion",'save_new');
		$tpl->assign("ESTATUS",'PENDIENTE');
	}elseif($action=="save_new"){
		$datos=array();
		$detalles=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		$total_bruto=0;
		for ($i=0; $i<sizeof($_POST['cconcepto']); $i++){
			$total_bruto=$total_bruto+$_total[$i];
			$det[$i]=0;
		}
		array_push($datos, strtoupper($_cedula_n));
		array_push($datos, strtoupper(date_to_mysql($_f_fact_c)));
		array_push($datos, strtoupper($_Periodos_c));
		array_push($datos, strtoupper('FAC'));
		array_push($datos, strtoupper('PROCESADA'));
		array_push($datos, strtoupper($total_bruto));//BRUTO
		array_push($datos, strtoupper(0));//IMP
		array_push($datos, strtoupper(0));//DESC
		array_push($datos, strtoupper($total_bruto));//NETO
		array_push($datos, strtoupper(0));//ABONADO
		array_push($datos, strtoupper(0));//ORIGEN
		array_push($detalles, ($_cconcepto));
		array_push($detalles, ($_cant));
		array_push($detalles, ($_precio));
		array_push($detalles, ($_total));
		array_push($detalles, ($det));//ORIGEN_DET
		$response=$cobros->new_fac($datos,$detalles);
		if($response['titulo']=="ERROR"){
			$tpl->assign("FECHA_FACTURA",date('d/m/Y'));
			$tpl->assign("accion",'save_new');
			$tpl->assign("ESTATUS",'PENDIENTE');
			//VERIFICAR SI MUESTRO EL ERROR SQL O MUESTRO UN ERROR GENERAL
			$tpl->assign("mensaje",$response['texto']);
			$tpl->assign("inv_mensaje_class","alert-danger");
			$tpl->newBlock("mensaje_log");
		}else{
			$mensaje="";
			$mensaje="COBRO GENERADO CON EXITO!";
			$action = "block";
			$new_mov=$response['texto'];
			$tpl->assign("accion",$action);
			$tpl->assign("cfactura",$new_mov);
			$tpl->assign("mensaje",$mensaje);
			$tpl->assign("inv_mensaje_class","alert-success");
			$con_mov=$cobros->get_fac($new_mov,'FAC');
			$con_mov_det=$cobros->get_fac_det($new_mov,'FAC');
			foreach ($con_mov as $key => $value){
				foreach ($con_mov[$key] as $key1 => $value1){
					$tpl->assign($key1,$value1);
				}
			}
			foreach ($con_mov_det as $key => $value){
				$tpl->newBlock("cobro_det");
				foreach ($con_mov_det[$key] as $key1 => $value1){
					$tpl->assign($key1,$value1);
				}
			}
			$tpl->newBlock("mensaje_log");
		}
	}
}
?>