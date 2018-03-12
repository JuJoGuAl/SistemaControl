<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_alumnos.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$alumnos = new alumnos;
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_POST['accion'])?$_POST['accion']:'');
	if($action==''){
		$tpl->newBlock("crud_alumno");
		foreach ($modulo as $key => $value){
			$tpl->assign("mod_name",$value['MODULO']);
		}
		$data_alumnos=$alumnos->get_alumnos();
		if(!empty($data_alumnos)){
			foreach ($data_alumnos as $llave => $datos) {
				$tpl->newBlock("alumnos_data");
				$icon_sex = ($datos['SEXO']=="M") ? 'fa-male' : 'fa-female';
				$span_sex = '<span class="fa '.$icon_sex.'" style="font-size: 25px;"></span>';
				$tpl->assign("sex",$span_sex);
				$id_alum=$datos['CALUMNO'];
				$cadena_acciones='<div class="tooltip-alumnos">
				<button type="button" class="btn btn-default btn-circle modal-btn" data-action="edt" data-id="'.$id_alum.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDITAR ALUMNO" href="form_alumno" data-toggle="modal"><i class="fa fa-edit"></i></button>
				</div>
				';
				foreach ($data_alumnos[$llave] as $key => $value){
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