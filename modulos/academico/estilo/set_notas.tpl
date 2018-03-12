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
		var button = '', objeto='',obj='',pass=true;
		function verificar(){
			var table1 = $('#materias_det'), valido=true;
			valido = valido && count_row(table1,'UN REGISTRO');
			$('#materias_det tbody tr input[id^="not"]').each(function(){
				if($(this).val()<=0){
					valido = valido && false;
				}else{
					valido = valido && true;
				}
				if(valido===false){
					mensaje("DEBE COLOCAR UNA NOTA DIFERENTE DE 0","ERROR");
				}else{
					clear_log();
				}
			});
			$('#materias_det tbody tr select').each(function(){
				valido = valido && list3($(this).val());
				if(valido===false){
					$(this).closest('tr').addClass("danger");
				}else{
					$(this).closest('tr').removeClass("danger");	
				}
			});

			return valido;
		}
		function filter(op=false){
			if(op){
				$('#materias_det tbody').html('');
				$('#tab_det').fadeIn('slown');
				$('.nav-tabs a[href="#alumnos"]').tab('show');
			}else{
				$('#materias_det tbody').html('');
				$('#tab_det').fadeOut('slown');
				$('.nav-tabs a[href="#alumnos"]').hide();
			}
		}
		/*Al cerrar el modal Toma la linea Verde y carga sus datos en el FORM*/
		$('#Modal_').on('hidden.bs.modal', function (){
			if ($('.datatables tbody tr.success').hasClass('success')){
				if($(this).find('.dataTables_empty').text()==''){
					//Si la fila que se selecciona posee datos
					filter(false);
					if(objeto=='Alumnos'){
						$('#Alumno_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#Alumno_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
						$('#Carreras_c').val('');
						$('#Carreras_n').val('');
						$('#Materias_c').val('');
						$('#Materias_n').val('');
						$('#Secciones_c').val('');
						$('#Secciones_n').val('');
					}else if(objeto=='Carreras'){
						$('#'+objeto+'_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
						$('#'+objeto+'_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#Materias_c').val('');
						$('#Materias_n').val('');
						$('#Secciones_c').val('');
						$('#Secciones_n').val('');
					}else if(objeto=='Materias'){
						$('#'+objeto+'_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
						$('#'+objeto+'_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
					}else{
						$('#'+objeto+'_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
						$('#'+objeto+'_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
					}
				}
			}
		});
		/*Controlo las acciones de cada Boton en el Form*/
		$(document).on('click', '.btn', function(e){
			button = $(this).attr('id');
			clear_log();
			switch (button){
				case 'Profesor_btn':
					objeto='Profesores';
				break;
				case 'Alumnos_btn':
					objeto='Alumnos';
					obj='notas';
				break;
				case 'Periodos_btn':
					objeto='Periodos';
					obj='notas';
				break;
				case 'Carreras_btn':
					objeto='Carreras';
					obj='notas';
				break;
				case 'Materias_btn':
					objeto='Materias';
					obj='notas';
				break;
				case 'Secciones_btn':
					objeto='Secciones';
					obj='notas';
				break;
				case 'bt_new':
					window.location.href = "?mod=ACADEMICO&submod=SET_NOTAS";
				break;
				case 'bt_save':
					if(verificar()){
						$('#accion').val('save_new');
						$('#notas_form').submit();
					}
				break;
				case 'bt_search':
					$('#accion').val('Filtros');
					$.ajax({
						url: './modulos/academico/modal.php',
						type: 'POST',
						data: $('#notas_form').serialize(),
						dataType:'json'
					})
					.done(function(data){
						filter(true);
						$.each(data, function(key,value){
							tr ='<tr>';
							tr=tr+'<td><i class="fa fa-play"></i>';
							tr=tr+'<input name="ins_det[]" id="ins_det[]" type="hidden" class="form-control hidden" value="'+value.CINSCRIPCION_DET+'"></td>';
							tr=tr+'<td><input name="alm[]" id="alm[]" class="form-control" readonly value="'+value.ALUMNO+'"></td>';
							tr=tr+'<td><input name="car[]" id="car[]" class="form-control" readonly value="'+value.CARRERA+'"></td>';
							tr=tr+'<td><input name="mat[]" id="mat[]" class="form-control" readonly value="'+value.MATERIA+'">';
							tr=tr+'<td><input name="sec[]" id="sec[]" class="form-control" readonly value="'+value.SECCION+'"></td>';
							tr=tr+'<td><input name="not[]" id="not[]" class="form-control numeric" value="'+value.NOTA+'"></td>';
							tr=tr+'<td><select name="prof[]" id="prof[]" class="form-control">';
							$.each(value.prof, function(key,value){
								tr=tr+'<option value="'+value.CPROFESOR+'">'+value.PROFESOR+'</option>';
							});
							tr=tr+'</select></td>';
							tr=tr+'</tr>';
							$("#materias_det tbody").append(tr);
						});
						//console.log(data);
					})
					.fail(function(){
						filter(false);
						mensaje("ERROR AL CARGAR LOS DETALLES DE LA BUSQUEDA","ERROR");
					});
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
				var det='';
				if(objeto=='Materias' || objeto=='Secciones'){
					det=$('#Carreras_c').val();
				}else if(objeto=='Carreras'){
					det=$('#Alumno_c').val();
				}
				$.ajax({
					url: './modulos/academico/modal.php',
					type: 'POST',
					data: 'accion='+objeto+'&adic='+obj+'&det='+det,
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
						<form role="form" name="notas_form" id="notas_form" method="post" enctype="multipart/form-data">
							<div class="row">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#principales" data-toggle="tab" aria-expanded="true">PRINCIPALES</a></li>
									<li class=""><a href="#alumnos" id="tab_det" style="display:none;" data-toggle="tab" aria-expanded="false">CALIFICACIONES</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade active in" id="principales">
										<div class="col-xs-6 col-md-4">
											<div id="Periodos_g" class="form-group input-group">
												<label class="control-label" for="Periodos_c">PERIODO</label>
												<input id="Periodos_c" name="Periodos_c" type="text" readonly class="form-control hidden" value="{CPERIODO}">
												<input id="Periodos_n" name="Periodos_n" type="text" readonly class="form-control" value="{PERIODO}">
												<span class="input-group-btn"><button id="Periodos_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="Alumno_g" class="form-group input-group">
												<label class="control-label" for="Alumno_c">ALUMNO</label>
												<input id="Alumno_c" name="Alumno_c" type="text" class="form-control hidden" value="{CODE_ALUMNO}">
												<input id="Alumno_n" name="Alumno_n" type="text" class="form-control" value="{CEDULA}" readonly>
												<span class="input-group-btn"><button id="Alumnos_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
											<div id="Profesores_g" class="form-group input-group">
												<label class="control-label" for="Profesores_c">PROFESOR</label>
												<input id="Profesores_c" name="Profesores_c" type="text" class="form-control hidden">
												<input id="Profesores_n" name="Profesores_n" type="text" class="form-control" readonly>
												<span class="input-group-btn"><button id="Profesor_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="Carreras_g" class="form-group input-group">
												<label class="control-label" for="Carreras_c">CARRERA</label>
												<input id="Carreras_c" name="Carreras_c" type="text" readonly class="form-control hidden" value="{CCARRERA}">
												<input id="Carreras_n" name="Carreras_n" type="text" readonly class="form-control" value="{CARRERA}">
												<span class="input-group-btn"><button id="Carreras_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
											<div id="Materias_g" class="form-group input-group">
												<label class="control-label" for="Materias_c">MATERIA</label>
												<input id="Materias_c" name="Materias_c" type="text" readonly class="form-control hidden" value="{CCARRERA}">
												<input id="Materias_n" name="Materias_n" type="text" readonly class="form-control" value="{CARRERA}">
												<span class="input-group-btn"><button id="Materias_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="Secciones_g" class="form-group input-group">
												<label class="control-label" for="Secciones_c">SECCION</label>
												<input id="Secciones_c" name="Secciones_c" type="text" readonly class="form-control hidden" value="{CPERIODO}">
												<input id="Secciones_n" name="Secciones_n" type="text" readonly class="form-control" value="{PERIODO}">
												<span class="input-group-btn"><button id="Secciones_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="alumnos">
										<div class="panel panel-default">
											<div class="panel-heading">CALIFICACIONES</div>
											<div class="panel-body">
												<div class="table-responsive">
													<table id="materias_det" class="table table-hover">
														<thead>
															<tr>
																<th width="40px">#</th>
																<th>ALUMNO</th>
																<th>CARRERA</th>
																<th>MATERIA</th>
																<th width="90px">SECCION</th>
																<th width="60px">NOTA</th>
																<th>PROFESOR</th>
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12"><p></p><div id="log" class="log alert {mensaje_class}">{mensaje}</div></div>
							</div>
							<div class="row">
								<div class="col-lg-12">
								<p style="text-align:center;">
									<input type="hidden" id="accion" name="accion" class="form-control hidden" value="{accion}">
									<input type="hidden" id="id" name="id" class="form-control hidden" value="{id}">
									<a id="bt_new" class="btn btn-default form-btn"><i class="fa fa-file-o"></i> NUEVO</a>
									<a id="bt_search" class="btn btn-default form-btn"><i class="fa fa-search"></i> BUSCAR</a>
									<a id="bt_save" class="btn btn-default form-btn"><i class="fa fa-save"></i> GUARDAR</a>
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
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save_modal">ACEPTAR</button>
				<button type="button" class="btn btn-default close_modal">CANCELAR</button>
			</div>
		</div>
	</div>
</div>