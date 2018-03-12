<?php
include_once("../../clases/class_parametros.php");
$parametros = new parametros();
$par=$parametros->get_parameters();
?>
<page_header>
	<table class="page_header" cellspacing="0">
		<tr>
			<td style="width:25%;" align="center">
				<?php
				echo '<img class="logo" src="../../img/'.$par[10]['VALOR'].'">';
				?>
			</td>
			<td style="width:75%;" align="center">
				<p>
				<?php
				echo "<p>".$par[0]['VALOR']."</p><p>SUCURSAL: ".$par[11]['VALOR']."</p><p><strong>".$par[2]['VALOR']."</strong></p><p>RIF: ".$par[1]['VALOR']."</p>";
				?>
				</p>
			</td>
		</tr>
	</table>
</page_header>