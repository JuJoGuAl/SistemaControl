<?php
session_start();
include_once("../../clases/functions.php");
include_once("../../clases/class_alumnos.php");
include_once("../../clases/class_periodos.php");
include_once("../../clases/class_inscripciones.php");
include_once("../../clases/class_carreras.php");
include_once("../../clases/class_materias.php");
include_once("../../clases/class_secciones.php");
include_once("../../clases/class_cobros.php");
include_once("../../clases/class_profesores.php");
include_once("../../clases/class_parametros.php");
$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
$table="";
$titles="";
$response=array();
$row_clas="";
$row=0;
$alumnos = new alumnos();
$periodos = new periodos();
$carreras = new carreras();
$materias = new EST_MATERIAS();
$inscripcion = new inscripcion();
$secciones = new secciones();
$cobros = new cobros();
$profesores = new profesores();
$parametros = new parametros();
if($action=='Alumnos'){
	$saldo_t = (@$_POST['adic']) ? '' : '<th>SALDO</th>' ;
	$titles='<tr><th>CODIGO</th><th>CEDULA</th><th>NOMBRES</th>'.$saldo_t.'</tr>';
	$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
	$data = (@$_POST['adic']) ? $inscripcion->list_alum_ins() : $alumnos->list_alum_sald() ;
	if (!empty($data)){
		foreach ($data as $key => $value){
			$row++;
			$row_clas = ($row%2==0) ? 'odd' : 'even' ;
			$saldo_r = (@$_POST['adic']) ? '' : '<td class="'.$action.'_saldo">'.($value['SALDO']).'</td>' ;
			$table.='<tr class="'.$row_clas.'"><td class="'.$action.'_id">'.$value['CODIGO'].'</td><td class="'.$action.'_ced">'.$value['CEDULA'].'</td><td class="'.$action.'_name">'.$value['NOMBRES'].'</td>'.$saldo_r.'</tr>';
		}
	}
}elseif($action=='Profesores'){
	$titles='<tr><th>CODIGO</th><th>CEDULA</th><th>NOMBRES</th></tr>';
	$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
	$data = $inscripcion->list_prof_ins();
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
	$data = $periodos->get_pers(1);
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
	$data = (@$_POST['adic']) ? $inscripcion->list_car_ins($_POST['det']) : $inscripcion->list_cars() ;
	if (!empty($data)){
		foreach ($data as $key => $value){
			$row++;
			$row_clas = ($row%2==0) ? 'odd' : 'even' ;
			$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CCARRERA'].'</td><td>'.$value['CODIGO'].'</td><td class="'.$action.'_name">'.$value['DESCRIPCION'].'</td></tr>';
		}
	}
}elseif($action=='Materias'){
	if(@$_POST['adic']){
		$titles='<tr><th>#</th><th>CODIGO</th><th>MATERIA</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $inscripcion->list_mat_ins($_POST['det']) ;
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CMATERIA'].'</td><td>'.$value['CODIGO'].'</td><td class="'.$action.'_name">'.$value['DESCRIPCION'].'</td></tr>';
			}
		}
	}else{
		if(isset($_POST['cmat'])){
			$data=$inscripcion->get_mate($_POST['cmat']);
			foreach ($data[0] as $key => $value){
				$mate[$key]=strtoupper($value);
			}
			$data1=$secciones->get_sec_car($_POST['ccar'],$_POST['cmat'],$_POST['cper'],$mate['MAT_SEM']);
			if(!empty($data1)){
				$sec_mat=array();
				foreach ($data1 as $llave => $datos){
					foreach ($data1[$llave] as $key => $value){
						$sec_mat[$key]=strtoupper($value);
					}
					$mate['sec'][$llave]=$sec_mat;
				}
			}
			$table=$mate;
		}else{
			$titles='<tr><th>#</th><th>CODIGO</th><th>MATERIA</th><th>SEMESTRE</th></tr>';
			$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
			$data = $inscripcion->list_mat_alum($_POST['code_det'],$_POST['code'],$_POST['no_mat']);
			if (!empty($data)){
				foreach ($data as $key => $value){
					$row++;
					$row_clas = ($row%2==0) ? 'odd' : 'even' ;
					$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['MATE_CODE'].'</td><td>'.$value['MAT_COD'].'</td><td>'.$value['MAT_NAME'].'</td><td>'.$value['MAT_SEM'].'</td></tr>';
				}
			}
		}
	}
}elseif($action=='Secciones'){
	if(@$_POST['adic']){
		$titles='<tr><th>#</th><th>SECCION</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $inscripcion->list_sec_ins($_POST['det']) ;
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CSECCION'].'</td><td class="'.$action.'_name">'.$value['DESCRIPCION'].'</td></tr>';
			}
		}
	}
}elseif($action=='Filtros'){
	extract($_POST, EXTR_PREFIX_ALL, "");
	$data = $inscripcion->search_ins_det($_Periodos_c,$_Alumno_c,$_Carreras_c,$_Materias_c,$_Secciones_c,$_Profesores_c);
	if (!empty($data)){
		foreach ($data as $llave => $datos){
			//echo $data[$llave]['CMATE'];
			$data1=$profesores->list_prof_mat($data[$llave]['CMATE'],$data[$llave]['CSECCION']);
			foreach ($data[$llave] as $key => $value){
				$mate[$llave][$key]=strtoupper($value);
			}
			$sec_mat=array();
			foreach ($data1 as $llave1 => $datos){
				foreach ($data1[$llave1] as $key1 => $value1){
					$sec_mat[$key1]=strtoupper($value1);
				}
				$mate[$llave]['prof'][$llave1]=$sec_mat;
			}
		}
		$table=$mate;
	}
}elseif($action=='Inscripciones'){
	if(isset($_POST['cins'])){
		$data=$inscripcion->get_ins($_POST['cins']);
		$cabecera=$data['cab'];
		$detalle=$data['det'];
		$recibo1=array();
		$recibo2=array();
		foreach ($cabecera[0] as $key1 => $value1){
			$recibo1[$key1]=strtoupper($value1);
		}
		$table['cab']=$recibo1;
		foreach ($detalle as $key => $value){
			foreach ($detalle[$key] as $key1 => $value1){
				$recibo2[$key1]=strtoupper($value1);
			}
			$table['det'][$key]=$recibo2;
		}
	}else{
		$titles='<tr><th>CODE</th><th>CEDULA</th><th>NOMBRES</th><th>PERIODO</th><th>FECHA</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $inscripcion->list_ins();
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="'.$row_clas.'"><td class="'.$action.'_id">'.$value['CODIGO'].'</td><td>'.$value['CEDULA'].'</td><td>'.$value['ALUMNO'].'</td><td>'.$value['PERIODO'].'</td><td>'.$value['FECHA_INS'].'</td></tr>';
			}
		}
	}
}elseif($action=='UC'){
	$uc_used=$_POST['uc']*1;
	$var=$parametros->get_parameter(5);
	$table = ($uc_used>$var[0]['VALOR']) ? false : true ;
}elseif($action=='Check_ins'){
	$data=$inscripcion->check_ins($_POST['calm'],$_POST['cper'],$_POST['ccar']);
	$table = (!empty($data)) ? false : true ;
}elseif($action=='clave'){
	$var=$parametros->get_parameter(10);
	$pass_user=md5($_POST['input']);
	$pass_bd=($var[0]['VALOR']);
	$table = ($pass_user===$pass_bd)?true:false;
}elseif($action=='UC_MAX'){
	$uc_used=$_POST['uc']*1;
	$var=$parametros->get_parameter(5);
	$var1=$parametros->get_parameter(6);
	$total_uc=($var[0]['VALOR']+$var1[0]['VALOR']);
	$table = ($uc_used>$total_uc) ? false : true ;
}
$response=$table;
echo json_encode($response);
?>