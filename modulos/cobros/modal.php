<?php
session_start();
include_once("../../clases/functions.php");
include_once("../../clases/class_alumnos.php");
include_once("../../clases/class_periodos.php");
include_once("../../clases/class_conceptos.php");
include_once("../../clases/class_permisos.php");
include_once("../../clases/class_cobros.php");
$response=array();
$alumnos = new alumnos();
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],'GEN_COBROS');
$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$table="";
	$titles="";
	$response=array();
	$row_clas="";
	$row=0;
	$periodos = new periodos();
	$conceptos = new conceptos();
	$cobros = new cobros();
	if($action=='check_ced'){
		$response=$alumnos->get_ced($_POST['ced'],0);
		if(!empty($response)){
			foreach ($response as $llave => $datos){
				foreach ($response[$llave] as $key => $value){
					$table[$key]=strtoupper($value);
				}
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
	}elseif($action=='Conceptos'){
		$titles='<tr><th>CODIGO</th><th>CONCEPTO</th><th>PRECIO</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $conceptos->list_concep(1,$_POST['non_conc']);
		$data2 = $periodos->get_pers(1,"I");//PERIODOS QUE NO SEAN INTENSIVOS (VERANOS)
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CCONCEPTO'].'</td><td class="'.$action.'_name">'.$value['CONCEPTO'].'</td><td class="'.$action.'_costo">'.(($value['SISTEMA']=='1')?$data2[0]['MINSCRIPCION']:$value['PRECIO']).'</td></tr>';
			}
		}
	}elseif($action=='Facturas'){
		if(isset($_POST['cfactura'])){
			$cfactura=$_POST['cfactura'];
			$dat_mov = $cobros->get_fac($cfactura,'FAC');
			if(!empty($dat_mov)){
				foreach ($dat_mov as $llave => $datos){
					foreach ($dat_mov[$llave] as $key => $value){
						$table[$key]=strtoupper($value);
					}
					$det_mov = $cobros->get_fac_det($cfactura,'FAC');
					if(!empty($det_mov)){
						$mov_det=array();
						foreach ($det_mov as $llave => $datos){
							foreach ($det_mov[$llave] as $key => $value){
								$mov_det[$key]=strtoupper($value);
							}
							$table['mov_det'][$llave]=$mov_det;
						}
					}
				}
			}
		}else{
			$titles='<tr><th>CODIGO</th><th>CLIENTE</th><th>MONTO</th><th>PERIODO</th><th>ESTATUS</th></tr>';
			$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
			$saldo = ($_POST['obj']=='DEV') ? true : false ;
			$data = $cobros->list_fac('FAC',false,$saldo);
			if (!empty($data)){
				foreach ($data as $key => $value){
					$row++;
					$row_clas = ($row%2==0) ? 'odd' : 'even' ;
					$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CODIGO'].'</td><td class="'.$action.'_name">'.$value['ALUMNO'].'</td><td class="'.$action.'_precio">'.$value['MONTO_NETO'].'</td><td class="'.$action.'_periodo">'.$value['PERIODO'].'</td><td>'.$value['ESTATUS'].'</td></tr>';
				}
			}
		}
	}elseif($action=='Devoluciones'){
		if(isset($_POST['cfactura'])){
			$cfactura=$_POST['cfactura'];
			$dat_mov = $cobros->get_fac($cfactura,'DEV');
			if(!empty($dat_mov)){
				foreach ($dat_mov as $llave => $datos){
					foreach ($dat_mov[$llave] as $key => $value){
						$table[$key]=strtoupper($value);
					}
					$det_mov = $cobros->get_fac_det($cfactura,'DEV');
					if(!empty($det_mov)){
						$mov_det=array();
						foreach ($det_mov as $llave => $datos){
							foreach ($det_mov[$llave] as $key => $value){
								$mov_det[$key]=strtoupper($value);
							}
							$table['mov_det'][$llave]=$mov_det;
						}
					}
				}
			}
		}else{
			$titles='<tr><th>CODIGO</th><th>CLIENTE</th><th>MONTO</th><th>PERIODO</th></tr>';
			$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
			$data = $cobros->list_fac('DEV','PROCESADA',false);
			if (!empty($data)){
				foreach ($data as $key => $value){
					$row++;
					$row_clas = ($row%2==0) ? 'odd' : 'even' ;
					$table.='<tr class="gradeA '.$row_clas.'"><td class="'.$action.'_id">'.$value['CODIGO'].'</td><td class="'.$action.'_name">'.$value['ALUMNO'].'</td><td class="'.$action.'_precio">'.$value['MONTO_NETO'].'</td><td class="'.$action.'_periodo">'.$value['PERIODO'].'</td></tr>';
				}
			}
		}
	}
}
$response=$table;
echo json_encode($response);
?>