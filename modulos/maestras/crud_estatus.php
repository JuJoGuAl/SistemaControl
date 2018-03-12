<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_estatus.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$estatus = new estatus;
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_POST['accion'])?$_POST['accion']:'');
	if($action==''){
		$tpl->newBlock("crud_estatus");
		foreach ($modulo as $key => $value){
			$tpl->assign("mod_name",$value['MODULO']);
		}
		$data_estatus=$estatus->get_status();
		if(!empty($data_estatus)){
			foreach ($data_estatus as $llave => $datos) {
				$tpl->newBlock("estatus_data");
				$st_clasif = ($datos['CLASE']=="A") ? 'ALUMNOS' : 'PROFESORES';
				$tpl->assign("CLASIF",$st_clasif);
				$id_sts=$datos['CESTATUS'];
				$cadena_acciones='<div class="tooltip-status">
				<button type="button" class="btn btn-default btn-circle modal-btn" data-action="edt" data-id="'.$id_sts.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDITAR ESTATUS" href="form_estatus" data-toggle="modal"><i class="fa fa-edit"></i></button>
				</div>
				';
				foreach ($data_estatus[$llave] as $key => $value){
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