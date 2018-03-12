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
	$tpl->assign("form_title",'ANULAR UN COBRO');
	$tpl->assign("form_subtitle",'UTILICE ESTE MODULO PARA ANULAR UN COBRO A UN ALUMNO <strong>(EL COBRO NO PUEDE HABER SIDO PAGADO)</strong>');
	if($action==''){
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
		}
		array_push($datos, strtoupper($_cedula_n));
		array_push($datos, strtoupper(date_to_mysql($_f_fact_c)));
		array_push($datos, strtoupper($_Periodos_c));
		array_push($datos, strtoupper('DEV'));
		array_push($datos, strtoupper('PROCESADA'));
		array_push($datos, strtoupper($total_bruto));//BRUTO
		array_push($datos, strtoupper(0));//IMP
		array_push($datos, strtoupper(0));//DESC
		array_push($datos, strtoupper($total_bruto));//NETO
		array_push($datos, strtoupper(0));//ABONADO
		array_push($datos, strtoupper($_Factura_c));//ORIGEN
		array_push($detalles, ($_cconcepto));
		array_push($detalles, ($_cant));
		array_push($detalles, ($_precio));
		array_push($detalles, ($_total));
		array_push($detalles, ($_cfactura_det));//ORIGEN_DET
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
			//LE ABONO EL SALDO DE LA DEV A LA FAC ORIGEN
			$fac=$cobros->get_fac($_Factura_c,'FAC');
			$factura=array();
			array_push($factura, $fac[0]['CALUMNO']);
			array_push($factura, $fac[0]['FFACTURA']);
			array_push($factura, $fac[0]['CPERIODO']);
			array_push($factura, $fac[0]['TIPO']);
			array_push($factura, 'ANULADA');
			array_push($factura, $fac[0]['MONTO_BRUTO']);
			array_push($factura, $fac[0]['MONTO_IMP']);
			array_push($factura, $fac[0]['MONTO_DESC']);
			array_push($factura, $fac[0]['MONTO_NETO']);
			array_push($factura, $total_bruto);
			array_push($factura, $fac[0]['CORIGEN']);
			$cobros->edit_fac($_Factura_c,'FAC',$factura);

			$mensaje="";
			$mensaje="DEVOLUCION GENERADA CON EXITO!";
			$action = "block";
			$new_mov=$response['texto'];
			$tpl->assign("accion",$action);
			$tpl->assign("cfactura",$new_mov);
			$tpl->assign("mensaje",$mensaje);
			$tpl->assign("inv_mensaje_class","alert-success");
			$con_mov=$cobros->get_fac($new_mov,'DEV');
			$con_mov_det=$cobros->get_fac_det($new_mov,'DEV');
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