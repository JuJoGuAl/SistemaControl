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
		$tpl->assign("form_title",'CIERRE DE CAJA');
		$tpl->assign("form_subtitle",'INGRESE LOS DATOS PARA GENERAR EL REPORTE');
		$tpl->assign("FECHA",date('d/m/Y'));
	}
}elseif($action=="print"){
	session_start();
	include_once("../../clases/class_permisos.php");
	include_once("../../clases/functions.php");
	$perm = new permisos();
	$perm_val = $perm->val_mod($_SESSION['user_log'],'REP_CAJA_CIERRE');
	if(!$perm_val){
		echo "<script>alert('NO TIENE PERMISO PARA ACCEDER A ESTE MODULO')</script>";
		echo "<script>window.close();</script>";
		exit;
	}else{
		ob_start();
		include_once("../../clases/class_cancelacion.php");
		$cancelacion = new cancelacion();
		$fecha=$_GET['date'];
		$data=$cancelacion->cierre_caja(date_to_mysql($fecha));
		//print_r($data);
		if($data==''){
			echo "<script>alert('NO EXISTEN DATOS PARA MOSTRAR EL REPORTE')</script>";
			echo "<script>window.close();</script>";
			exit;
		}else{
			require_once('../../clases/pdf/html2pdf.class.php');
			// cargo el HTML
			ob_start();
			include(dirname('__FILE__').'/html/rep_caja_cierre_html.php');
			$content = ob_get_clean();
			try{
				// init HTML2PDF
				$html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
				// display the full page
				$html2pdf->pdf->SetDisplayMode('fullpage');
				// convert
				$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
				// send the PDF
				$html2pdf->Output("Cierre_Caja_$fecha.pdf");
			}catch(HTML2PDF_exception $e){
				echo $e;
				exit;
			}
		}
	}
}
?>