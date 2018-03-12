<?php
include_once("../../clases/class_parametros.php");
$parametros = new parametros();
$dir=$parametros->get_parameter(8);
$tel=$parametros->get_parameter(9);
?>
<page_footer>
	<div class="page_footer">
		<hr><?php echo "<p>".$dir[0]['VALOR']."</p><p>TELEFONO(S): ".$tel[0]['VALOR']."</p>"; ?>
	</div>
</page_footer>