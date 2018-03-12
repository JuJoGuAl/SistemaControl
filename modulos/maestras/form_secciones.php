<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_secciones.php");
include_once("./clases/class_carreras.php");
include_once("./clases/functions.php");
$perm = new permisos();
$secciones = new secciones();
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
	$tpl->assign("obj",'sec');
	$cars=$carreras->get_cars(1);
	if($action==''){
		$tpl->assign("accion",'save_new');
		$tpl->assign("form_title",'CREAR UNA SECCION');
		$tpl->assign("form_subtitle",'LAS SECCIONES PERMITEN MANTENER CUPOS POR CLASES Y ASIGNARLES MATERIAS PARA QUE ESTAS UTILICEN LOS CUPOS DE LAS SECCIONES');
		if(!empty($cars)){
			foreach ($cars as $llave => $datos) {
				$tpl->newBlock("carreras_det");
				foreach ($cars[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
		}
	}elseif($action=='edt'){
		$tpl->assign("accion",'save_edt');
		$tpl->assign("form_title",'EDITAR UNA SECCION');
		$tpl->assign("form_subtitle",'ACTUALICE LOS DATOS DE LA SECCION');
		$tpl->assign("id",$_GET['id']);
		$data_secciones=$secciones->get_seccion($_GET['id']);
		if(!empty($data_secciones)){
			foreach ($data_secciones as $llave => $datos){
				foreach ($data_secciones[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
			if(!empty($cars)){
				foreach ($cars as $llave1 => $datos1) {
					$tpl->newBlock("carreras_det");
					if($datos['CCARRERA'] == $datos1['CCARRERA']){
						$tpl->assign("selected",$selected);
					}
					foreach ($cars[$llave1] as $key => $value){
						$tpl->assign($key,$value);
					}
				}
			}
			//ACTIVA LA INFORMACION DE AUDITORIA
			$tpl->newBlock("det_datos");
			$tpl->assign("USUARIO_CREADO",$data_secciones[0]['USUARIO_CREADO']);
			$tpl->assign("USUARIO_MODIFICACION",$data_secciones[0]['USUARIO_MODIFICACION']);
		}
	}
}
?>