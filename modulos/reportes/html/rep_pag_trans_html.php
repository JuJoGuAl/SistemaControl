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
		<h2 align="center">TRANSACCIONES DE PAGOS</h2>
		<table border="0" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:8%;" align="center">ALUMNO</td>
				<td style="width:35%;" align="center"><?php echo ($_GET['alum']) ? $data[0]['CLIENTE'] : "TODOS" ; ?></td>
				<td class='cabecera' style="width:7%;" align="center">BANCO</td>
				<td style="width:15%;" align="center"><?php echo ($_GET['ban']) ? $data[0]['BANCO'] : "TODOS" ; ?></td>
				<td class='cabecera' style="width:7%;" align="center">FECHA</td>
				<td style="width:28%;" align="center"><?php echo ($_GET['fec1']) ? "DEL ".$_GET['fec1']." AL ".$_GET['fec2'] : "TODOS" ; ?></td>
			</tr>
		</table>
		<br>
		<table border="0.5" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:10%;" align="center">RECIBO</td>
				<td class='cabecera' style="width:15%;" align="center">FECHA RECIBO</td>
				<td class='cabecera' style="width:15%;" align="center">CEDULA</td>
				<td class='cabecera' style="width:30%;" align="center">CLIENTE</td>
				<td class='cabecera' style="width:15%;" align="center">BANCO</td>
				<td class='cabecera' style="width:15%;" align="center">MONTO</td>
			</tr>
		<?php
		$detalle=$data;
		$total=0;
		foreach ($detalle as $key => $value){
			$total = $total + (($value['ESTATUS']=="ANULADO") ? ($value['MONTO']*-1) : $value['MONTO']);
			echo "<tr>
				<td align='center'>".$value['CCANCELACION']."</td>
				<td align='center'>".$value['FECHA_CANCELACION']."</td>
				<td align='center'>".$value['CEDULA']."</td>
				<td align='center'>".$value['CLIENTE']."</td>
				<td align='center'>".$value['BANCO']."</td>
				<td align='center'>".numeros(($value['ESTATUS']=="ANULADO") ? ($value['MONTO']*-1) : $value['MONTO'])."</td>
			</tr>";
			}
		?>
			<tr>
				<td colspan="5" class='cabecera' align="center">TOTAL GENERAL</td>
				<td class='cabecera' align="center"><?php echo numeros($total); ?></td>
			</tr>
		</table>
	</div>
</page>