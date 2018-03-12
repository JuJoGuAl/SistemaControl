<script>
	$(document).ready(function(){
		var button = '',objeto='';
		function imprime(){
			var alum='Alumno', valido=true;
			valido = valido && validar(alum);
			return valido;
		}
		/*Controlo las acciones de cada Boton en el Form*/
		$(document).on('click', '.btn', function(e){
			button = $(this).attr('id');
			clear_log();
			switch (button){
				case 'Alumnos_btn':
					objeto='Alumnos';
				break;
				case 'bt_new':
					window.location.href = "?mod=REPORTES&submod=REP_ALUM_CONSTANCIA";
				break;
				case 'bt_print':
				var report = (getUrlParameter('submod'));
					if(imprime()){ imprimir('./modulos/reportes/'+report+'.php?accion=print&code='+$('#Alumno_c').val()); }
				break;
				case 'bt_exit':
					window.location.href = "./";
				break;
				default:
					objeto='undefined';
			}
		});
		/*Abre el Modal segun el boton seleccionado, me llena la tabla y la muestra, si no consigue objetos llena un Modal con Info Generica*/
		$(document).on('click', '.search_data', function(e){
			$('#Modal_').modal('show');
			$('#Modal_Content').html('');
			$('#Modal_loader').show();
			$.ajax({
				url: './modulos/reportes/modal.php',
				type: 'POST',
				data: 'accion='+objeto,
				dataType:'json'
			})
			.done(function(data){
				$('#ModalLabel').html('LISTADO DE '+objeto.toUpperCase());
				$('#Modal_Text').html('Filtre según el criterio ingresándolo en el recuadro de <strong>Filtrar</strong>');
				$('#Modal_Text').show();
				$('#Modal_Content').html(data);
				$('#Modal_loader').hide();
				$('.datatables').DataTable({
					responsive: true
				});

				/*Coloca en verde la fila seleccionada, luego cierra el Modal*/
				$('.datatables tbody').on( 'click', 'tr', function (){
					$(this).addClass('success');
					$('#Modal_').modal('hide');
				});
			})
			.fail(function(){
				$('#ModalLabel').html('Error');
				$('#Modal_Text').hide();
				$('#Modal_Content').html('<i class="fa fa-warning"></i> Ocurrió un problema al intentar cargar la información, consulte a soporte');
				$('#Modal_loader').hide();
			});
		});
		/*Al cerrar el modal Toma la linea Verde y carga sus datos en el FORM*/
		$('#Modal_').on('hidden.bs.modal', function (){
			if ($('.datatables tbody tr.success').hasClass('success')){
				if($(this).find('.dataTables_empty').text()==''){
					//Si la fila que se selecciona posee datos
					if(objeto=='Alumnos'){
						clear_log();
						$('#Alumno_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#Alumno_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
					}
				}
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
								<div class="col-xs-8 col-md-8">
									<div id="Alumno_g" class="input-group">
										<label class="control-label" for="Alumno_c">ALUMNO</label>
										<input id="Alumno_c" name="Alumno_c" type="text" class="form-control hidden" value="{CODE_ALUMNO}">
										<input id="Alumno_n" name="Alumno_n" type="text" class="form-control" value="{CEDULA}" readonly>
										<span class="input-group-btn"><button id="Alumnos_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
									</div>
									<br>
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