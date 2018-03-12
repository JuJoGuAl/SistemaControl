<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_estatus.php");
$perm = new permisos();
$estatus = new estatus();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->assign("obj",'sts');
	if($action==''){
		$tpl->assign("accion",'save_new');
		$tpl->assign("form_title",'CREAR UN ESTATUS');
		$tpl->assign("form_subtitle",'LOS ESTATUS SON UTILIZADOS PARA CLASIFICAR A LOS ALUMNOS Y PROFESORES');
		foreach ($array_clase as $key => $value){
			$tpl->newBlock("clase_det");
			$tpl->assign("clase_cod",$key);
			$tpl->assign("clase_name",$value);
		}
	}elseif($action=='edt'){
		$tpl->assign("accion",'save_edt');
		$tpl->assign("form_title",'EDITAR UN ESTATUS');
		$tpl->assign("form_subtitle",'ACTUALICE LOS DATOS DEL ESTATUS');
		$tpl->assign("id",$_GET['id']);
		$data_estatus=$estatus->get_statu($_GET['id']);
		if(!empty($data_estatus)){
			foreach ($data_estatus as $llave => $datos){
				foreach ($data_estatus[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
				foreach ($array_clase as $key => $value){
					$tpl->newBlock("clase_det");
					$tpl->assign("clase_cod",$key);
					$tpl->assign("clase_name",$value);
					if($datos['CLASE'] == $key){
						$tpl->assign("selected",$selected);
					}
				}
			}
			//ACTIVA LA INFORMACION DE AUDITORIA
			$tpl->newBlock("det_datos");
			$tpl->assign("USUARIO_CREADO",$data_estatus[0]['USUARIO_CREADO']);
			$tpl->assign("USUARIO_MODIFICACION",$data_estatus[0]['USUARIO_MODIFICACION']);
		}
	}
}
?>