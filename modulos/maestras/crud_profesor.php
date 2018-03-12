<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_profesores.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$profesores = new profesores;
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_POST['accion'])?$_POST['accion']:'');
	if($action==''){
		$tpl->newBlock("crud_profesor");
		foreach ($modulo as $key => $value){
			$tpl->assign("mod_name",$value['MODULO']);
		}
		$data_profesor=$profesores->list_profesors();
		if(!empty($data_profesor)){
			foreach ($data_profesor as $llave => $datos) {
				$tpl->newBlock("data_profesores");
				$icon_sex = ($datos['SEXO']=="M") ? 'fa-male' : 'fa-female';
				$span_sex = '<span class="fa '.$icon_sex.'" style="font-size: 25px;"></span>';
				$tpl->assign("sex",$span_sex);
				$id_prof=$datos['CPROFESOR'];
				$cadena_acciones='<div class="tooltip-profesores">
				<button type="button" class="btn btn-default btn-circle modal-btn" data-action="edt" data-id="'.$id_prof.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDITAR PROFESOR" href="form_profesor" data-toggle="modal"><i class="fa fa-edit"></i></button>
				</div>
				';
				foreach ($data_profesor[$llave] as $key => $value){
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