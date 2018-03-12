<?php
session_start();
include_once("../../clases/class_permisos.php");
$perm = new permisos();
$perm_val = $perm->val_mod($_SESSION['user_log'],'INSCRIPCION');
if(!$perm_val){
	echo "<script>alert('No tiene permiso para acceder a este modulo')</script>";
	echo "<script>window.close();</script>";
	exit;
}else{
	ob_start();
	include_once("../../clases/class_inscripciones.php");
	$inscripcion = new inscripcion();
	$cmov=$_GET['code'];
	$data=$inscripcion->get_ins($cmov);
	if($data==''){
		echo "<script>alert('Transaccion: $cmov no encontrada')</script>";
		echo "<script>window.close();</script>";
		exit;
	}else{
		require_once('../../clases/pdf/html2pdf.class.php');
		// cargo el HTML
		$titulo="INSCRIPCION ACADEMICA";
		$transan="INSCRIPCION N°";
		$cabecera=$data['cab'];
		$detalle=$data['det'];
		$transac=$cabecera[0]['CODIGO'];
		$transaf=$cabecera[0]['FECHA_INS'];
		ob_start();
		include(dirname('__FILE__').'/html/inscripcion_recibo_html.php');
		$content = ob_get_clean();
		try{
			// init HTML2PDF
			$html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
			// display the full page
			$html2pdf->pdf->SetDisplayMode('fullpage');
			// convert
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			// send the PDF
			$html2pdf->Output('inscripcion_'.$transac.'.pdf');
		}catch(HTML2PDF_exception $e){
			echo $e;
			exit;
		}
	}
}
?>