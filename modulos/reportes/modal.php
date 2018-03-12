<?php
session_start();
include_once("../../clases/functions.php");
include_once("../../clases/class_alumnos.php");
include_once("../../clases/class_inscripciones.php");
include_once("../../clases/class_secciones.php");
include_once("../../clases/class_materias.php");
include_once("../../clases/class_bancos.php");
include_once("../../clases/class_periodos.php");
$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
$table="";
$titles="";
$response=array();
$row_clas="";
$row=0;
$alumnos = new alumnos();
$inscripcion = new inscripcion();
$secciones = new secciones();
$materias = new EST_MATERIAS();
$bancos = new bancos();
$periodos = new periodos();
if($action=='Alumnos'){
	$titles='<tr><th>CODIGO</th><th>CEDULA</th><th>NOMBRES</th></tr>';
	$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
	$data = $alumnos->list_alum_sald();
	if (!empty($data)){
		foreach ($data as $key => $value){
			$row++;
			$row_clas = ($row%2==0) ? 'odd' : 'even' ;
			$table.='<tr class="'.$row_clas.'"><td class="'.$action.'_id">'.$value['CODIGO'].'</td><td class="'.$action.'_ced">'.$value['CEDULA'].'</td><td class="'.$action.'_name">'.$value['NOMBRES'].'</td></tr>';
		}
	}
}elseif($action=='Carreras'){
	$titles='<tr><th>#</th><th>CODIGO</th><th>CARRERA</th></tr>';
	$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
	$data = $inscripcion->list_cars();
	if (!empty($data)){
		foreach ($data as $key => $value){
			$row++;
			$row_clas = ($row%2==0) ? 'odd' : 'even' ;
			$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CCARRERA'].'</td><td>'.$value['CODIGO'].'</td><td class="'.$action.'_name">'.$value['DESCRIPCION'].'</td></tr>';
		}
	}
}elseif($action=='Secciones'){
	$car = ($_POST['det']>0) ? $_POST['det'] : "" ;
	$titles='<tr><th>#</th><th>SECCION</th><th>CARRERA</th><th>SEM</th></tr>';
	$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
	$data = $secciones->get_secciones($car);
	if (!empty($data)){
		foreach ($data as $key => $value){
			$row++;
			$row_clas = ($row%2==0) ? 'odd' : 'even' ;
			$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CSECCION'].'</td><td class="'.$action.'_name">'.$value['SECCION'].'</td><td>'.$value['CARRERA'].'</td><td>'.$value['SEMESTRE'].'</td></tr>';
		}
	}
}elseif($action=='Materias'){
	$car = ($_POST['det']>0) ? $_POST['det'] : "" ;
	$group = ($_POST['det']>0) ? false : true ;
	$titles='<tr><th>#</th><th>CODIGO</th><th>MATERIA</th></tr>';
	$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
	$data = $materias->list_mats($car,$group);
	if (!empty($data)){
		foreach ($data as $key => $value){
			$row++;
			$row_clas = ($row%2==0) ? 'odd' : 'even' ;
			$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['ID_MATERIA'].'</td><td>'.$value['MATERIA_CODE'].'</td><td class="'.$action.'_name">'.$value['MATERIA'].'</td></tr>';
		}
	}
}elseif($action=='Bancos'){
	$titles='<tr><th>CODIGO</th><th>BANCO</th></tr>';
	$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
	$data = $bancos->list_bank(1);
	if (!empty($data)){
		foreach ($data as $key => $value){
			$row++;
			$row_clas = ($row%2==0) ? 'odd' : 'even' ;
			$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CODIGO'].'</td><td class="'.$action.'_name">'.$value['BANCO'].'</td></tr>';
		}
	}
}elseif($action=='Periodos'){
	$titles='<tr><th>CODIGO</th><th>PERIODO</th><th>FEC INI</th><th>FEC FIN</th></tr>';
	$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
	$data = $periodos->get_pers();
	if (!empty($data)){
		foreach ($data as $key => $value){
			$row++;
			$row_clas = ($row%2==0) ? 'odd' : 'even' ;
			$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CPERIODO'].'</td><td class="'.$action.'_name">'.$value['PERIODO'].'</td><td>'.$value['FECHA_INI'].'</td><td>'.$value['FECHA_FIN'].'</td></tr>';
		}
	}
}
$response=$table;
echo json_encode($response);
?>