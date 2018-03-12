<?php
include_once("../../clases/class_parametros.php");
$parametros = new parametros();
$log=$parametros->get_parameter(12);
?>
<page_header>
	<table class="page_header" cellspacing="0">
		<tr>
			<td style="width:30%;" align="left"><?php echo '<img class="logo" src="../../img/'.$log[0]['VALOR'].'">'; ?></td>
			<td style="width:40%;" align="center"><h3><?php echo $titulo; ?></h3></td>
			<td style="width:30%;" align="rigth" class="minitable">
				<p><strong><?php echo $transan;?></strong></p>
				<p><?php echo $transac;?></p>
				<p><strong>FECHA: </strong><?php echo $transaf;?></p>
			</td>
		</tr>
	</table>
</page_header>