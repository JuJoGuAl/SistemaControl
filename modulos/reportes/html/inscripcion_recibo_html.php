<?php
include_once("../../css/reports.php");
include_once("../../clases/functions.php");
?>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
	<?php
	include_once("report_header_html.php");
	include_once("report_footer_html.php");
	?>
	<div class="cuerpo">
		<table border="0" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:20%;" align="center"><strong>CEDULA</strong></td>
				<td class='cabecera' style="width:40%;" align="center"><strong>NOMBRES</strong></td>
				<td class='cabecera' style="width:40%;" align="center"><strong>CARRERA / ESPECIALIDAD</strong></td>
			</tr>
			<tr>
				<td align="center"><?php echo $cabecera[0]['CEDULA']; ?></td>
				<td align="center"><?php echo $cabecera[0]['ALUMNO']; ?></td>
				<td align="center"><?php echo $cabecera[0]['CARRERA']; ?></td>
			</tr>
			<tr>
				<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
			</tr>
			<tr>
				<td class='cabecera' style="width:20%;" align="center"><strong>PERIODO</strong></td>
				<td class='cabecera' style="width:40%;" align="center"><strong>CONVENIO</strong></td>
			</tr>
			<tr>
				<td align="center"><?php echo $cabecera[0]['PERIODO']; ?></td>
				<td align="center"><?php echo ($cabecera[0]['CCONVENIO']==0) ? "SIN CONVENIO" : $cabecera[0]['CONVENIO'] ; ?></td>
			</tr>
		</table>
		<br><br>
		<table border="0.5" cellspacing="0">
			<tr>
				<td class='cabecera' style="width:15%;" align="center"><strong>CODIGO</strong></td>
				<td class='cabecera' style="width:40%;" align="center"><strong>UNIDAD CURRICULAR</strong></td>
				<td class='cabecera' style="width:15%;" align="center"><strong>SEMESTRE</strong></td>
				<td class='cabecera' style="width:15%;" align="center"><strong>SECCION</strong></td>
				<td class='cabecera' style="width:15%;" align="center"><strong>U.C.</strong></td>
			</tr>
		<?php
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
		<table cellspacing='0' border='0'>
			<tr>
				<td style="width:15%;" align="center">&nbsp;</td>
				<td style="width:40%;" align="center">&nbsp;</td>
				<td style="width:15%;" align="center">&nbsp;</td>
				<td style="width:15%;" align="center">&nbsp;</td>
				<td style="width:15%;" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td colspan='2' style='padding: 2px;' align='right'><strong>MATERIAS INSCRITAS: </strong></td>
				<td style='padding: 2px;' align='right'><strong><?php echo numeros($cabecera[0]['MATS']); ?></strong></td>
				<td style='padding: 2px;' align='right'><strong>TOTAL U.C.: </strong></td>
				<td style='padding: 2px;' align='right'><strong><?php echo numeros($cabecera[0]['UCS']); ?></strong></td>
			</tr>
		</table>		
	</div>
	<div class="pie">
		<table class="firmas" cellspacing='15' border='0'>
			<tr>
				<td class='cabecera' style="width:33%;" align="center"><strong>FIRMA DEL ALUMNO</strong></td>
				<td class='cabecera' style="width:33%;" align="center"><strong>FIRMA DEL ASESOR ACADEMICO</strong></td>
				<td class='cabecera' style="width:33%;" align="center"><strong>FIRMA Y SELLO DE CONTROL DE ESTUDIOS</strong></td>
			</tr>
		</table>
		<?php include_once("report_auditoria.php"); ?>
	</div>
</page>