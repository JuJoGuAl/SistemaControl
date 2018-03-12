<?php
session_start();
include_once("../../clases/functions.php");
include_once("../../clases/class_cobros.php");
include_once("../../clases/class_tipo_pagos.php");
include_once("../../clases/class_cancelacion.php");
$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
$table="";
$titles="";
$response=array();
$row_clas="";
$row=0;
$cobros = new cobros();
$pagos = new pagos();
$cancelacion = new cancelacion();
if($action=='Clientes'){
	$titles='<tr><th>CODIGO</th><th>CEDULA</th><th>NOMBRES</th><th>SALDO</th></tr>';
	$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
	$data = $cobros->list_saldo();
	if (!empty($data)){
		foreach ($data as $key => $value){
			$row++;
			$row_clas = ($row%2==0) ? 'odd' : 'even' ;
			$table.='<tr class="'.$row_clas.'"><td class="'.$action.'_id">'.$value['CODIGO'].'</td><td class="'.$action.'_ced">'.$value['CEDULA'].'</td><td class="'.$action.'_name">'.$value['NOMBRES'].'</td><td>'.numeros($value['SALDO']).'</td></tr>';
		}
	}
}elseif($action=='facturas'){
	if(isset($_POST['ccliente'])){
		$titles='<tr><th>#</th><th>FACTURA</th><th>PERIODO</th><th>MONTO</th><th>SALDO</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $cobros->list_facts($_POST['ccliente']);
		if (!empty($data)){
			foreach ($data as $key => $value){
				$id='FAC_'.$value['CODIGO'];
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="'.$row_clas.'"><td><div class="checkbox"><label class="checks"><input class="facs_clientes" id="'.$id.'" name="'.$id.'" type="checkbox" value="'.$value['CODIGO'].'"></label></div></td><td class="_code">'.$value['CODIGO'].'</td><td class="_peri">'.$value['PERIODO'].'</td><td class="_monto">'.numeros($value['MONTO']).'</td><td class="_saldo">'.numeros($value['SALDO']).'</td></tr>';
			}
		}
	}
}elseif($action=='pagos'){
	$data = $pagos->list_pagos();
	if(!empty($data)){
		$pagos=array();
		foreach ($data as $llave => $datos){
			foreach ($data[$llave] as $key => $value){
				$pagos[$key]=strtoupper($value);
			}
			$table['pago'][$llave]=$pagos;
		}
	}
}elseif($action=='Recibos'){
	if(isset($_POST['crecibo'])){
		$data=$cancelacion->get_canc($_POST['crecibo']);
		$cabecera=$data['cab'];
		$detalle=@$data['det'];
		$factura=@$data['fac'];
		$recibo1=array();
		$recibo2=array();
		$recibo3=array();
		foreach ($cabecera[0] as $key1 => $value1){
			$recibo1[$key1]=strtoupper($value1);
		}
		$table['cab']=$recibo1;
		if(!empty($factura)){
			foreach ($factura as $key => $value){
				foreach ($factura[$key] as $key1 => $value1){
					$recibo2[$key1]=strtoupper($value1);
				}
				$table['fac'][$key]=$recibo2;
			}
		}
		if(!empty($detalle)){
			foreach ($detalle as $key => $value){
				foreach ($detalle[$key] as $key1 => $value1){
					$recibo3[$key1]=strtoupper($value1);
				}
				$table['det'][$key]=$recibo3;
			}
		}
	}else{
		$titles='<tr><th>RECIBO</th><th>CEDULA</th><th>NOMBRES</th><th>MONTO</th><th>FECHA</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$status = ($_POST['obj']=='DEV') ? 'PROCESADO' : false ;
		$data = $cancelacion->list_canc($status);
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="'.$row_clas.'"><td class="'.$action.'_id">'.$value['CODIGO'].'</td><td>'.$value['CEDULA'].'</td><td>'.$value['CLIENTE'].'</td><td>'.numeros($value['MONTO_CANCELACION']).'</td><td>'.$value['FECHA_CANCELACION'].'</td></tr>';
			}
		}
	}
}elseif($action=='Devoluciones'){
	if(isset($_POST['crecibo'])){
		$data=$cancelacion->get_canc($_POST['crecibo']);
		$cabecera=$data['cab'];
		$detalle=@$data['det'];
		$factura=@$data['fac'];
		$recibo1=array();
		$recibo2=array();
		$recibo3=array();
		foreach ($cabecera[0] as $key1 => $value1){
			$recibo1[$key1]=strtoupper($value1);
		}
		$table['cab']=$recibo1;
		if(!empty($factura)){
			foreach ($factura as $key => $value){
				foreach ($factura[$key] as $key1 => $value1){
					$recibo2[$key1]=strtoupper($value1);
				}
				$table['fac'][$key]=$recibo2;
			}
		}
		if(!empty($detalle)){
			foreach ($detalle as $key => $value){
				foreach ($detalle[$key] as $key1 => $value1){
					$recibo3[$key1]=strtoupper($value1);
				}
				$table['det'][$key]=$recibo3;
			}
		}
	}else{
		$titles='<tr><th>RECIBO</th><th>CEDULA</th><th>NOMBRES</th><th>MONTO</th><th>FECHA</th></tr>';
		$table.='<table width="100%" class="table table-striped table-bordered table-hover datatables" id="'.$action.'_tbl"><thead>'.$titles.'</thead><tbody>';
		$data = $cancelacion->list_canc('ANULADO');
		if (!empty($data)){
			foreach ($data as $key => $value){
				$row++;
				$row_clas = ($row%2==0) ? 'odd' : 'even' ;
				$table.='<tr class="'.$row_clas.'"><td class="'.$action.'_id">'.$value['CODIGO'].'</td><td>'.$value['CEDULA'].'</td><td>'.$value['CLIENTE'].'</td><td>'.numeros($value['MONTO_CANCELACION']).'</td><td>'.$value['FECHA_CANCELACION'].'</td></tr>';
			}
		}
	}
}
$response=$table;
echo json_encode($response);
?>