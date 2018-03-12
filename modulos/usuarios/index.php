<?php
ob_start();
session_start();
$action=(isset($_POST['action'])?$_POST['action']:'');
if(!$action){
}elseif($action == "val_log"){
	include_once("../../clases/class_permisos.php");
	$user = new permisos();
	echo $valido = $user->val_log($_POST['username'], $_POST['pass']);
}elseif($action == "logout"){
	ob_start();
	session_start();
	session_destroy();
}
?>