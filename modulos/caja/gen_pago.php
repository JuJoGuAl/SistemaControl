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
	$tpl->assign("form_title",'RECIBO DE PAGOS');
	$tpl->assign("form_subtitle",'UTILICE ESTE MODULO PARA PAGAR LOS COBROS REALIZADOS A LOS ALUMNOS');
	if($action==''){
		$tpl->assign("FECHA_CANCELACION",date('d/m/Y'));
		$tpl->assign("ESTATUS",'PENDIENTE');
		$tpl->assign("accion",'save_new');
	}elseif($action=="save_new"){
		$datos=array();
		$detalles=array();
		$pagos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		$total_bruto=0;
		for ($i=0; $i<sizeof($_POST['abono']); $i++){
			$total_bruto=$total_bruto+$_abono[$i];
		}
		for ($i=0; $i<sizeof($_POST['pago']); $i++){
			$fecha[$i]=date_to_mysql($_fecha[$i]);
		}
		array_push($datos, $total_bruto);
		array_push($datos, date_to_mysql($_f_can_c));
		array_push($datos, $_Cliente_c);
		array_push($datos, 'PROCESADO');
		array_push($pagos, $_pago);
		array_push($pagos, $_tpago);
		array_push($pagos, $_doc);
		array_push($pagos, $fecha);
		array_push($detalles, $_cfactura);
		array_push($detalles, $_abono);
		$response=$cancelacion->new_canc($datos,$pagos,$detalles);
		if($response['titulo']=="ERROR"){
			$tpl->assign("FECHA_CANCELACION",date('d/m/Y'));
			$tpl->assign("ESTATUS",'PENDIENTE');
			$tpl->assign("accion",'save_new');
			//VERIFICAR SI MUESTRO EL ERROR SQL O MUESTRO UN ERROR GENERAL
			$tpl->assign("mensaje",$response['texto']);
			$tpl->assign("mensaje_class","alert-danger");
			$tpl->newBlock("mensaje_log");
		}else{
			$mensaje="COBRO REALIZADO CON EXITO!";
			$action = "block";
			$new_mov=$response['texto'];
			$tpl->assign("accion",$action);
			$tpl->assign("id",$new_mov);
			$tpl->assign("mensaje",$mensaje);
			$tpl->assign("mensaje_class","alert-success");
			$data=$cancelacion->get_canc($new_mov);
			//echo $data['cab'][0]['CODIGO'];
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