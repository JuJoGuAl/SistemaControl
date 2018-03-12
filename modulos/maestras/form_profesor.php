<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_profesores.php");
include_once("./clases/class_estatus.php");
include_once("./clases/class_entidades.php");
include_once("./clases/functions.php");
$perm = new permisos();
$entidades = new entidades();
$profesores = new profesores();
$estatus = new estatus();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$row_clas="";
	$row=0;
	$modulo = $perm->get_module($_GET['submod']);
	$paises = $entidades->get_pais();
	$status = $estatus->list_st('P');
	$profesiones = $profesores->list_prof(1);
	$dedicaciones = $profesores->list_ded(1);
	$categorias = $profesores->list_cat(1);
	$tpl->assign("obj",'prof');
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	if($action==''){
		$tpl->assign("accion",'save_new');
		$tpl->assign("form_title",'CREAR UN PROFESOR');
		$tpl->assign("form_subtitle",'COMPLETE EL FORMULARIO PARA AGREGAR UN PROFESOR NUEVO');
		foreach ($array_sexo as $key => $value){
			$tpl->newBlock("sexos_det");
			$tpl->assign("sex_code",$key);
			$tpl->assign("sex_name",$value);
		}
		foreach ($array_edo as $key => $value){
			$tpl->newBlock("edo_det");
			$tpl->assign("edo_code",$value);
			$tpl->assign("edo_name",$value);
		}
		if(!empty($paises)){
			foreach ($paises as $llave => $datos) {
				$tpl->newBlock("pais_det");
				foreach ($paises[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
		}
		if(!empty($profesiones)){
			foreach ($profesiones as $llave => $datos) {
				$tpl->newBlock("prof_det");
				foreach ($profesiones[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
		}
		if(!empty($dedicaciones)){
			foreach ($dedicaciones as $llave => $datos) {
				$tpl->newBlock("prof_ded");
				foreach ($dedicaciones[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
		}
		if(!empty($categorias)){
			foreach ($categorias as $llave => $datos) {
				$tpl->newBlock("prof_cat");
				foreach ($categorias[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
		}
		foreach ($array_con as $key => $value){
			$tpl->newBlock("prof_con");
			$tpl->assign("con_code",$key);
			$tpl->assign("con_name",$value);
		}
	}elseif($action=='edt'){
		$tpl->assign("accion",'save_edt');
		$tpl->assign("form_title",'EDITAR UN PROFESOR');
		$tpl->assign("form_subtitle",'ACTUALICE LOS DATOS DEL PROFESOR');
		$tpl->assign("id",$_GET['id']);
		$tpl->assign("read",'readonly');//IMPIDE QUE LA CI CAMBIE
		$data_profesor=$profesores->get_profesor($_GET['id']);
		if(!empty($data_profesor)){
			foreach ($data_profesor as $llave => $datos){
				foreach ($data_profesor[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
				foreach ($array_sexo as $key => $value){
					$tpl->newBlock("sexos_det");
					$tpl->assign("sex_code",$key);
					$tpl->assign("sex_name",$value);
					if($datos['SEXO'] == $key){
						$tpl->assign("selected",$selected);
					}
				}
				foreach ($array_edo as $key => $value){
					$tpl->newBlock("edo_det");
					$tpl->assign("edo_code",$value);
					$tpl->assign("edo_name",$value);
					if($datos['CIVIL'] == $value){
						$tpl->assign("selected",$selected);
					}
				}
				if(!empty($paises)){
					foreach ($paises as $llave1 => $datos1) {
						$tpl->newBlock("pais_det");
						if($datos['CPAIS'] == $datos1['CPAIS']){
							$tpl->assign("selected",$selected);
							$estados = $entidades->get_estados($datos['CPAIS']);
						}
						foreach ($paises[$llave1] as $key => $value){
							$tpl->assign($key,$value);
						}
					}
				}
				if(!empty($profesiones)){
					foreach ($profesiones as $llave1 => $datos1) {
						$tpl->newBlock("prof_det");
						if($datos['CPROFESION'] == $datos1['CPROFESION']){
							$tpl->assign("selected",$selected);
						}
						foreach ($profesiones[$llave1] as $key => $value){
							$tpl->assign($key,$value);
						}
					}
				}
				if(!empty($dedicaciones)){
					foreach ($dedicaciones as $llave2 => $datos2) {
						$tpl->newBlock("prof_ded");
						if($datos['CDEDICACION'] == $datos2['CDEDICACION']){
							$tpl->assign("selected",$selected);
						}
						foreach ($dedicaciones[$llave2] as $key => $value){
							$tpl->assign($key,$value);
						}
					}
				}
				if(!empty($categorias)){
					foreach ($categorias as $llave3 => $datos3) {
						$tpl->newBlock("prof_cat");
						if($datos['CCATEGORIA'] == $datos3['CCATEGORIA']){
							$tpl->assign("selected",$selected);
						}
						foreach ($categorias[$llave3] as $key => $value){
							$tpl->assign($key,$value);
						}
					}
				}
				foreach ($array_con as $key => $value){
					$tpl->newBlock("prof_con");
					$tpl->assign("con_code",$key);
					$tpl->assign("con_name",$value);
					if($datos['CCONDICION'] == $key){
						$tpl->assign("selected",$selected);
					}
				}
				if(!empty($estados)){
					foreach ($estados as $llave1 => $datos1) {
						$tpl->newBlock("edos_det");
						if($datos['CESTADO'] == $datos1['CESTADO']){
							$tpl->assign("selected",$selected);
							$ciudades = $entidades->get_ciudad($datos['CESTADO']);
						}
						foreach ($estados[$llave1] as $key => $value){
							$tpl->assign($key,$value);
						}
					}
				}
				if(!empty($ciudades)){
					foreach ($ciudades as $llave1 => $datos1) {
						$tpl->newBlock("ciuds_det");
						if($datos['CCIUDAD'] == $datos1['CCIUDAD']){
							$tpl->assign("selected",$selected);
						}
						foreach ($ciudades[$llave1] as $key => $value){
							$tpl->assign($key,$value);
						}
					}
				}
				//ACTIVO STATUS QUE ESTA BLOQUEADO EN CREACION Y MUESTRO SU VALOR
				$tpl->newBlock("st_block");
				if(!empty($status)){
					foreach ($status as $llave1 => $datos1){
						$tpl->newBlock("st_det");
						if($datos['CESTATUS'] == $datos1['CESTATUS']){
							$tpl->assign("selected",$selected);
						}
						foreach ($status[$llave1] as $key => $value){
							$tpl->assign($key,$value);
						}
					}
				}
			}
			//ACTIVA LA INFORMACION DE AUDITORIA
			$tpl->newBlock("det_datos");
			$tpl->assign("USUARIO_CREADO",$data_profesor[0]['USUARIO_CREADO']);
			$tpl->assign("USUARIO_MODIFICACION",$data_profesor[0]['USUARIO_MODIFICACION']);
		}
	}
}
?>