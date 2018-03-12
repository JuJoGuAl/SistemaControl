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
		<p>QUIENES  SUSCRIBEN, DIRECTOR(A) <strong><?php echo $par[12]['VALOR']; ?></strong>, Y EL(LA) JEFE DE LA OFICINA LOCAL DE CONTROL DE ESTUDIOS, ESTADÍSTICA Y EVALUACIÓN DEL INSTITUTO UNIVERSITARIO DE TECNOLOGÍA “JUAN PABLO PÉREZ ALFONZO” (IUTEPAL) AMPLIACIÓN SAN FRANCISCO <strong><?php echo $par[13]['VALOR']; ?></strong>, POR MEDIO DE LA PRESENTE, HACEN CONSTAR QUE EL (LA) BACHILLER <strong><?php echo $data[0]['ALUMNO']; ?></strong> PORTADOR(A) DE LA C.I. <strong><?php echo $data[0]['CEDULA']; ?></strong>, ACTUALMENTE CURSA LA CARRERA / ESPECIALIDAD: <strong><?php echo $data[0]['CARRERA']; ?></strong>. CUYAS UNIDADES CURRICULARES INSCRITAS FUERON:</p>
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
		<p>CONSTANCIA QUE SE EXPIDE A PETICION DE LA PARTE INTERESADA EN SAN FRANCISCO <?php echo strtoupper(fecha_reports()); ?></p>
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