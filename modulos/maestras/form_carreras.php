<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_carreras.php");
include_once("./clases/functions.php");
$perm = new permisos();
$carreras = new carreras();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->assign("obj",'car');
	if($action==''){
		$tpl->assign("accion",'save_new');
		$tpl->assign("form_title",'CREAR UNA CARRERA');
		$tpl->assign("form_subtitle",'UTILICE EL FORMULARIO PARA CREAR UNA CARRERA');
	}elseif($action=='edt'){
		$tpl->assign("accion",'save_edt');
		$tpl->assign("form_title",'EDITAR UNA CARRERA');
		$tpl->assign("form_subtitle",'ACTUALICE LOS DATOS DE LA CARRERA');
		$tpl->assign("id",$_GET['id']);
		$data_carrera=$carreras->get_car($_GET['id']);
		if(!empty($data_carrera)){
			foreach ($data_carrera as $llave => $datos){
				foreach ($data_carrera[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
				//ACTIVO STATUS QUE ESTA BLOQUEADO EN CREACION Y MUESTRO SU VALOR
				$tpl->newBlock("st_block");
				foreach ($array_status as $key => $value){
					$tpl->newBlock("st_det");
					$tpl->assign("st_code",$key);
					$tpl->assign("st_name",$value);
					if($datos['ESTATUS'] == $key){
						$tpl->assign("selected",$selected);
					}
				}
			}
			//ACTIVA LA INFORMACION DE AUDITORIA
			$tpl->newBlock("det_datos");
			$tpl->assign("USUARIO_CREADO",$data_carrera[0]['USUARIO_CREADO']);
			$tpl->assign("USUARIO_MODIFICACION",$data_carrera[0]['USUARIO_MODIFICACION']);
		}
	}
}
?>