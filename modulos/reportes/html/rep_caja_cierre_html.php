<?php
include_once("../../css/reports.php");
include_once("../../clases/functions.php");
?>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
	<?php
	include_once("report_header_list.php");
	include_once("report_footer_html.php");
	?>
	<div class="cuerpo">
		<h2 align="center" style="margin-bottom:0px;">CIERRE DE CAJA DIARIO</h2><h3 style="margin-top:0px;" align="center"><?php echo $fecha; ?></h3>
		<br>
		<table border="0.5" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:25%;" align="center">RECIBO</td>
				<td class='cabecera' style="width:45%;" align="center">STATUS</td>
				<td class='cabecera' style="width:30%;" align="center">MONTO</td>
			</tr>
		<?php
		$cab=$data['cab'];
		$det=$data['det'];
		$total=0;
		foreach ($cab as $key => $value){
			$total = $total + (($value['ESTATUS']=="ANULADO") ? ($value['MONTO_TOTAL']*-1) : $value['MONTO_TOTAL']);
			echo "<tr>
				<td align='center'>".$value['CODIGO']."</td>
				<td align='center'>".$value['ESTATUS']."</td>
				<td align='right'>".numeros(($value['ESTATUS']=="ANULADO") ? ($value['MONTO_TOTAL']*-1) : $value['MONTO_TOTAL'])."</td>
			</tr>
			<tr style='border: 0px;'>
				<td colspan='3' align='right' style='border: 0px;'>
					<table border='0' cellspacing='0' style='width:100%;'>
						<tr>
							<td class='cabecera' style='width:25%;' align='center'>DOCUMENTO</td>
							<td class='cabecera' style='width:45%;' align='center'>FORMA DE PAGO</td>
							<td class='cabecera' style='width:30%;' align='center'>MONTO</td>
						</tr>";
						foreach ($det as $key1 => $value1){
							if ($value['CODIGO']==$value1['CODIGO']){
								echo "<tr>
									<td align='center'>".$value1['DOCUMENTO']."</td>
									<td align='center'>".$value1['TIPO_PAGO']."</td>
									<td align='right'>".numeros(($value['ESTATUS']=="ANULADO") ? ($value1['MONTO']*-1) : $value1['MONTO'])."</td>
								</tr>";
							}
						}
					echo "</table>
				</td>
			</tr>";
			}
		?>
		<tr>
			<td colspan="2" class='cabecera' style="width:70%;" align="center">TOTAL DE CAJA</td>
			<td class='cabecera' style="width:30%;" align="center"><?php echo numeros($total); ?></td>
		</tr>
		</table>
	</div>
</page>