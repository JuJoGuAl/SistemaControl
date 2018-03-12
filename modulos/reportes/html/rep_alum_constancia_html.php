<?php
include_once("../../css/reports.php");
include_once("../../clases/functions.php");
?>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
	<?php
	include_once("report_header_form.php");
	include_once("report_footer_html.php");
	?>
	<div class="cuerpo">
		<br><br>
		<h2 align="center">CONSTANCIA DE ESTUDIOS</h2>
		<p>&nbsp;</p>
		<p>Quienes suscriben, Director(a) <strong><?php echo $par[12]['VALOR']; ?></strong>, y el(la) Jefe de la Oficina Local de Control de Estudios, Estadísticas y Evaluación del Instituto Universitario de Tecnología "Juan Pablo Pérez Alfonzo" (IUTEPAL) ampliación San Francisco <strong><?php echo $par[13]['VALOR']; ?></strong>, por medio de la presente, hacen constar que el (la) Bachiller <strong><?php echo $data[0]['ALUMNO']; ?></strong> portador(a) de la C.I. <strong><?php echo $data[0]['CEDULA']; ?></strong>, actualmente cursa la carrera / especialidad: <strong><?php echo $data[0]['CARRERA']; ?></strong>. cuyas unidades curriculares inscritas fueron:</p>
		<br>
		<table border="0.5" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:15%;" align="center"><strong>CODIGO</strong></td>
				<td class='cabecera' style="width:40%;" align="center"><strong>UNIDAD CURRICULAR</strong></td>
				<td class='cabecera' style="width:15%;" align="center"><strong>SEMESTRE</strong></td>
				<td class='cabecera' style="width:15%;" align="center"><strong>SECCION</strong></td>
				<td class='cabecera' style="width:15%;" align="center"><strong>U.C.</strong></td>
			</tr>
		<?php
		$detalle=$data;
		foreach ($detalle as $key => $value){
			echo "<tr>
				<td align='center'>".$value['MATE_CODIGO']."</td>
				<td align='left'>".$value['MATE_NOMBRE']."</td>
				<td align='center'>".$value['MATE_SEM']."</td>
				<td align='center'>".$value['MATE_SEC']."</td>
				<td align='center'>".$value['MATE_UC']."</td>
			</tr>";
			}
		?>
		</table>
		<br>
		<p>constancia que se explide a petición de la parte interesada en San Francisco <?php echo strtoupper(fecha_reports()); ?></p>
	</div>
	<div class="pie">
		<table class="firmas" cellspacing='15' border='0' align="center">
			<tr>
				<td style="width:45%;" valign="top" align="center"><?php echo '<p><strong>'.$par[12]['VALOR'].'</strong></p>'; ?>DIRECTOR(A)</td>
				<td style="width:45%;" valign="top" align="center"><?php echo '<p><strong>'.$par[13]['VALOR'].'</strong></p>'; ?>JEFE DE LA  OFICINA  LOCAL DE CONTROL DE ESTUDIOS, ESTADÍSTICA Y EVALUACIÓN</td>
			</tr>
		</table>
	</div>
</page>