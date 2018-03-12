<?php
$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
$selected = 'selected="selected"';
if($action=="save_new" || $action=="save_edt"){
	include_once("../../clases/class_permisos.php");
	include_once("../../clases/functions.php");
	session_start();
	$perm = new permisos();
	if($action=="save_new"){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper(htmlentities($_user_c,ENT_QUOTES,"UTF-8")));
		array_push($datos, md5($_pass1_c));
		array_push($datos, strtoupper(htmlentities($_nombre_c,ENT_QUOTES,"UTF-8")));
		array_push($datos, 1);//ACTIVO AL SER NUEVO
		$response=$perm->new_user($datos,@$_POST['permisos_c']);
	}elseif($action=="save_edt"){
		$datos=array();
		extract($_POST, EXTR_PREFIX_ALL, "");
		array_push($datos, strtoupper(htmlentities($_user_c,ENT_QUOTES,"UTF-8")));
		if($_pass1_c){$pas = md5($_pass1_c);}
		array_push($datos, (@$pas));
		array_push($datos, strtoupper(htmlentities($_nombre_c,ENT_QUOTES,"UTF-8")));
		array_push($datos, $_status_c);//STATUS
		$response=$perm->edit_user($_code,$datos,@$_POST['permisos_c']);
	}
	echo json_encode($response);
}else{
	include_once("./clases/class_permisos.php");
	include_once("./clases/functions.php");
	$perm = new permisos();
	$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
	if(empty($perm_val)){
		$tpl->gotoBlock("_ROOT");
		$tpl->newBlock("validar");
	}else{
		$row_clas="";
		$row=0;
		$mods=$perm->list_mods();
		if($action==''){
			$tpl->assign("accion",'save_new');
			$tpl->assign("form_title",'CREAR UN USUARIO');
			$tpl->assign("form_subtitle",'CREE UN USUARIO PARA UTILIZAR EL SISTEMA');
			$tpl->newBlock("val_clave");
			if(!empty($mods)){
				foreach ($mods as $llave => $datos) {
					$tpl->newBlock("perm");
					foreach ($mods[$llave] as $key => $value){
						$tpl->assign($key,strtoupper($value));
					}
				}
			}
		}elseif($action=='edt'){
			$tpl->assign("accion",'save_edt');
			$tpl->assign("form_title",'EDITAR UN USUARIO');
			$tpl->assign("form_subtitle",'ACTUALICE LOS DATOS DEL USUARIO');
			$tpl->assign("code",$_GET['id']);
			$data=$perm->get_user($_GET['id']);
			$tpl->assign("read","readonly");
			$user=$data[0];
			$perm=$data[1];
			if(!empty($data)){
				foreach ($user as $llave => $datos){
					$tpl->assign($llave,$datos);
				}
				//ACTIVO STATUS QUE ESTA BLOQUEADO EN CREACION Y MUESTRO SU VALOR
				$tpl->newBlock("st_block");
				foreach ($array_status1 as $key => $value){
					$tpl->newBlock("st_det");
					$tpl->assign("st_code",$key);
					$tpl->assign("st_name",$value);
					if($user['ESTATUS'] == $key){
						$tpl->assign("selected",$selected);
					}
				}
				if(!empty($mods)){
					foreach ($mods as $llave => $datos1) {
						$tpl->newBlock("perm");
						if(!empty($perm)){
							foreach ($perm as $key1 => $value1){
								if($value1['CMODULO'] == $datos1['CMODULO']){
									$tpl->assign("selected",$selected);
								}
							}
						}
						foreach ($mods[$llave] as $key => $value){
							$tpl->assign($key,strtoupper($value));
						}
					}
				}
				//ACTIVA LA INFORMACION DE AUDITORIA
				$tpl->newBlock("det_datos");
				$tpl->assign("USUARIO_CREADO",$user['USUARIO_CREADO']);
				$tpl->assign("USUARIO_MODIFICACION",$user['USUARIO_MODIFICACION']);
			}
		}
	}
}
?>