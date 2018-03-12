<script>
	$(document).ready(function(){
		var button = '',objeto='';
		/*Controlo las acciones de cada Boton en el Form*/
		$(document).on('click', '.btn', function(e){
			button = $(this).attr('id');
			clear_log();
			switch (button){
				case 'Carrera_btn':
					objeto='Carreras';
				break;
				case 'Seccion_btn':
					objeto='Secciones';
				break;
				case 'Materia_btn':
					objeto='Materias';
				break;
				case 'bt_new':
					window.location.href = "?mod=REPORTES&submod=REP_ALUM_INS";
				break;
				case 'bt_print':
				var report = (getUrlParameter('submod'));
					imprimir('./modulos/reportes/'+report+'.php?accion=print&car='+$('#Carreras_c').val()+'&sec='+$('#Secciones_c').val()+'&mat='+$('#Materias_c').val());
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
			if(objeto=="Secciones" || objeto=="Materias"){
				var det=$('#Carreras_c').val();
			}
			$.ajax({
				url: './modulos/reportes/modal.php',
				type: 'POST',
				data: 'accion='+objeto+'&det='+det,
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
					clear_log();
					$('#'+objeto+'_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
					$('#'+objeto+'_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
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
								<div class="col-xs-4 col-md-4">
									<div id="Carreras_g" class="input-group">
										<label class="control-label" for="Carreras_c">CARRERA</label>
										<input id="Carreras_c" name="Carreras_c" type="text" class="form-control hidden">
										<input id="Carreras_n" name="Carreras_n" type="text" class="form-control" readonly>
										<span class="input-group-btn"><button id="Carrera_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
									</div>
									<br>
								</div>
								<div class="col-xs-4 col-md-4">
									<div id="Secciones_g" class="input-group">
										<label class="control-label" for="Secciones_c">SECCION</label>
										<input id="Secciones_c" name="Secciones_c" type="text" class="form-control hidden">
										<input id="Secciones_n" name="Secciones_n" type="text" class="form-control" readonly>
										<span class="input-group-btn"><button id="Seccion_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
									</div>
									<br>
								</div>
								<div class="col-xs-4 col-md-4">
									<div id="Materias_g" class="input-group">
										<label class="control-label" for="Materias_c">MATERIA</label>
										<input id="Materias_c" name="Materias_c" type="text" class="form-control hidden">
										<input id="Materias_n" name="Materias_n" type="text" class="form-control" readonly>
										<span class="input-group-btn"><button id="Materia_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
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