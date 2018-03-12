<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_carreras.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$carrera = new carreras;
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_POST['accion'])?$_POST['accion']:'');
	if($action==''){
		$tpl->newBlock("crud_carrera");
		foreach ($modulo as $key => $value){
			$tpl->assign("mod_name",$value['MODULO']);
		}
		$data_carrera=$carrera->get_cars();
		if(!empty($data_carrera)){
			foreach ($data_carrera as $llave => $datos) {
				$tpl->newBlock("data_carrera");
				$sts_car = ($datos['ESTATUS']=="1") ? 'ACTIVA' : 'INACTIVA';
				$tpl->assign("STATUS",$sts_car);
				$id=$datos['CCARRERA'];
				$cadena_acciones='<div class="tooltip-carreras">
				<button type="button" class="btn btn-default btn-circle modal-btn" data-action="edt" data-id="'.$id.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDITAR CARRERA" href="form_carreras" data-toggle="modal"><i class="fa fa-edit"></i></button>
				</div>
				';
				foreach ($data_carrera[$llave] as $key => $value){
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