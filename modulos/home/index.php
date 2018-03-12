<?php
include_once("./clases/class_permisos.php");
$menus = new permisos;
$menu = $menus->get_menu($_SESSION['user_log']);
if(empty($menu)){
	$tpl->gotoBlock("_ROOT");
	$tpl->newBlock("validar");
}
//Controla el contenido de la pantalla sin opciones
?>