<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_periodos.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$periodos = new periodos;
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_POST['accion'])?$_POST['accion']:'');
	if($action==''){
		$tpl->newBlock("crud_periodos");
		foreach ($modulo as $key => $value){
			$tpl->assign("mod_name",$value['MODULO']);
		}
		$data_periodos=$periodos->get_pers();
		if(!empty($data_periodos)){
			foreach ($data_periodos as $llave => $datos) {
				$tpl->newBlock("periodos_data");
				$id=$datos['CPERIODO'];
				$cadena_acciones='<div class="tooltip-tool">
				<button type="button" class="btn btn-default btn-circle modal-btn" data-action="edt" data-id="'.$id.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="EDITAR PERIODO" href="form_periodos" data-toggle="modal"><i class="fa fa-edit"></i></button>
				</div>
				';
				foreach ($data_periodos[$llave] as $key => $value){
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