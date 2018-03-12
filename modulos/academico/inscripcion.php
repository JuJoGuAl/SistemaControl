<?php
include_once("./clases/class_permisos.php");
include_once("./clases/class_inscripciones.php");
include_once("./clases/class_cobros.php");
include_once("./clases/class_periodos.php");
include_once("./clases/class_convenios.php");
include_once("./clases/functions.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$inscripcion = new inscripcion();
	$periodos = new periodos();
	$cobros = new cobros();
	$convenios = new convenios();
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->newBlock("form");
	$tpl->assign("form_title",'INSCRIPCIONES');
	$tpl->assign("form_subtitle",'UTILICE ESTE MODULO PARA INSCRIBIR ALUMNOS');
	$convenios_list = $convenios->list_con(1);
	if($action==''){
		$tpl->assign("FECHA_INS",date('d/m/Y'));
		$tpl->assign("ESTATUS",'PENDIENTE');
		$tpl->assign("accion",'save_new');
		if(!empty($convenios_list)){
			foreach ($convenios_list as $llave => $datos) {
				$tpl->newBlock("convenios_det");
				foreach ($convenios_list[$llave] as $key => $value){
					$tpl->assign($key,$value);
				}
			}
		}
	}elseif($action=="save_new"){
		$datos=array();
		$detalles=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, $_Carreras_c);
		array_push($datos, $_Periodos_c);
		array_push($datos, $_Alumno_c);
		array_push($datos, date_to_mysql($_f_ins_c));
		array_push($datos, $_convenio_c);
		array_push($detalles, $_cmate);
		array_push($detalles, $_mat_sec);
		$response=$inscripcion->new_ins($datos,$detalles);
		if($response['titulo']=="ERROR"){
			$tpl->assign("FECHA_INS",date('d/m/Y'));
			$tpl->assign("ESTATUS",'PENDIENTE');
			$tpl->assign("accion",'save_new');
			//VERIFICAR SI MUESTRO EL ERROR SQL O MUESTRO UN ERROR GENERAL
			$tpl->assign("mensaje",$response['texto']);
			$tpl->assign("mensaje_class","alert-danger");
		}else{
			$new_mov=$response['texto'];
			if($_convenio_c==0){
				$datos1=array();
				$detalles1=array();
				$data2 = $periodos->get_pers(1,"I");
				array_push($datos1, $_Alumno_c);
				array_push($datos1, date_to_mysql($_f_ins_c));
				array_push($datos1, $_Periodos_c);
				array_push($datos1, 'FAC');
				array_push($datos1, 'PROCESADA');
				array_push($datos1, $data2[0]['MSEMESTRE']);//BRUTO
				array_push($datos1, 0);//IMP
				array_push($datos1, 0);//DESC
				array_push($datos1, $data2[0]['MSEMESTRE']);//NETO
				array_push($datos1, 0);//ABONADO
				array_push($datos1, 0);//ORIGEN
				for ($i=0; $i<1; $i++){
					$_con[$i]=10;
					$_can[$i]=1;
					$_pre[$i]=$data2[0]['MSEMESTRE'];
					$_tot[$i]=$data2[0]['MSEMESTRE'];
					$_det[$i]=0;
				}
				array_push($detalles1, $_con);
				array_push($detalles1, $_can);
				array_push($detalles1, $_pre);
				array_push($detalles1, $_tot);
				array_push($detalles1, $_det);//ORIGEN_DET
				$response=$cobros->new_fac($datos1,$detalles1);
			}
			if($response['titulo']=="ERROR"){
				$tpl->assign("FECHA_INS",date('d/m/Y'));
				$tpl->assign("ESTATUS",'PENDIENTE');
				$tpl->assign("accion",'save_new');
				//VERIFICAR SI MUESTRO EL ERROR SQL O MUESTRO UN ERROR GENERAL
				$tpl->assign("mensaje",$response['texto']);
				$tpl->assign("mensaje_class","alert-danger");
			}else{
				$mensaje="INSCRIPCION REGISTRADA!";
				$action = "block";
				$tpl->assign("accion",$action);
				$tpl->assign("id",$new_mov);
				$tpl->assign("mensaje",$mensaje);
				$tpl->assign("mensaje_class","alert-success");
				$tpl->assign("ESTATUS",'PROCESADA');
				$data=$inscripcion->get_ins($new_mov);
				$cabecera=$data['cab'];
				$detalle=$data['det'];
				foreach ($cabecera as $key => $value){
					foreach ($cabecera[$key] as $key1 => $value1){
						$tpl->assign($key1,$value1);
					}
				}
				foreach ($detalle as $key => $value){
					$tpl->newBlock("mat_det");
					foreach ($detalle[$key] as $key1 => $value1){
						$tpl->assign($key1,$value1);
					}
				}
			}
		}
		$tpl->newBlock("mensaje_log");
	}
}
?>