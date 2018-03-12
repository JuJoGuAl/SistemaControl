<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_alumnos.php");
include_once("./clases/class_estatus.php");
include_once("./clases/class_entidades.php");
include_once("./clases/functions.php");
$perm = new permisos();
$entidades = new entidades();
$alumnos = new alumnos();
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
	$status = $estatus->list_st('A');
	$ALUM_DOCUMENTOS = $alumnos->list_docs();
	$tpl->assign("obj",'alum');
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	if($action==''){
		$tpl->assign("accion",'save_new');
		$tpl->assign("form_title",'CREAR UN ALUMNO');
		$tpl->assign("form_subtitle",'COMPLETE EL FORMULARIO PARA AGREGAR UN ALUMNO NUEVO');
		$tpl->assign("FECHA_REG",date('d/m/Y'));
		$tpl->newBlock("plantel_egreso");
		foreach ($array_sexo as $key => $value){
			$tpl->newBlock("sexos_det");
			$tpl->assign("sex_code",$key);
			$tpl->assign("sex_name",$value);
		}
		foreach ($array_plantel_tipo as $key => $value){
			$tpl->newBlock("plantel_tipo");
			$tpl->assign("tipo_code",$key);
			$tpl->assign("tipo_name",$value);
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
				$tpl->newBlock("pais_plantel_det");
				foreach ($paises[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
		}
		if(!empty($ALUM_DOCUMENTOS)){
			foreach ($ALUM_DOCUMENTOS as $llave => $datos) {
				$tpl->newBlock("ALUM_DOCUMENTOS");
				foreach ($ALUM_DOCUMENTOS[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
		}
	}elseif($action=='edt'){
		$tpl->assign("accion",'save_edt');
		$tpl->assign("form_title",'EDITAR UN ALUMNO');
		$tpl->assign("form_subtitle",'ACTUALICE LOS DATOS DEL ALUMNO');
		$tpl->assign("id",$_GET['id']);
		$tpl->assign("read",'readonly');//IMPIDE QUE LA CI CAMBIE
		$alum=$alumnos->get_alumno($_GET['id']);
		$data_alumno=$alum[0];
		$docs_alumno=@$alum[1];
		$pla_alumno=@$alum[2];
		if(!empty($data_alumno)){
			//BUCLE PARA SACAR LA DATA PRINCIPAL
			foreach ($data_alumno as $llave => $datos){
				foreach ($data_alumno[$llave] as $key => $value){
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
				//RECUPERO LA DATA DE SUS ALUM_DOCUMENTOS
				if(!empty($ALUM_DOCUMENTOS)){
					foreach ($ALUM_DOCUMENTOS as $llave => $datos1) {
						$tpl->newBlock("ALUM_DOCUMENTOS");
						if(!empty($docs_alumno)){
							foreach ($docs_alumno as $key1 => $value1){
								if($value1['CDOCUMENTO'] == $datos1['CDOCUMENTO']){
									$tpl->assign("checked","checked");
								}
							}
						}
						foreach ($ALUM_DOCUMENTOS[$llave] as $key => $value){
							$tpl->assign($key,$value);
						}
					}
				}
				//RECUPERO LA DATA DEL PLANTEL
				$tpl->newBlock("plantel_egreso");
				if(!empty($pla_alumno)){
					foreach ($pla_alumno as $llave => $datos){
						foreach ($pla_alumno[$llave] as $key => $value){
							$tpl->assign($key,$value);
						}
						foreach ($array_plantel_tipo as $key => $value){
							$tpl->newBlock("plantel_tipo");
							$tpl->assign("tipo_code",$key);
							$tpl->assign("tipo_name",$value);
							if($datos['TIPO'] == $key){
								$tpl->assign("selected",$selected);
							}
						}
						if(!empty($paises)){
							foreach ($paises as $llave1 => $datos1) {
								$tpl->newBlock("pais_plantel_det");
								if($datos['CPAIS'] == $datos1['CPAIS']){
									$tpl->assign("selected",$selected);
									$estados = $entidades->get_estados($datos['CPAIS']);
								}
								foreach ($paises[$llave1] as $key => $value){
									$tpl->assign($key,$value);
								}
							}
						}
						if(!empty($estados)){
							foreach ($estados as $llave1 => $datos1) {
								$tpl->newBlock("edos_plantel_det");
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
								$tpl->newBlock("ciuds_plantel_det");
								if($datos['CCIUDAD'] == $datos1['CCIUDAD']){
									$tpl->assign("selected",$selected);
								}
								foreach ($ciudades[$llave1] as $key => $value){
									$tpl->assign($key,$value);
								}
							}
						}
					}					
				}else{
					foreach ($array_plantel_tipo as $key => $value){
						$tpl->newBlock("plantel_tipo");
						$tpl->assign("tipo_code",$key);
						$tpl->assign("tipo_name",$value);
					}					
					if(!empty($paises)){
						foreach ($paises as $llave => $datos) {							
							$tpl->newBlock("pais_plantel_det");
							foreach ($paises[$llave] as $key => $value){
								$tpl->assign($key,$value);
							}
						}
					}					
				}
			}
			//ACTIVO PESTAÑA ACADEMICOS
			$tpl->newBlock("pst_acade");
			//ACTIVA LA INFORMACION DE AUDITORIA
			$tpl->newBlock("det_datos");
			$tpl->assign("USUARIO_CREADO",$data_alumno[0]['USUARIO_CREADO']);
			$tpl->assign("USUARIO_MODIFICACION",$data_alumno[0]['USUARIO_MODIFICACION']);
		}
	}
}
?>