<?php
date_default_timezone_set('America/Caracas');
ob_start();
session_start();
include("./clases/class_TemplatePower.php");
include("./clases/functions.php");
$tpl = new TemplatePower("./estilos/cuerpo.tpl");
$mod_error="./estilos/404.tpl";
if(!isset($_SESSION['user_log'])){
	$tpl->assignInclude("contenido","estilos/login.tpl");
}else{
	$tpl->assignInclude("menu_user","estilos/menu_user.tpl");
	if(!isset($_GET['mod'])){
		$mod='home';
		$submod='home';
	}else{
		$mod=strtolower($_GET['mod']);
		if(!isset($_GET['submod'])){
			$submod='home';
		}else{
			$submod=strtolower($_GET['submod']);
		}
	}
	$file_tpl="./modulos/".$mod."/estilo/index.tpl";
	$file_sub_tpl="./modulos/".$mod."/estilo/".$submod.".tpl";
	//echo $file_sub_tpl;
	$tpl->assignInclude("menu", "estilos/menu.tpl");
	if($mod=="home"){//pregunto si estoy en submodulo
		if (file_exists($file_tpl)){
			$tpl->assignInclude("contenido",$file_tpl);
		}else{
			$tpl->assignInclude("contenido",$mod_error);
		}
	}else{//si estoy en submodulo, pregunto si existe
		if (file_exists($file_sub_tpl)){
			$tpl->assignInclude("contenido",$file_sub_tpl);
		}else{
			$tpl->assignInclude("contenido",$mod_error);
		}
	}
}
$tpl->prepare();
include_once("./clases/class_parametros.php");
$parametros = new parametros();
$par=$parametros->get_parameters();
$tpl->assign("icono",$par[6]['VALOR']);
$tpl->assign("institucion",$par[2]['VALOR']);
$tpl->assign("logo",$par[3]['VALOR']);
if(!isset($_SESSION['user_log'])){	
}else{
	include("./clases/class_permisos.php");
	$user= new permisos;
	$usuario=$user->get_user($_SESSION['user_log']);
	$nombre=$usuario[0]['EMPLEADO'];
	$tpl->assign("user",  $nombre);
	$file_php="./modulos/".$mod."/index.php";
	$file_sub_php="./modulos/".$mod."/".$submod.".php";
	//echo "<br>".$file_sub_php;
	include('./modulos/menu.php');
	if($mod=="home"){//pregunto si estoy en submodulo
		if (file_exists($file_php)){
			include($file_php);
		}
	}else{//si estoy en submodulo, pregunto si existe
		if (file_exists($file_sub_php)){
			include($file_sub_php);
		}
	}
}
$tpl->printToScreen();
ob_end_flush();
?>