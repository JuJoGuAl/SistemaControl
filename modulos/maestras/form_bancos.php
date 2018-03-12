<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_bancos.php");
include_once("./clases/functions.php");
$perm = new permisos();
$bancos = new bancos;
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->assign("obj",'ban');
	if($action==''){
		$tpl->assign("accion",'save_new');
		$tpl->assign("form_title",'CREAR UN BANCO');
		$tpl->assign("form_subtitle",'UTILICE LOS BANCOS PARA GESTIONAR LAS CUENTAS DONDE SE RECIBIRAN LOS PAGOS.');
	}elseif($action=='edt'){
		$tpl->assign("accion",'save_edt');
		$tpl->assign("form_title",'EDITAR UN BANCO');
		$tpl->assign("form_subtitle",'ACTUALICE LOS DATOS DEL BANCO');
		$tpl->assign("code",$_GET['id']);
		$data=$bancos->get_bank($_GET['id']);
		if(!empty($data)){
			foreach ($data as $llave => $datos){
				foreach ($data[$llave] as $key => $value){
					$tpl->assign($key,$value);
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