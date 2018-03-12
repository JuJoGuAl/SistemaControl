<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_materias.php");
include_once("./clases/class_carreras.php");
include_once("./clases/functions.php");
$perm = new permisos();
$EST_MATERIAS = new EST_MATERIAS();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$row_clas="";
	$row=0;
	$carrera = new carreras;
	$modulo = $perm->get_module($_GET['submod']);
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$data_carrera=$carrera->get_cars();
	$tpl->assign("obj",'mat');
	if($action==''){
		$tpl->assign("accion",'save_new');
		$tpl->assign("form_title",'CREAR UNA MATERIA');
		$tpl->assign("form_subtitle",'UTILICE EL FORMULARIO PARA CREAR UNA MATERIA Y ASIGNARLE CARRERAS');
	}elseif($action=='edt'){
		$tpl->assign("accion",'save_edt');
		$tpl->assign("form_title",'EDITAR UNA MATERIA');
		$tpl->assign("form_subtitle",'ACTUALICE LOS DATOS DE LA MATERIA');
		$tpl->assign("id",$_GET['id']);
		$materias=$EST_MATERIAS->get_mat($_GET['id']);
		$data_materia=$materias[0];
		$mats_cars=@$materias[1];
		if(!empty($data_materia)){
			foreach ($data_materia as $llave => $datos){
				foreach ($data_materia[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
				//ACTIVO STATUS QUE ESTA BLOQUEADO EN CREACION Y MUESTRO SU VALOR
				$tpl->newBlock("st_block");
				foreach ($array_status as $key => $value){
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
			$tpl->assign("USUARIO_CREADO",$data_materia[0]['USUARIO_CREADO']);
			$tpl->assign("USUARIO_MODIFICACION",$data_materia[0]['USUARIO_MODIFICACION']);
		}
	}
}
?>