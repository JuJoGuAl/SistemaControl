<?php
$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
if($action==""){
	include_once("./clases/class_permisos.php");
	$perm = new permisos();
	$perm_val = $perm->val_mod($_SESSION['user_log'],$_GET['submod']);
	if(empty($perm_val)){
		$tpl->gotoBlock("_ROOT");
		$tpl->newBlock("validar");
	}else{
		$action=(isset($_REQUEST['accion'])?$_REQUEST['accion']:'');
		$tpl->newBlock("form");
		$tpl->assign("form_title",'TRANSACCIONES DE COBROS');
		$tpl->assign("form_subtitle",'INGRESE LOS DATOS PARA GENERAR EL REPORTE');
	}
}elseif($action=="print"){
	session_start();
	include_once("../../clases/class_permisos.php");
	include_once("../../clases/functions.php");
	$perm = new permisos();
	$perm_val = $perm->val_mod($_SESSION['user_log'],'REP_COB_TRANS');
	if(!$perm_val){
		echo "<script>alert('NO TIENE PERMISO PARA ACCEDER A ESTE MODULO')</script>";
		echo "<script>window.close();</script>";
		exit;
	}else{
		ob_start();
		include_once("../../clases/class_cobros.php");
		$cobros = new cobros();
		$df1 = ($_GET['fec1']) ? date_to_mysql($_GET['fec1']) : false ;
		$df2 = ($_GET['fec2']) ? date_to_mysql($_GET['fec2']) : false ;
		$data=$con_mov=$cobros->transacciones($_GET['alum'],$_GET['per'],$df1,$df2);
		//print_r($data);
		if($data==''){
			echo "<script>alert('NO EXISTEN DATOS PARA MOSTRAR EL REPORTE')</script>";
			echo "<script>window.close();</script>";
			exit;
		}else{
			require_once('../../clases/pdf/html2pdf.class.php');
			// cargo el HTML
			ob_start();
			include(dirname('__FILE__').'/html/rep_cob_trans_html.php');
			$content = ob_get_clean();
			try{
				// init HTML2PDF
				$html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
				// display the full page
				$html2pdf->pdf->SetDisplayMode('fullpage');
				// convert
				$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
				// send the PDF
				$html2pdf->Output('Transacciones.pdf');
			}catch(HTML2PDF_exception $e){
				echo $e;
				exit;
			}
		}
	}
}
?>