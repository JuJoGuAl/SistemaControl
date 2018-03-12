<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_materias.php");
include_once("./clases/class_carreras.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$mats = new EST_MATERIAS();
	$carrera = new carreras;
	$det_mat=$mats->get_mat($_GET['id']);
	$data_carrera=$carrera->get_cars();
	$tpl->assign("car_name",$det_mat[0][0]['DESCRIPCION']);
	$tpl->assign("mat_id",$det_mat[0][0]['CMATERIA']);
	$tpl->assign("obj",'mat');
	$tpl->assign("accion",'new_mat_car');
	$tpl->assign("accion2",'new_presla');
	$tpl->assign("form_subtitle",'A CONTINUACIÓN SE LISTAN LAS CARRERAS ATADAS A LA MATERIA, PUEDE AGREGAR, ACTUALIZAR O QUITAR');
	$tpl->assign("form_subtitle2",'EDITE O CREE REQUISITOS PARA LA MATERIA EN LA CARRERA, COMO PUEDE SER UN CO REQUISITO (HABER APROBADO UNA MATERIA) O UC (TENER ACUMULADAS UNA CANTIDAD DE UC APROBADAS)');
	$row_clas="";
	$row_clas2="";
	$row=0;
	$row2=0;
	$mats_cars=@$det_mat[1];
	if(!empty($mats_cars)){
		foreach ($mats_cars as $key => $val) {
			$tpl->newBlock("data_mats_cars");
			$id=$val['CMATE'];
			$id_car=$val['CCARRERA'];
			$sem=$val['SEMESTRE'];
			$row++;
			$row_clas = ($row%2==0)?'odd':'even';
			$cadena_acciones='<div class="tooltip-mats_cars">
			<a id="bt_presla_'.$id.'" class="btn btn-default btn-circle presla" data-sem="'.$sem.'" data-car="'.$id_car.'" data-id="'.$id.'" data-toggle2="tooltip" data-placement="top" title="" data-original-title="REQUISITOS" data-toggle="modal" data-target="#Modal_Preslaciones"><i class="fa fa-edit"></i></a>
			<button type="button" class="btn btn-default btn-circle borrar" data-action="del_car_mat" data-obj="mat" data-id="'.$id.'" href="modulos/maestras/modal.php" data-toggle2="tooltip" data-placement="top" title="" data-original-title="BORRAR CRUCE"><i class="fa fa-trash-o"></i></button>
			</div>
			';
			foreach ($data_carrera as $llave => $datos){
				foreach ($data_carrera[$llave] as $key2 => $value2) {
					if($data_carrera[$llave]['CCARRERA']==$mats_cars[$key]['CCARRERA']){
						$tpl->assign($key2,$value2);
					}
				}
			}
			foreach ($mats_cars[$key] as $key1 => $value1){
				$tpl->assign("class",$row_clas);
				$tpl->assign($key1,$value1);
				$tpl->assign("actions",$cadena_acciones);
			}
			//$mat_pres=$mats->get_mat_pres($_GET['id'],$mats_cars[$key]['CCARRERA']);
			/*if(!empty($mat_pres)){
				foreach ($mat_pres as $key2 => $val2){
					$tpl->newBlock("data_pres_mat");
					foreach ($mat_pres[$key2] as $key3 => $value3){
						$tpl->assign($key3,$value3);
						if($mat_pres[$key2]['CTIPO']==1){
							$tpl->assign('CANTIDAD','N/A');
						}else{
							$tpl->assign('CANTIDAD',$mat_pres[$key2]['CANT']);
						}
						if($mat_pres[$key2]['CTIPO']==2){
							$tpl->assign('PRELACION','UC APROBADAS');
						}else{
							$mat_p=$mats->get_mat($mat_pres[$key2]['CPRESLACION']);
							$tpl->assign('PRELACION',$mat_p[0][0]['DESCRIPCION']);
						}
					}
				}

			}*/
		}
	}
}
?>