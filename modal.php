<?php
ob_start();
session_start();
include("./clases/class_TemplatePower.php");
include("./clases/functions.php");
$mod=strtolower($_GET['mod']);
$page=strtolower($_GET['pag']);
$tpl = new TemplatePower( "./modulos/$mod/estilo/$page.tpl" );
$tpl->prepare();
$tpl->assign("accion",@$_GET['accion']);
$tpl->assign("id",@$_GET['id']);
$tpl->assign("obj",@$_GET['obj']);

include("./modulos/$mod/$page.php");

$tpl->printToScreen();
ob_end_flush();
?>