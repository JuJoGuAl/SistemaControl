<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_cobros.php");
include_once("./clases/class_cancelacion.php");
include_once("./clases/functions.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$cobros = new cobros();
	$cancelacion = new cancelacion();
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->newBlock("form");
	$tpl->assign("form_title",'ANULAR RECIBO DE PAGOS');
	$tpl->assign("form_subtitle",'UTILICE ESTE MODULO PARA ANULAR LOS PAGOS REALIZADOS ANTERIORMENTE, <STRONG>ESTA ACCION DEVOLVERA EL SALDO A LAS FACTURAS</STRONG>');
	if($action==''){
		$tpl->assign("ESTATUS",'PENDIENTE');
		$tpl->assign("accion",'save_new');
	}elseif($action=="save_new"){
		extract($_POST, EXTR_PREFIX_ALL, "");
		$response=$cancelacion->dev_canc($_Recibo_c);
		if($response['titulo']=="ERROR"){
			$tpl->assign("ESTATUS",'PENDIENTE');
			$tpl->assign("accion",'save_new');
			//VERIFICAR SI MUESTRO EL ERROR SQL O MUESTRO UN ERROR GENERAL
			$tpl->assign("mensaje",$response['texto']);
			$tpl->assign("mensaje_class","alert-danger");
			$tpl->newBlock("mensaje_log");
		}else{
			$mensaje="ANULACIÓN EXITOSA!";
			$action = "block";
			$new_mov=$response['texto'];
			$tpl->assign("accion",$action);
			$tpl->assign("id",$new_mov);
			$tpl->assign("mensaje",$mensaje);
			$tpl->assign("mensaje_class","alert-success");
			$data=$cancelacion->get_canc($new_mov);
			$cabecera=$data['cab'];
			$detalle=$data['det'];
			$factura=$data['fac'];
			foreach ($cabecera as $key => $value){
				foreach ($cabecera[$key] as $key1 => $value1){
					$tpl->assign($key1,$value1);
				}
			}
			foreach ($factura as $key => $value){
				$tpl->newBlock("can_fac");
				foreach ($factura[$key] as $key1 => $value1){
					$tpl->assign($key1,$value1);
				}
			}
			foreach ($detalle as $key => $value){
				$tpl->newBlock("can_det");
				foreach ($detalle[$key] as $key1 => $value1){
					$tpl->assign($key1,$value1);
				}
			}
			$tpl->newBlock("mensaje_log");
		}
	}
}
?>