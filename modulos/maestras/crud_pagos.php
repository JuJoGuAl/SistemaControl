<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_tipo_pagos.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$classe = new pagos;
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_POST['accion'])?$_POST['accion']:'');
	if($action==''){
		$tpl->newBlock("CRUD");
		foreach ($modulo as $key => $value){
			$tpl->assign("mod_name",$value['MODULO']);
		}
		$data=$classe->list_pagos();
		if(!empty($data)){
			foreach ($data as $llave => $datos) {
				$tpl->newBlock("DATA");
				$sts = ($datos['ESTATUS']=="1") ? 'ACTIVO' : 'INACTIVO';
				$tpl->assign("STATUS",$sts);
				$id=$datos['CODIGO'];
				$cadena_acciones='<div class="tooltip-acciones">
				<button type="button" class="btn btn-default btn-circle modal-btn" data-action="edt" data-id="'.$id.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDITAR TIPO DE PAGO" href="form_pagos" data-toggle="modal"><i class="fa fa-edit"></i></button>
				</div>
				';
				foreach ($data[$llave] as $key => $value){
					$row++;
					$row_clas = ($row%2==0)?'odd':'even';
					$tpl->assign("class",$row_clas);
					$tpl->assign($key,$value);
					$tpl->assign("actions",$cadena_acciones);
				}
			}
		}
	}
}
?>