<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_inscripciones.php");
include_once("./clases/functions.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$inscripcion = new inscripcion();	
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->newBlock("form");
	$tpl->assign("form_title",'REGISTRO DE NOTAS');
	$tpl->assign("form_subtitle",'UTILICE LOS FILTROS PARA OBTENER LAS MATERIAS / ALUMNOS A CALIFICAR');
	if($action==''){
		$tpl->assign("accion",'save_new');
	}elseif($action=="save_new"){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, $_ins_det);
		array_push($datos, $_not);
		array_push($datos, $_prof);
		$response=$inscripcion->nota_ins($datos);
		if($response['titulo']=="ERROR"){
			//VERIFICAR SI MUESTRO EL ERROR SQL O MUESTRO UN ERROR GENERAL
			$tpl->assign("mensaje",$response['texto']);
			$tpl->assign("mensaje_class","alert-danger");
		}else{
			$mensaje="NOTAS CARGADAS CON EXITO!";
			$tpl->assign("mensaje",$mensaje);
			$tpl->assign("mensaje_class","alert-success");
		}
		$tpl->newBlock("mensaje_log");
	}
}
?>