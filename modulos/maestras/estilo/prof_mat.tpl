 <!-- START BLOCK : mensaje_log -->
<script>
$(document).ready(function(){
	/*Si caigo en el FORM desde un INSERT y obtengo error, muestro el Error*/
	$('#log').fadeIn('slown');
	$('.nav-tabs a[href="#list_prof_mat"]').tab('show');
});
</script>
<!-- END BLOCK : mensaje_log -->
<script>
	$(document).ready(function(){
		var button = '', objeto='',pass=true;
		$('#prof_mat').DataTable({
			responsive: true
		});
		$('.tooltip-acciones').tooltip({
			selector: "[data-toggle=tooltip]",
			container: "body"
		});
		function verificar(){
			var prof='Profesores', per='Periodos', car='Carreras', mat='Materias', sec='Secciones', valido=true;
			valido = valido && validar(prof);
			//valido = valido && validar(per);
			valido = valido && validar(car);
			valido = valido && validar(mat);
			valido = valido && validar(sec);
			return valido;
		}
		/*Al cerrar el modal Toma la linea Verde y carga sus datos en el FORM*/
		$('#Modal_').on('hidden.bs.modal', function (){
			if ($('.datatables tbody tr.success').hasClass('success')){
				if($(this).find('.dataTables_empty').text()==''){
					//Si la fila que se selecciona posee datos
					if(objeto=='Profesores'){
						$('#Profesores_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#Profesores_n').val($('.datatables tbody tr.success .'+objeto+'_ced').text());
						$('#nombres_c').val($('.datatables tbody tr.success .'+objeto+'_name').text());
					}else if(objeto=='Carreras'){
						$('#'+objeto+'_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
						$('#'+objeto+'_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#Materias_c').val('');
						$('#Materias_n').val('');
						$('#Secciones_c').val('');
						$('#Secciones_n').val('');
					}else if(objeto=='Materias'){
						$('#Materias_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
						$('#Materias_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#Semestre_c').val($('.datatables tbody tr.success .'+objeto+'_sem').text());
						$('#Secciones_c').val('');
						$('#Secciones_n').val('');
					}else{
						$('#'+objeto+'_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
						$('#'+objeto+'_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
					}
				}
			}
		});
 		$('.save_modal').on("click",function(){
			$('#accion').val('delete');
			$('#prof_mat_form').submit();
		});
		/*Controlo las acciones de cada Boton en el Form*/
		$(document).on('click', '.btn', function(e){
			button = $(this).attr('id');
			clear_log();
			switch (button){
				case 'Profesor_btn':
					objeto='Profesores';
				break;
				case 'Periodos_btn':
					objeto='Periodos';
				break;
				case 'Carreras_btn':
					objeto='Carreras';
				break;
				case 'Materia_btn':
					objeto='Materias';
				break;
				case 'Seccion_btn':
					objeto='Secciones';
				break;
				case 'Delete_btn':
					$('#id').val($(this).data('id'));
					$('#modal_delete').modal('show');
				break;
				case 'bt_new':
					window.location.href = "?mod=MAESTRAS&submod=PROF_MAT";
				break;
				case 'bt_save':
					if(verificar()){
						$('#prof_mat_form').submit();
					}
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
			if(objeto=='Materias'){
				if($('#Carreras_c').val()<=0){
					mensaje("DEBE SELECCIONAR PRIMERO UNA CARRERA","ERROR");
					pass = pass && false;
				}else{
					pass = pass && true;
				}
			}else if(objeto=='Secciones'){
				if($('#Carreras_c').val()<=0){
					mensaje("DEBE SELECCIONAR PRIMERO UNA CARRERA","ERROR");
					pass = pass && false;
				}else{
					pass = pass && true;
				}
			}else{
				pass = true;
			}
			if(pass){
				$('#Modal_').modal('show');
				$('#Modal_Content').html('');
				$('#Modal_loader').show();
				$(".save_modal").hide();
				$(".close_modal").hide();
				var obj = $('#obj').val();
				$.ajax({
					url: './modulos/maestras/modal.php',
					type: 'POST',
					data: 'obj='+obj+'&accion='+objeto+'&det='+$('#Carreras_c').val()+'&sem='+$('#Semestre_c').val(),
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
						<form role="form" name="prof_mat_form" id="prof_mat_form" method="post" enctype="multipart/form-data">
							<div class="row">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#form_prof_mat" data-toggle="tab" aria-expanded="true">FORMULARIO</a></li>
									<li class=""><a href="#list_prof_mat" data-toggle="tab" aria-expanded="false">LISTADO</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade active in" id="form_prof_mat">
										<div class="col-xs-6 col-md-4">
											<div id="Profesores_g" class="form-group input-group">
												<label class="control-label" for="Profesores_c">PROFESOR</label>
												<input id="Profesores_c" name="Profesores_c" type="text" class="form-control hidden">
												<input id="Profesores_n" name="Profesores_n" type="text" class="form-control" readonly>
												<span class="input-group-btn"><button id="Profesor_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="nombres_g" class="form-group input-group">
												<label class="control-label" for="nombres_c">NOMBRE(S)</label>
												<input id="nombres_c" name="nombres_c" type="text" class="form-control" readonly>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
											<!-- <div id="Periodos_g" class="form-group input-group">
												<label class="control-label" for="Periodos_c">PERIODO</label>
												<input id="Periodos_c" name="Periodos_c" type="text" readonly class="form-control hidden">
												<input id="Periodos_n" name="Periodos_n" type="text" readonly class="form-control">
												<span class="input-group-btn"><button id="Periodos_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div> -->
											<div id="Carreras_g" class="form-group input-group">
												<label class="control-label" for="Carreras_c">CARRERA</label>
												<input id="Carreras_c" name="Carreras_c" type="text" readonly class="form-control hidden">
												<input id="Carreras_n" name="Carreras_n" type="text" readonly class="form-control">
												<span class="input-group-btn"><button id="Carreras_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
											<div id="Materias_g" class="form-group input-group">
												<label class="control-label" for="Materias_c">MATERIA</label>
												<input id="Materias_c" name="Materias_c" type="text" readonly class="form-control hidden">
												<input id="Semestre_c" name="Semestre_c" type="text" readonly class="form-control hidden">
												<input id="Materias_n" name="Materias_n" type="text" readonly class="form-control">
												<span class="input-group-btn"><button id="Materia_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="Secciones_g" class="form-group input-group">
												<label class="control-label" for="Secciones_c">SECCION</label>
												<input id="Secciones_c" name="Secciones_c" type="text" readonly class="form-control hidden">
												<input id="Secciones_n" name="Secciones_n" type="text" readonly class="form-control">
												<span class="input-group-btn"><button id="Seccion_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
										</div>
										<div class="col-xs-12">
											<div class="row">
												<div class="col-lg-12">
												<p style="text-align:center;">
													<input type="hidden" id="accion" name="accion" class="form-control hidden" value="{accion}">
													<input type="hidden" id="obj" name="obj" class="form-control hidden" value="{obj}">
													<input type="hidden" id="id" name="id" class="form-control hidden" value="{id}">
													<a id="bt_new" class="btn btn-default form-btn"><i class="fa fa-file-o"></i> NUEVO</a>
													<a id="bt_save" class="btn btn-default form-btn"><i class="fa fa-save"></i> GUARDAR</a>
													<a id="bt_exit" class="btn btn-default form-btn"><i class="fa fa-sign-out"></i> SALIR</a>
												</p>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="list_prof_mat">
										<div class="panel panel-default">
											<div class="panel-heading">LISTADO DE MATERIAS POR PROFESOR / SECCION</div>
											<div class="panel-body">
												<div class="table-responsive">
													<table width="100%" class="table table-striped table-bordered table-hover" id="prof_mat">
														<thead>
															<tr>
																<th>PROFESOR</th>
																<th>CARRERA</th>
																<th>MATERIA</th>
																<th>SECCION</th>
																<th>ACCIONES</th>
															</tr>
														</thead>
														<tbody>
														<!-- START BLOCK : data -->
															<tr class="{class}">
																<td>{PROFESOR}</td>
																<td>{CARRERA}</td>
																<td>{MATERIA}</td>
																<td>{SECCION}</td>
																<td>{actions}</td>
															</tr>
														<!-- END BLOCK : data -->
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
						<div class="row">
							<div class="col-lg-12"><p></p><div id="log" class="log alert {mensaje_class}">{mensaje}</div></div>
						</div>
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
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="ModalLabel">¿CONFIRME?</h4>
			</div>
			<div class="modal-body">
				<p>¿DESEA ELIMINAR EL CRUCE SELECCIONADO?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save_modal" data-dismiss="modal">ACEPTAR</button>
				<button type="button" class="btn btn-default close_modal" data-dismiss="modal">CANCELAR</button>
			</div>
		</div>
	</div>
</div>