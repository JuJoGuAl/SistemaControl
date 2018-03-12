<script>
	$(document).ready(function(){
		var button = '',objeto='';
		function imprime(){
			var fecha='fecha', valido=true;
			valido = valido && validar(fecha);
			return valido;
		}
		$('#fecha_c').datepicker({
	      format: "dd/mm/yyyy",
	      autoclose: true,
	      todayHighlight: true,
	      language: "es"
	    });
		/*Controlo las acciones de cada Boton en el Form*/
		$(document).on('click', '.btn', function(e){
			button = $(this).attr('id');
			clear_log();
			switch (button){
				case 'bt_new':
					window.location.href = "?mod=REPORTES&submod=REP_CAJA_CIERRE";
				break;
				case 'bt_print':
				var report = (getUrlParameter('submod'));
					if(imprime()){ imprimir('./modulos/reportes/'+report+'.php?accion=print&date='+$('#fecha_c').val()); }
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
								<div class="col-xs-4 col-md-4">
									<div id="fecha_g" class="form-group input-group">
										<label class="control-label" for="fecha_c">FECHA</label>
										<input id="fecha_c" name="fecha_c" type="text" class="form-control" value="{FECHA}">
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
									<a id="bt_print" class="btn btn-default form-btn"><i class="fa fa-print"></i> IMPRIMIR</a>
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
<div class="modal fade" id="Modal_" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="ModalLabel">Error</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="panel panel-default">
						<div class="panel-heading" id="Modal_Text">No se asignó datos para el Modal!</div>
						<div class="panel-body">
							<div id="Modal_loader" style="display: none; text-align: center;">
								<img src="./img/loader.gif">
							</div>
							<div class="dataTable_wrapper" id="Modal_Content">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>