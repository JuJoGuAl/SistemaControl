<?php
session_start();
include_once("../../clases/functions.php");
include_once("../../clases/class_entidades.php");
include_once("../../clases/class_permisos.php");
include_once("../../clases/class_alumnos.php");
include_once("../../clases/class_profesores.php");
include_once("../../clases/class_estatus.php");
include_once("../../clases/class_carreras.php");
include_once("../../clases/class_materias.php");
include_once("../../clases/class_turnos.php");
include_once("../../clases/class_periodos.php");
include_once("../../clases/class_secciones.php");
include_once("../../clases/class_conceptos.php");
include_once("../../clases/class_bancos.php");
include_once("../../clases/class_cuentas.php");
include_once("../../clases/class_tipo_pagos.php");
include_once("../../clases/class_convenios.php");
include_once("../../clases/class_inscripciones.php");
$response=array();
$entidades = new entidades();
$alumnos = new alumnos();
$profesores = new profesores();
$estatus = new estatus();
$carreras = new carreras();
$turnos = new turnos();
$perm = new permisos();
$EST_MATERIAS = new EST_MATERIAS();
$secciones = new secciones();
$conceptos = new conceptos;
$per = new periodos();
$bancos = new bancos;
$cuentas = new cuentas;
$pagos = new pagos;
$convenios = new convenios;
$inscripcion = new inscripcion();
$table="";
$titles="";
$row_clas="";
$row=0;
$perm_val = $perm->val_mod($_SESSION['user_log'],'CRUD_ALUMNO');
$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
$obj=(isset($_REQUEST['obj'])?$_REQUEST['obj']:'');
$objeto=(isset($_REQUEST['objeto'])?$_REQUEST['objeto']:'');
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}elseif($obj=="prof"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_cedula_c));
		array_push($datos, strtoupper($_apellidos_c));
		array_push($datos, strtoupper($_nombres_c));
		array_push($datos, strtoupper(date_to_mysql($_fecha_nac_c)));
		array_push($datos, strtoupper($_pais_c));
		array_push($datos, strtoupper($_estado_c));
		array_push($datos, strtoupper($_ciudad_c));
		array_push($datos, strtoupper($_sexo_c));
		array_push($datos, strtoupper($_civil_c));
		array_push($datos, strtoupper($_direccion_c));
		array_push($datos, strtoupper($_telefono_c));
		array_push($datos, strtoupper('5'));//ESTATUS DE PROFESOR ACTIVO
		array_push($datos, strtoupper($_prof_c));
		array_push($datos, strtoupper($_ded_c));
		array_push($datos, strtoupper($_cat_c));
		array_push($datos, strtoupper($_con_c));
		array_push($datos, strtoupper($_obs_c));
		$response=$profesores->new_profesor($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_cedula_c));
		array_push($datos, strtoupper($_apellidos_c));
		array_push($datos, strtoupper($_nombres_c));
		array_push($datos, strtoupper(date_to_mysql($_fecha_nac_c)));
		array_push($datos, strtoupper($_pais_c));
		array_push($datos, strtoupper($_estado_c));
		array_push($datos, strtoupper($_ciudad_c));
		array_push($datos, strtoupper($_sexo_c));
		array_push($datos, strtoupper($_civil_c));
		array_push($datos, strtoupper($_direccion_c));
		array_push($datos, strtoupper($_telefono_c));
		array_push($datos, strtoupper($_status_c));
		array_push($datos, strtoupper($_prof_c));
		array_push($datos, strtoupper($_ded_c));
		array_push($datos, strtoupper($_cat_c));
		array_push($datos, strtoupper($_con_c));
		array_push($datos, strtoupper($_obs_c));
		$response=$profesores->edit_profesor($_cprofesor,$datos);
	}elseif($action=='check_ced'){
		$response=$profesores->get_ced($_POST['ced'],$_POST['code']);
	}
}elseif($obj=="alum"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_cedula_c));
		array_push($datos, strtoupper($_apellidos_c));
		array_push($datos, strtoupper($_nombres_c));
		array_push($datos, strtoupper(date_to_mysql($_fecha_nac_c)));
		array_push($datos, strtoupper($_pais_c));
		array_push($datos, strtoupper($_estado_c));
		array_push($datos, strtoupper($_ciudad_c));
		array_push($datos, strtoupper('N/A'));//ETNIA
		array_push($datos, strtoupper($_sexo_c));
		array_push($datos, strtoupper($_civil_c));
		array_push($datos, strtoupper($_direccion_c));
		array_push($datos, strtoupper($_telefono_c));
		array_push($datos, strtoupper('1'));//POR DEFECTO UN ALUMNO OBTIENE EL STATUS DE ACTIVO
		//DATOS DEL PLANTEL DE EGRESO
		$plantel=array();
		if($_plantel_c!=''){
			//EL PLANTEL FUE INGRESADO
			array_push($plantel, strtoupper($_plantel_c));
			array_push($plantel, strtoupper($_pais_plantel_c));
			array_push($plantel, strtoupper($_estado_plantel_c));
			array_push($plantel, strtoupper($_ciudad_plantel_c));
			array_push($plantel, strtoupper(@$_especialidad_c));
			array_push($plantel, strtoupper($_plantel_tipo_c));
			array_push($plantel, strtoupper($_plantel_num_c));
			array_push($plantel, strtoupper(@$_ins_cod_c));
			array_push($plantel, strtoupper(date_to_mysql(@$_fec_cert_c)));
			array_push($plantel, strtoupper(date_to_mysql(@$_fec_titulo_c)));
			array_push($plantel, strtoupper(@$_no_reg_c));
			array_push($plantel, strtoupper($_year_c));
		}
		//COLOCO EL @ PORQUE SI EL ALUMNO NO SELECCIONA NINGUN DOCUMENTO EL ARRAY _DOC ESTARÁ VACIO
		$response=@$alumnos->new_alumnos($datos,$_doc,$plantel);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_cedula_c));
		array_push($datos, strtoupper($_apellidos_c));
		array_push($datos, strtoupper($_nombres_c));
		array_push($datos, strtoupper(date_to_mysql($_fecha_nac_c)));
		array_push($datos, strtoupper($_pais_c));
		array_push($datos, strtoupper($_estado_c));
		array_push($datos, strtoupper($_ciudad_c));
		array_push($datos, strtoupper('N/A'));//ETNIA
		array_push($datos, strtoupper($_sexo_c));
		array_push($datos, strtoupper($_civil_c));
		array_push($datos, strtoupper($_direccion_c));
		array_push($datos, strtoupper($_telefono_c));
		array_push($datos, strtoupper($_status_c));
		//DATOS DEL PLANTEL DE EGRESO
		$plantel=array();
		if($_plantel_c!=''){
			//EL PLANTEL FUE INGRESADO
			array_push($plantel, strtoupper($_calumno));
			array_push($plantel, strtoupper($_plantel_c));
			array_push($plantel, strtoupper($_pais_plantel_c));
			array_push($plantel, strtoupper($_estado_plantel_c));
			array_push($plantel, strtoupper($_ciudad_plantel_c));
			array_push($plantel, strtoupper(@$_especialidad_c));
			array_push($plantel, strtoupper($_plantel_tipo_c));
			array_push($plantel, strtoupper($_plantel_num_c));
			array_push($plantel, strtoupper(@$_ins_cod_c));
			array_push($plantel, strtoupper(date_to_mysql(@$_fec_cert_c)));
			array_push($plantel, strtoupper(date_to_mysql(@$_fec_titulo_c)));
			array_push($plantel, strtoupper(@$_no_reg_c));
			array_push($plantel, strtoupper($_year_c));
		}
		//COLOCO EL @ PORQUE SI EL ALUMNO NO SELECCIONA NINGUN DOCUMENTO EL ARRAY _DOC ESTARÁ VACIO
		$response=@$alumnos->edit_alumnos($_calumno,$datos,$_doc,$plantel);
	}elseif($action=='check_ced'){
		$response=$alumnos->get_ced($_POST['ced'],$_POST['code']);
	}
}elseif($obj=="sts"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_descripcion_c));
		array_push($datos, strtoupper($_clase_c));		
		$response=$estatus->new_status($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_descripcion_c));
		array_push($datos, strtoupper($_clase_c));		
		$response=$estatus->edit_status($_cstatus,$datos);
	}
}elseif($obj=="car"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_codigo_c));
		array_push($datos, strtoupper($_descripcion_c));
		array_push($datos, strtoupper('1'));//ACTIVO POR DEFECTO
		$response=$carreras->new_car($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_codigo_c));
		array_push($datos, strtoupper($_descripcion_c));
		array_push($datos, strtoupper($_status_c));
		$response=$carreras->edit_car($_ccarrera,$datos);
	}
}elseif($obj=="mat"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_codigo_c));
		array_push($datos, strtoupper($_descripcion_c));
		array_push($datos, strtoupper($_ht_c));
		array_push($datos, strtoupper($_hp_c));
		array_push($datos, strtoupper($_uc_c));
		array_push($datos, strtoupper($_cm1_c));
		array_push($datos, strtoupper($_cm2_c));
		$_ver=@$_POST['ver']<>0 ? strtoupper(@$_POST['ver']):"0";
		array_push($datos, strtoupper($_ver));
		array_push($datos, strtoupper($_notas_c));
		array_push($datos, strtoupper('1'));//ACTIVO POR DEFECTO
		$response=$EST_MATERIAS->new_mat($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_codigo_c));
		array_push($datos, strtoupper($_descripcion_c));
		array_push($datos, strtoupper($_ht_c));
		array_push($datos, strtoupper($_hp_c));
		array_push($datos, strtoupper($_uc_c));
		array_push($datos, strtoupper($_cm1_c));
		array_push($datos, strtoupper($_cm2_c));
		$_ver=@$_POST['ver']<>0 ? strtoupper(@$_POST['ver']):"0";
		array_push($datos, strtoupper($_ver));
		array_push($datos, strtoupper($_notas_c));
		array_push($datos, strtoupper($_status_c));
		$response=$EST_MATERIAS->edit_mat($_cmateria,$datos);
	}elseif($action=='del_car_mat'){
		$response=$EST_MATERIAS->del_mat_car($_POST['id']);
	}elseif($action=='get_car'){
		$result=$carreras->get_cars(1,$_POST['no_car']);
		if(!empty($result)){
			foreach ($result as $llave => $datos){
				foreach ($result[$llave] as $key => $value){
					$response[$llave][$key]=strtoupper($value);
				}
			}
		}
	}elseif($action=='new_mat_car'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_cmateria));
		array_push($datos, strtoupper($_carreras_c));
		array_push($datos, strtoupper($_semestre_c));
		$response=$EST_MATERIAS->new_mat_car($datos);
	}elseif($action=='get_presla'){
		$mat_pres=$EST_MATERIAS->get_mat_pres($_POST['cmat'],$_POST['ccar']);
		if(!empty($mat_pres)){
			foreach ($mat_pres as $llave => $datos){
				$cantidad = ($mat_pres[$llave]['CTIPO']==1)?'N/A':$mat_pres[$llave]['CANT'];
				$mat_p=$EST_MATERIAS->get_mat($mat_pres[$llave]['CPRESLACION']);
				$prelacion = ($mat_pres[$llave]['CTIPO']==2)?'UC APROBADAS':$mat_p[0][0]['DESCRIPCION'];
				$response[$llave]['CANTIDAD']=strtoupper($cantidad);
				$response[$llave]['PRELACION']=strtoupper($prelacion);
				$response[$llave]['CPRELACION']=strtoupper($mat_pres[$llave]['CPRESLACION']);
				$id_pres=$mat_pres[$llave]['CPRESLA'];
				$cadena_acciones='<div class="tooltip-mats_pres">
				<button type="button" class="btn btn-default btn-circle borrar" data-action="del_mat_pres" data-obj="mat" data-id="'.$id_pres.'" href="modulos/maestras/modal.php" data-toggle2="tooltip" data-placement="top" title="" data-original-title="BORRAR REQUISITO"><i class="fa fa-trash-o"></i></button>
				</div>
				';
				$response[$llave]['acciones']=($cadena_acciones);
			}
		}
	}elseif($action=='del_mat_pres'){
		$response=$EST_MATERIAS->del_mat_pres($_POST['id']);
	}elseif($action=='get_pres'){
		$result=$EST_MATERIAS->list_mat_pres($_POST['car'],$_POST['sem'],$_POST['no_pre']);
		if(!empty($result)){
			foreach ($result as $llave => $datos){
				foreach ($result[$llave] as $key => $value){
					$response[$llave][$key]=strtoupper($value);
				}
			}
		}
	}elseif($action=='new_presla'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		$mat_car=$EST_MATERIAS->get_presla($_cpreslacion);
		array_push($datos, strtoupper($mat_car[0]['CMATERIA']));
		if($_tipo_c==1){
			array_push($datos, strtoupper($_preslacion_c));
		}elseif($_tipo_c==2){
			array_push($datos, strtoupper($mat_car[0]['CMATERIA']));
		}
		array_push($datos, strtoupper($mat_car[0]['CCARRERA']));
		array_push($datos, strtoupper($_tipo_c));
		$_cant=@$_POST['cant_c']>0 ? strtoupper(@$_POST['cant_c']):"0";
		array_push($datos, strtoupper($_cant));
		$response=$EST_MATERIAS->new_presla($datos);
	}
}elseif($obj=="tur"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_descripcion_c));
		array_push($datos, strtoupper('1'));//ACTIVO POR DEFECTO
		$response=$turnos->new_turno($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_descripcion_c));
		array_push($datos, strtoupper($_status_c));		
		$response=$turnos->edit_turno($_cturno,$datos);
	}
}elseif($obj=="sec"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_seccion_c));
		array_push($datos, strtoupper($_carreras_c));
		array_push($datos, strtoupper($_sem_c));
		array_push($datos, strtoupper($_cup_c));
		$response=$secciones->new_seccion($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_seccion_c));
		array_push($datos, strtoupper($_carreras_c));
		array_push($datos, strtoupper($_sem_c));
		array_push($datos, strtoupper($_cup_c));
		$response=$secciones->edit_seccion($_cseccion,$datos);
	}elseif($action=='check_sec'){
		$response=$secciones->get_sec($_POST['data'],$_POST['code']);
	}
}elseif($obj=="per"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_per_c));
		array_push($datos, strtoupper($_tper_c));
		array_push($datos, strtoupper(date_to_mysql($_f_ini_c)));
		array_push($datos, strtoupper(date_to_mysql($_f_fin_c)));
		$_f_grad=@$_POST['f_gra_c']<>0 ? strtoupper(date_to_mysql($_f_gra_c)):"";
		array_push($datos, strtoupper($_f_grad));
		array_push($datos, strtoupper(@$_pro_c));
		array_push($datos, strtoupper(date_to_mysql($_f_inir_c)));
		array_push($datos, strtoupper(date_to_mysql($_f_finr_c)));
		array_push($datos, strtoupper(date_to_mysql($_f_inii_c)));
		array_push($datos, strtoupper(date_to_mysql($_f_fini_c)));
		array_push($datos, strtoupper($_mins_c));
		array_push($datos, strtoupper($_msem_c));
		array_push($datos, strtoupper('1'));//ACTIVO POR DEFECTO
		$response=$per->new_peri($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_per_c));
		array_push($datos, strtoupper($_tper_c));
		array_push($datos, strtoupper(date_to_mysql($_f_ini_c)));
		array_push($datos, strtoupper(date_to_mysql($_f_fin_c)));
		$_f_grad=@$_POST['f_gra_c']<>0 ? strtoupper(date_to_mysql($_f_gra_c)):"";
		array_push($datos, strtoupper($_f_grad));
		array_push($datos, strtoupper($_pro_c));
		array_push($datos, strtoupper(date_to_mysql($_f_inir_c)));
		array_push($datos, strtoupper(date_to_mysql($_f_finr_c)));
		array_push($datos, strtoupper(date_to_mysql($_f_inii_c)));
		array_push($datos, strtoupper(date_to_mysql($_f_fini_c)));
		array_push($datos, strtoupper($_mins_c));
		array_push($datos, strtoupper($_msem_c));
		array_push($datos, strtoupper($_status_c));
		$response=$per->edit_peri($_cperiodo,$datos);
	}
}elseif($obj=="con"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_concepto_c));
		array_push($datos, $_precio_c);
		array_push($datos, 1);//ESTATUS
		array_push($datos, 0);//SISTEMA
		$response=$conceptos->new_concep($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_concepto_c));
		array_push($datos, $_precio_c);
		array_push($datos, $_status_c);//ESTATUS
		array_push($datos, 0);//SISTEMA
		$response=$conceptos->edit_concep($_code,$datos);
	}
}elseif($obj=="ban"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_banco_c));
		array_push($datos, 1);//ESTATUS
		$response=$bancos->new_bank($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_banco_c));
		array_push($datos, $_status_c);//ESTATUS
		$response=$bancos->edit_bank($_code,$datos);
	}
}elseif($obj=="cue"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_cuenta_c));
		array_push($datos, strtoupper($_tcuenta_c));
		array_push($datos, strtoupper($_banco_c));
		array_push($datos, 1);//ESTATUS
		$response=$cuentas->new_count($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_cuenta_c));
		array_push($datos, strtoupper($_tcuenta_c));
		array_push($datos, strtoupper($_banco_c));
		array_push($datos, $_status_c);//ESTATUS
		$response=$cuentas->edit_count($_code,$datos);
	}
}elseif($obj=="tpag"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_pago_c));
		array_push($datos, strtoupper($_cuenta_c));
		array_push($datos, 1);//ESTATUS
		$response=$pagos->new_pagos($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_pago_c));
		array_push($datos, strtoupper($_cuenta_c));
		array_push($datos, $_status_c);//ESTATUS
		$response=$pagos->edit_pagos($_code,$datos);
	}
}elseif($obj=="cov"){
	if($action=='save_new'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_convenio_c));
		array_push($datos, 1);//ESTATUS
		$response=$convenios->new_con($datos);
	}elseif($action=='save_edt'){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper($_convenio_c));
		array_push($datos, $_status_c);//ESTATUS
		$response=$convenios->edit_con($_code,$datos);
	}
}elseif($obj=="prof_mat"){
	if($action=='Profesores'){
		$titles='<tr><th>CODIGO</th><th>CEDULA</th><th>NOMBRES</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $profesores->list_profesors(5);
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="'.$row_clas.'"><td class="'.$action.'_id">'.$value['CPROFESOR'].'</td><td class="'.$action.'_ced">'.$value['CEDULA'].'</td><td class="'.$action.'_name">'.$value['PROFESOR'].'</td></tr>';
			}
		}
	}elseif($action=='Periodos'){
		$titles='<tr><th>CODIGO</th><th>PERIODO</th><th>FEC INI</th><th>FEC FIN</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $per->get_pers(1);
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CPERIODO'].'</td><td class="'.$action.'_name">'.$value['PERIODO'].'</td><td>'.$value['FECHA_INI'].'</td><td>'.$value['FECHA_FIN'].'</td></tr>';
			}
		}
	}elseif($action=='Carreras'){
		$titles='<tr><th>#</th><th>CODIGO</th><th>CARRERA</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $carreras->get_cars(1);
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CCARRERA'].'</td><td>'.$value['CODIGO'].'</td><td class="'.$action.'_name">'.$value['DESCRIPCION'].'</td></tr>';
			}
		}
	}elseif($action=='Materias'){
		$titles='<tr><th>#</th><th>CODIGO</th><th>MATERIA</th><th>SEMESTRE</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $inscripcion->list_mat_car($_POST['det']);
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['MATE_CODE'].'</td><td>'.$value['MAT_COD'].'</td><td class="'.$action.'_name">'.$value['MAT_NAME'].'</td><td class="'.$action.'_sem">'.$value['MAT_SEM'].'</td></tr>';
			}
		}
	}elseif($action=='Secciones'){
		$titles='<tr><th>#</th><th>SECCION</th><th>CARRERA</th><th>CUPOS</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $secciones->lis_sec_car_sem($_POST['det'],$_POST['sem']);
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CSECCION'].'</td><td class="'.$action.'_name">'.$value['SECCION'].'</td><td>'.$value['CARRERA'].'</td><td>'.$value['CUPOS'].'</td></tr>';
			}
		}
	}
	$response=$table;
}else{
	if($objeto=='estados'){
		$estados = $entidades->get_estados($_POST['pais']);
		if(!empty($estados)){
			foreach ($estados as $llave => $datos){
				$response[$llave]['CESTADO']=$datos['CESTADO'];
				$response[$llave]['nombre']=$datos['NOMBRE'];
				$response[$llave]['CPAIS']=$datos['CPAIS'];
			}
		}
	}elseif($objeto=='ciudades'){
		$ciudades = $entidades->get_ciudad($_POST['cedo']);
		if(!empty($ciudades)){
			foreach ($ciudades as $llave => $datos){
				$response[$llave]['ciuc']=$datos['CCIUDAD'];
				$response[$llave]['ciu']=$datos['NOMBRE'];
				$response[$llave]['edo']=$datos['CESTADO'];
			}
		}
	}elseif($objeto=='Carreras'){
		$table = '<table width="100%" class="table table-striped table-bordered table-hover table_carreras" id="carreras_tbl"><thead>';
		$table .= '<tr><th>SERIAL</th><th>CODIGO</th><th>DESCRIPCION</th></tr></thead><tbody>';
		$cars=$carreras->get_cars('1',$_POST['non_car']);
		if(!empty($cars)){
			foreach ($cars as $llave => $datos){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table .= '<tr class="'.$row_clas.'"><td class="id">'.$datos['CCARRERA'].'</td><td class="code">'.$datos['CODIGO'].'</td><td class="desc">'.$datos['DESCRIPCION'].'</td></tr>';
			}
		}
		$table .= '</tbody></table>';
		$response = $table;
	}
}
echo json_encode($response);
?>