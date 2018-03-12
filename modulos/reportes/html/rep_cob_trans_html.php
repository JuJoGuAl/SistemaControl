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
		<h2 align="center">TRANSACCIONES DE COBROS</h2>
		<table border="0" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:8%;" align="center">ALUMNO</td>
				<td style="width:35%;" align="center"><?php echo ($_GET['alum']) ? $data[0]['CLIENTE'] : "TODOS" ; ?></td>
				<td class='cabecera' style="width:7%;" align="center">BANCO</td>
				<td style="width:15%;" align="center"><?php echo ($_GET['per']) ? $data[0]['PERIODO'] : "TODOS" ; ?></td>
				<td class='cabecera' style="width:7%;" align="center">FECHA</td>
				<td style="width:28%;" align="center"><?php echo ($_GET['fec1']) ? "DEL ".$_GET['fec1']." AL ".$_GET['fec2'] : "TODOS" ; ?></td>
			</tr>
		</table>
		<br>
		<table border="0.5" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:10%;" align="center">COBRO</td>
				<td class='cabecera' style="width:10%;" align="center">FECHA</td>
				<td class='cabecera' style="width:15%;" align="center">CEDULA</td>
				<td class='cabecera' style="width:30%;" align="center">CLIENTE</td>
				<td class='cabecera' style="width:15%;" align="center">CONCEPTO</td>
				<td class='cabecera' style="width:8%;" align="center">TOTAL</td>
			</tr>
		<?php
		$detalle=$data;
		$total=0;
		foreach ($detalle as $key => $value){
			$total = $total + (($value['ESTATUS']=="ANULADA") ? ($value['TOTAL']*-1) : $value['TOTAL']);
			echo "<tr>
				<td align='center'>".$value['CFACTURA']."</td>
				<td align='center'>".$value['FECHA_FACTURA']."</td>
				<td align='center'>".$value['CEDULA']."</td>
				<td align='center'>".$value['CLIENTE']."</td>
				<td align='center'>".$value['CONCEPTO']."</td>
				<td align='center'>".numeros(($value['ESTATUS']=="ANULADA") ? ($value['TOTAL']*-1) : $value['TOTAL'])."</td>
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