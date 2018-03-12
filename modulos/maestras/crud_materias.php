<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_materias.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$materia = new EST_MATERIAS;
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_POST['accion'])?$_POST['accion']:'');
	if($action==''){
		$tpl->newBlock("crud_EST_MATERIAS");
		foreach ($modulo as $key => $value){
			$tpl->assign("mod_name",$value['MODULO']);
		}
		$data_EST_MATERIAS=$materia->get_mats();
		if(!empty($data_EST_MATERIAS)){
			foreach ($data_EST_MATERIAS as $llave => $datos) {
				$tpl->newBlock("data_EST_MATERIAS");
				$sts_car = ($datos['ESTATUS']=="1") ? 'ACTIVA' : 'INACTIVA';
				$tpl->assign("STATUS",$sts_car);
				$id=$datos['CMATERIA'];
				$cadena_acciones='<div class="tooltip-acciones_mat">
				<button type="button" class="btn btn-default btn-circle modal-btn" data-action="edt" data-id="'.$id.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDITAR MATERIA" href="form_materias" data-toggle="modal"><i class="fa fa-edit"></i></button>
				<button type="button" class="btn btn-default btn-circle modal-btn" data-action="edt" data-id="'.$id.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="CARRERAS POR MATERIA" href="crud_materias_carreras" data-toggle="modal"><i class="fa fa-list-alt"></i></button>
				</div>
				';
				foreach ($data_EST_MATERIAS[$llave] as $key => $value){
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