<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_cuentas.php");
include_once("./clases/class_bancos.php");
include_once("./clases/functions.php");
$perm = new permisos();
$classe = new cuentas;
$classe1 = new bancos;
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->assign("obj",'cue');
	$bancos=$classe1->list_bank('1');
	if($action==''){
		$tpl->assign("accion",'save_new');
		$tpl->assign("form_title",'CREAR UNA CUENTA');
		$tpl->assign("form_subtitle",'LAS CUENTAS PERMITEN CONTROLAR LOS TIPOS DE PAGOS (UTILIZADOS EN EL MODULO DE PAGOS / CAJA)');
		foreach ($array_cuenta as $key => $value){
			$tpl->newBlock("cuenta_det");
			$tpl->assign("code",$key);
			$tpl->assign("tipo",$value);
		}
		if(!empty($bancos)){
			foreach ($bancos as $llave => $datos) {
				$tpl->newBlock("bancos_det");
				foreach ($bancos[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
		}
	}elseif($action=='edt'){
		$tpl->assign("accion",'save_edt');
		$tpl->assign("form_title",'EDITAR UNA CUENTA');
		$tpl->assign("form_subtitle",'ACTUALICE LOS DATOS DE LA CUENTA');
		$tpl->assign("code",$_GET['id']);
		$data=$classe->get_count($_GET['id']);
		if(!empty($data)){
			foreach ($data as $llave => $datos){
				foreach ($data[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
				foreach ($array_cuenta as $key => $value){
					$tpl->newBlock("cuenta_det");
					$tpl->assign("code",$key);
					$tpl->assign("tipo",$value);
					if($datos['TIPO'] == $key){
						$tpl->assign("selected",$selected);
					}
				}
				if(!empty($bancos)){
					foreach ($bancos as $llave1 => $datos1) {
						$tpl->newBlock("bancos_det");
						if($datos['CBANCO'] == $datos1['CODIGO']){
							$tpl->assign("selected",$selected);
						}
						foreach ($bancos[$llave1] as $key => $value){
							$tpl->assign($key,$value);
						}
					}
				}
				//ACTIVO STATUS QUE ESTA BLOQUEADO EN CREACION Y MUESTRO SU VALOR
				$tpl->newBlock("st_block");
				foreach ($array_status1 as $key => $value){
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
			$tpl->assign("USUARIO_CREADO",$data[0]['USUARIO_CREADO']);
			$tpl->assign("USUARIO_MODIFICACION",$data[0]['USUARIO_MODIFICACION']);
		}
	}
}
?>