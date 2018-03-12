<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_profesores.php");
include_once("./clases/functions.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$profesores = new profesores();
	$row_clas="";
	$row=0;
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->newBlock("form");
	$tpl->assign("form_title",'PROFESORES POR MATERIAS');
	$tpl->assign("form_subtitle",'ASIGNE A UN PROFESOR, UNA CARRERA, UNA MATERIA Y UNA SECCION');
	$tpl->assign("obj",'prof_mat');
	$tpl->assign("accion",'save_new');
	function CreateTable(){
		global $profesores,$tpl,$row,$row_clas;
		$data=$profesores->list_prof_car();
		if(!empty($data)){
			foreach ($data as $llave => $datos) {
				$tpl->newBlock("data");
				$id=$datos['CMAT_CAR_PROF'];
				$cadena_acciones='<div class="tooltip-acciones">
				<button type="button" id="Delete_btn" class="btn btn-default btn-circle" data-id="'.$id.'" data-toggle="tooltip" data-placement="top" title="" data-original-title="BORRAR CRUCE" data-toggle="modal" data-target="#modal_delete"><i class="fa fa-trash-o"></i></button>
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
	if($action==''){
		 CreateTable();
	}elseif($action=="save_new"){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, $_Materias_c);
		array_push($datos, $_Profesores_c);
		array_push($datos, $_Secciones_c);
		$response=$profesores->new_prof_mat($datos);
		if($response['titulo']=="ERROR"){
			//VERIFICAR SI MUESTRO EL ERROR SQL O MUESTRO UN ERROR GENERAL
			$tpl->assign("mensaje",$response['texto']);
			$tpl->assign("mensaje_class","alert-danger");
			CreateTable();
		}else{
			$mensaje="CRUCE REALIZADO CON EXITO!";
			$tpl->assign("mensaje",$mensaje);
			$tpl->assign("mensaje_class","alert-success");
			CreateTable();
		}
		$tpl->newBlock("mensaje_log");
	}elseif($action=="delete"){
		$response=$profesores->del_prof_mat($_POST['id']);
		if($response['titulo']=="ERROR"){
			//VERIFICAR SI MUESTRO EL ERROR SQL O MUESTRO UN ERROR GENERAL
			$tpl->assign("mensaje",$response['texto']);
			$tpl->assign("mensaje_class","alert-danger");
			CreateTable();
		}else{
			$mensaje="CRUCE ELIMINADO CON EXITO!";
			$tpl->assign("mensaje",$mensaje);
			$tpl->assign("mensaje_class","alert-success");
			CreateTable();
		}
		$tpl->newBlock("mensaje_log");
	}
}
?>