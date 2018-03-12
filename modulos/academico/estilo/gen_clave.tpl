 <!-- START BLOCK : mensaje_log -->
<script>
$(document).ready(function(){
	/*Si caigo en el FORM desde un INSERT y obtengo error, muestro el Error*/
	$('#log').fadeIn('slown');
});
</script>
<!-- END BLOCK : mensaje_log -->
<script>
	$(document).ready(function(){
		var button = '';
		/*Controlo las acciones de cada Boton en el Form*/
		$(document).on('click', '.btn', function(e){
			button = $(this).attr('id');
			clear_log();
			switch (button){
				case 'bt_new':
					window.location.href = "?mod=ACADEMICO&submod=GEN_CLAVE";
				break;
				case 'bt_exit':
					window.location.href = "./";
				break;
				default:
					objeto='undefined';
			}
		});
	});
</script>
<!-- START BLOCK : form -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row"><div class="col-lg-12"><h2 class="page-header">{form_title}</h2></div></div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading titulares">{form_subtitle}</div>
					<div class="panel-body">
						<form role="form" name="inscripcion_form" id="inscripcion_form" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="col-xs-12 col-md-12">
									<div id="clave_g" class="form-group input-group">
										<label class="control-label" for="clave_c">CLAVE</label>
										<input id="clave_c" name="clave_c" type="text" class="form-control" value="{HASH}" readonly>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12"><p></p><div id="log" class="log alert {mensaje_class}">{mensaje}</div></div>
							</div>
							<div class="row">
								<div class="col-lg-12">
								<p style="text-align:center;">
									<a id="bt_new" class="btn btn-default form-btn"><i class="fa fa-file-o"></i> NUEVO</a>
									<a id="bt_exit" class="btn btn-default form-btn"><i class="fa fa-sign-out"></i> SALIR</a>
								</p>
								</div>
							</div>
						</form>
					</div>
				</div>			
			</div>
		</div>
	</div>
</div>
<!-- END BLOCK : form -->