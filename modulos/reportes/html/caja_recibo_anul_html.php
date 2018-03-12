<?php
include_once("../../css/reports.php");
include_once("../../clases/functions.php");
?>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
	<?php
	include_once("report_header_caja_html.php");
	include_once("report_footer_html.php");
	?>
	<div class="cuerpo">
		<p style="font-size: 25px;text-align:center;color:red">- RECIBO ANULADO -</p>
		<p>Hemos recibido de: <strong><?php echo $cabecera[0]['CLIENTE']; ?></strong>, titular de la CI: <strong><?php echo $cabecera[0]['CEDULA']; ?></strong>, por Abono o Pago de Factura(s):</p>
		<table border="0.5" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:25%;"><strong>FACTURA</strong></td>
				<td class='cabecera' style="width:15%;"><strong>PERIODO</strong></td>
				<td class='cabecera' style="width:15%;"><strong>FECHA</strong></td>
				<td class='cabecera' style="width:15%;"><strong>MONTO</strong></td>
				<td class='cabecera' style="width:15%;"><strong>ABONO</strong></td>
				<td class='cabecera' style="width:15%;"><strong>SALDO</strong></td>
			</tr>
			<?php
			foreach ($factura as $key => $value) {
				echo "<tr>
					<td align='left'>".$value['CODE_FACTURA']."</td>
					<td align='center'>".$value['PERIODO']."</td>
					<td align='center'>".$value['FECHA_FACTURA']."</td>
					<td align='right'>".numeros($value['NETO_FACTURA'])."</td>
					<td align='right'>".numeros($value['MONTO_FACTURA'])."</td>
					<td align='right'>".numeros($value['SALDO_FACTURA'])."</td>
				</tr>";}
			?>
			</table>
			<table cellspacing='0'>
			<tr>
				<td style='width: 25%;text-align:center'>&nbsp;</td>
				<td style='width: 15%;text-align:center'>&nbsp;</td>
				<td style='width: 15%;text-align:center'>&nbsp;</td>
				<td style='width: 15%;text-align:center'>&nbsp;</td>
				<td style='width: 15%;text-align:center'>&nbsp;</td>
				<td style='width: 15%;text-align:center'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='2' style='padding: 2px;' align='right'><strong>FACTURAS PAGADAS: </strong></td>
				<td style='padding: 2px;' align='right'><strong><?php echo numeros($cabecera[0]['FACTURAS']); ?></strong></td>
				<td colspan='2' style='padding: 2px;' align='right'><strong>TOTAL PAGADO: </strong></td>
				<td style='padding: 2px;' align='right'><strong><?php echo numeros($cabecera[0]['MONTO_CANCELACION']); ?></strong></td>
			</tr>
		</table>
		<p>Forma(s) de Pago(s):</p>
		<table border="0.5" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:25%;"><strong>FORMA DE PAGO</strong></td>
				<td class='cabecera' style="width:25%;"><strong>MONTO</strong></td>
				<td class='cabecera' style="width:25%;"><strong>DOCUMENTO</strong></td>
				<td class='cabecera' style="width:25%;"><strong>FECHA</strong></td>
			</tr>
			<?php
			foreach ($detalle as $key => $value) {
				echo "<tr>
					<td align='left'>".$value['TIPO']."</td>
					<td align='center'>".numeros($value['MONTO_PAGO'])."</td>
					<td align='center'>".$value['DOCUMENTO']."</td>
					<td align='center'>".$value['FECHA_DET']."</td>
				</tr>";}
			?>
		</table>
		<p style="font-size: 25px;text-align:center;color:red">- RECIBO ANULADO -</p>		
	</div>
	<div class="pie">
		<table class="firmas" cellspacing='15' border='0' align="center">
			<tr>
				<td class='cabecera' style="width:33%;" align="center"><strong>DPTO DE ADMINISTRACION</strong></td>
			</tr>
		</table>
		<?php include_once("report_auditoria.php"); ?>
	</div>
</page>