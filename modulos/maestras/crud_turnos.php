<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_turnos.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$turnos = new turnos;
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_POST['accion'])?$_POST['accion']:'');
	if($action==''){
		$tpl->newBlock("crud_turnos");
		foreach ($modulo as $key => $value){
			$tpl->assign("mod_name",$value['MODULO']);
		}
		$data_turnos=$turnos->get_turnos();
		if(!empty($data_turnos)){
			foreach ($data_turnos as $llave => $datos) {
				$tpl->newBlock("turnos_data");
				$id=$datos['CTURNO'];
				$sts = ($datos['ESTATUS']=="1") ? 'ACTIVO' : 'INACTIVO';
				$tpl->assign("STATUS",$sts);
				$cadena_acciones='<div class="tooltip-tool">
				<button type="button" class="btn btn-default btn-circle modal-btn" data-action="edt" data-id="'.$id.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDITAR TURNO" href="form_turnos" data-toggle="modal"><i class="fa fa-edit"></i></button>
				</div>
				';
				foreach ($data_turnos[$llave] as $key => $value){
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