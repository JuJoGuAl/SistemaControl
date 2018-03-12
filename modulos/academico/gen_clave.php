<?php
include_once("./clases/class_permisos.php");
include_once("./clases/functions.php");
include_once("./clases/class_hash.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
if(empty($perm_val)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}else{
	$claves = new hash();
	$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
	$tpl->newBlock("form");
	$tpl->assign("form_title",'CLAVE PARA UC EXTRAS');
	$tpl->assign("form_subtitle",'MODULO PARA GENERAR CLAVES PARA LA INSCRIPCION DE UC EXTRAS');
	$clave = $claves->new_hash(8);
	if($action==''){
		if ($clave['titulo']=="OK"){
			$tpl->assign("HASH",$clave['texto']);
		}else{
			$tpl->assign("mensaje",$clave['texto']);
			$tpl->assign("mensaje_class","alert-danger");
			$tpl->newBlock("mensaje_log");
		}
	}
}
?>