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
		<h2 align="center">LISTADO DE ALUMNOS INSCRITOS</h2>
		<br>
		<table border="0.5" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:12%;" align="center"><strong>CEDULA</strong></td>
				<td class='cabecera' style="width:39%;" align="center"><strong>NOMBRE</strong></td>
				<td class='cabecera' style="width:39%;" align="center"><strong>CARRERA</strong></td>
				<td class='cabecera' style="width:10%;" align="center"><strong>PERIODO</strong></td>
			</tr>
		<?php
		$detalle=$data;
		foreach ($detalle as $key => $value){
			echo "<tr>
				<td align='center'>".$value['CEDULA']."</td>
				<td align='left'>".$value['ALUMNO']."</td>
				<td align='center'>".$value['CARRERA']."</td>
				<td align='center'>".$value['PERIODO']."</td>
			</tr>";
			}
		?>
		</table>
	</div>
</page>