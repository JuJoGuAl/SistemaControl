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
		check_status();
		totaliza();
		var button = '', objeto='',pass=true;
		//$('#Materias_btn').prop('disabled', true);
		/*Verifico, los campos para su procesar*/
		function imprime(){
			var error=true;
			if($('#status_c').val()!='PROCESADA'){
				error = error && false;
				mensaje("LA INSCRIPCION NO ESTA PROCESADA","ERROR");
			}
			return error;
		}
		function verificar(){
			var alum='Alumno', per='Periodos', car='Carreras', fec='f_ins', table1 = $('#materias_det'), total1='materias_uc', valido=true;
			valido = valido && validar(alum);
			valido = valido && validar(per);
			valido = valido && validar(car);
			valido = valido && validar(fec);
			valido = valido && count_row(table1,'UNA MATERIA');
			valido = valido && IsNumber(total1);
			$('#materias_det tbody tr select').each(function(){
				valido = valido && list2($(this).val());
				if(valido===false){
					$(this).closest('tr').addClass("danger");
				}else{
					$(this).closest('tr').removeClass("danger");	
				}
			});

			return valido;
		}
		function totaliza(){
			var total_uc = 0;
			total_uc=$('input[name^="mat_uc"]').sumValues();
			$('#materias_uc_c').val((total_uc));
		}
		function valida_uc(){
			var clave_input=$('#clave_uc_c').val(),uc=+$('#materias_uc_c').val();;
			mensaje("LA CANTIDAD DE UC INSCRITAS: <strong>"+uc+"</strong> SUPERA EL MAXIMO PERMITIDO, INGRESE UNA CLAVE PARA REGISTRAR EL EXCESO DE UC","ERROR");
			if(clave_input!=""){
				new Promise(function(resolve, reject){
					$.ajax({
						url: './modulos/academico/modal.php',
						type: 'POST',
						data: 'accion=clave&input='+clave_input,
						dataType:'json',
						success: function(data){
							resolve(data);
							if(!data){
								mensaje("CLAVE INVALIDA!","ERROR");
							}else{
								$.ajax({
									url: './modulos/academico/modal.php',
									type: 'POST',
									data: 'accion=UC_MAX&uc='+uc,
									dataType:'json',
									success: function(data){
										resolve(data);
										if(!data){
											mensaje("MAXIMO DE UC EXCEDIDO!","ERROR");
										}else{
											clear_log();
											$('#inscripcion_form').submit();
										}
									},
									error: function(error){
										reject(error);
										console.log(error);
									}
								})
							}
						},
						error: function(error){
							reject(error);
							console.log(error);
						}
					})
				});
			}
		}
		/*Verifico el STATUS*/
		function check_status(){
			if($('#status_c').val()=='PROCESADA'){
				$('.form-control').prop('disabled', true);
				$('[id^="del_mat"]').prop('disabled', true);
				$('#Alumnos_btn').prop('disabled', true);
				$('#pagos_btn').prop('disabled', true);
				$('#Materias_btn').prop('disabled', true);
				$('#Periodos_btn').prop('disabled', true);
				$('#Carreras_btn').prop('disabled', true);
				$('#bt_save').prop('disabled', true);
			}else{
				$('.form-control').prop('disabled', false);
				$('[id^="del_mat"]').prop('disabled', false);
				$('#Alumnos_btn').prop('disabled', false);
				$('#pagos_btn').prop('disabled', false);
				$('#Materias_btn').prop('disabled', false);
				$('#Periodos_btn').prop('disabled', false);
				$('#Carreras_btn').prop('disabled', false);
				$('#bt_save').prop('disabled', false);
			}
		}
		$('button[id^="del_mat"]').livequery(function(){
			$(this).click(function (){
				$(this).closest('[id^="mat_"]').remove();
				totaliza();
			});
		});
		
		$('#Modal_').on('hidden.bs.modal',function(){
		});
		/*Al cerrar el modal Toma la linea Verde y carga sus datos en el FORM*/
		$('#Modal_').on('hidden.bs.modal', function (){
			if ($('.datatables tbody tr.success').hasClass('success')){
				if($(this).find('.dataTables_empty').text()==''){
					//Si la fila que se selecciona posee datos
					if(objeto=='Alumnos'){
						$('#Alumno_c').val('');
						$('#Alumno_n').val('');
						$('#nombres_c').val('');
						var cliente=$('.datatables tbody tr.success .'+objeto+'_name').text(), saldo = +$('.datatables tbody tr.success .'+objeto+'_saldo').text();
						if(saldo>0){
							mensaje("EL ALUMNO POSEE UNA DEUDA DE <strong>"+saldo+"</strong> DEBE DE CANCELARLA ANTES DE CONTINUAR CON LA INSCRIPCION","ERROR");
						}else{
							clear_log();
							$('#Alumno_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
							$('#Alumno_n').val($('.datatables tbody tr.success .'+objeto+'_ced').text());
							$('#nombres_c').val(cliente);
							$('#materias_det tbody').html('');
							$('#materias_uc_c').val('');
						}
					}else if(objeto=='Carreras'){
						$('#'+objeto+'_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
						$('#'+objeto+'_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#materias_det tbody').html('');
						$('#materias_uc_c').val('');
					}else if(objeto=='Materias'){
						var cmat = $('.datatables tbody tr.success .'+objeto+'_id').text(),ccar = $('#Carreras_c').val(),cper = $('#Periodos_c').val();
						$.ajax({
							url: './modulos/academico/modal.php',
							type: 'POST',
							data: 'accion='+objeto+'&cmat='+cmat+'&ccar='+ccar+'&cper='+cper,
							dataType:'json'
						})
						.done(function(data){
							var uc_used=+$('#materias_uc_c').val(),new_uc=+data.MAT_UC,max_uc=+data.MAX_UC;
 							tr ='<tr id="mat_">';
							tr=tr+'<td><button id="del_mat" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button>';
							tr=tr+'<input name="cmate[]" id="cmate[]" type="hidden" class="form-control hidden" value="'+data.MATE_CODE+'"></td>';
							tr=tr+'<td><input name="mat_cod[]" id="mat_cod[]" class="form-control" readonly value="'+data.MAT_COD+'"></td>';
							tr=tr+'<td><input name="mat_nam[]" id="mat_nam[]" class="form-control" readonly value="'+data.MAT_NAME+'">';
							tr=tr+'<td><input name="mat_sem[]" id="mat_sem[]" class="form-control" readonly value="'+data.MAT_SEM+'"></td>';
							tr=tr+'<td><input name="mat_uc[]" id="mat_uc[]" class="form-control" readonly value="'+data.MAT_UC+'"></td>';
							tr=tr+'<td><select name="mat_sec[]" id="mat_sec[]" class="form-control">';
							$.each(data.sec, function(key,value){
								tr=tr+'<option value="'+value.CSECCION+'">'+value.SECCION+'</option>';
							});
							tr=tr+'</select></td>';
							tr=tr+'</tr>';
							$("#materias_det tbody").append(tr);
							totaliza();
							check_status();
						})
						.fail(function(x,err,msj){
							console.log(msj);
						});
					}else if(objeto=='Inscripciones'){
						var cins = $('.datatables tbody tr.success .'+objeto+'_id').text();
						$.ajax({
							url: './modulos/academico/modal.php',
							type: 'POST',
							data: 'accion='+objeto+'&cins='+cins,
							dataType:'json'
						})
						.done(function(trans){
							$('#Inscripcion_c').val(cins);
							$('#id').val(cins);
							$('#Alumno_c').val(trans.cab.CALUMNO);
							$('#Alumno_n').val(trans.cab.CEDULA);
							$('#nombres_c').val(trans.cab.ALUMNO);
							$('#status_c').val('PROCESADA');
							$('#Periodos_c').val(trans.cab.CPERIODO);
							$('#Periodos_n').val(trans.cab.PERIODO);
							$('#Carreras_c').val(trans.cab.CCARRERA);
							$('#Carreras_n').val(trans.cab.CARRERA);
							$('#convenio_c').html("");
							var convenio="";
							if (trans.cab.CCONVENIO==0){
								convenio="SIN CONVENIO";
							}else{
								convenio=trans.cab.CONVENIO;
							}
							$('#convenio_c').append("<option value='0'>"+convenio+"</option>");
							$('#f_ins_c').val(trans.cab.FECHA_INS);
							$('#accion').val("block");
							clear_log();
							var tr;
							$('#materias_det tbody').html('');
							$.each(trans.det, function(key,data){
								tr ='<tr id="mat_">';
								tr=tr+'<td><button id="del_mat" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button>';
								tr=tr+'<input name="cmate[]" id="cmate[]" type="hidden" class="form-control hidden" value="'+data.CODIGO_DET+'"></td>';
								tr=tr+'<td><input name="mat_cod[]" id="mat_cod[]" class="form-control" readonly value="'+data.MATE_CODIGO+'"></td>';
								tr=tr+'<td><input name="mat_nam[]" id="mat_nam[]" class="form-control" readonly value="'+data.MATE_NOMBRE+'">';
								tr=tr+'<td><input name="mat_sem[]" id="mat_sem[]" class="form-control" readonly value="'+data.MATE_SEM+'"></td>';
								tr=tr+'<td><input name="mat_uc[]" id="mat_uc[]" class="form-control" readonly value="'+data.MATE_UC+'"></td>';
								tr=tr+'<td><input name="mat_sec[]" id="mat_sec[]" class="form-control" readonly value="'+data.MATE_SEC+'"></td>';
								tr=tr+'</tr>';
								$("#materias_det tbody").append(tr);
							});
							totaliza();
							check_status();
						})
						.fail(function(x,err,msj){
							console.log(msj);
						});
					}else{
						$('#'+objeto+'_n').val($('.datatables tbody tr.success .'+objeto+'_name').text());
						$('#'+objeto+'_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#materias_det tbody').html('');
						$('#materias_uc_c').val('');
					}
				}
			}
		});
		/*Controlo las acciones de cada Boton en el Form*/
		$(document).on('click', '.btn', function(e){
			button = $(this).attr('id');
			clear_log();
			switch (button){
				case 'Alumnos_btn':
					objeto='Alumnos';
				break;
				case 'Periodos_btn':
					objeto='Periodos';
				break;
				case 'Carreras_btn':
					objeto='Carreras';
				break;
				case 'Materias_btn':
					objeto='Materias';
				break;
				case 'Inscripcion_btn':
					objeto='Inscripciones';
				break;
				case 'bt_new':
					window.location.href = "?mod=ACADEMICO&submod=INSCRIPCION";
				break;
				case 'bt_print':
					if(imprime()){ imprimir('./modulos/reportes/inscripcion_recibo.php?code='+$('#id').val()); }
				break;
				case 'bt_save':
					if(verificar()){
						var calm=$('#Alumno_c').val(),ccar=$('#Carreras_c').val(),cper=$('#Periodos_c').val(),uc_ins=+$('#materias_uc_c').val();
						new Promise(function(resolve, reject){
							$.ajax({
								url: './modulos/academico/modal.php',
								type: 'POST',
								data: 'accion=Check_ins&calm='+calm+'&ccar='+ccar+'&cper='+cper,
								dataType:'json',
								success: function(data){ // si recibo algún dato como parametro
									resolve(data); //Resuelvo la promesa (la cumplo) y con esto se puede recibir como parámetro
									if(!data){
										mensaje("NO SE PUEDE PROCESAR LA INSCRIPCION, YA EXISTE UNA INSCRIPCION, CON EL MISMO ALUMNO, CARRERA Y PERIODO","ERROR");
										//DEBRIA SALIRSE DEL PROMISE
									}else{
										$.ajax({
											url: './modulos/academico/modal.php',
											type: 'POST',
											data: 'accion=UC&uc='+uc_ins,
											dataType:'json',
											success: function(data){ // si recibo algún dato como parametro
												resolve(data); //Resuelvo la promesa (la cumplo) y con esto se puede recibir como parámetro
												if(!data){
													$('#pass_uc').removeClass("hidden");
													valida_uc();
												}else{
													clear_log();
													$('#pass_uc').addClass("hidden");
													$('#inscripcion_form').submit();
												}
											},
											error: function(error){
												reject(error); //marco la promesa como incumplida y paso como parámetro el porque no se cumplió
												console.log(error);
											}
										})
									}
								},
								error: function(error){
									reject(error); //marco la promesa como incumplida y paso como parámetro el porque no se cumplió
									console.log(error);
								}
							})
						});
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
				if($('#Alumno_c').val()<=0){
					mensaje("DEBE SELECCIONAR PRIMERO UN ALUMNO","ERROR");
					pass=false;
				}
				else if($('#Periodos_c').val()<=0){
					mensaje("DEBE SELECCIONAR PRIMERO UN PERIODO","ERROR");
					pass=false;
				}
				else if($('#Carreras_c').val()<=0){
					mensaje("DEBE SELECCIONAR PRIMERO UNA CARRERA","ERROR");
					pass=false;
				}
				else{
					pass=true;
				}
			}else{
				pass=true;
			}
			if(pass){
				$('#Modal_').modal('show');
				$('#Modal_Content').html('');
				$('#Modal_loader').show();
				$(".save_modal").hide();
				$(".close_modal").hide();
				if(objeto=="Materias"){ var code = $('#Carreras_c').val(),code_det = $('#Alumno_c').val();}
				if(objeto=="Materias"){
					var non_mat = new Array();
					$('#materias_det tbody tr td input[id^="cmate"]').each(function(row, tr){
						non_mat.push($(this).val());
					});
				}
				$.ajax({
					url: './modulos/academico/modal.php',
					type: 'POST',
					data: 'accion='+objeto+'&code='+code+'&code_det='+code_det+'&no_mat='+non_mat,
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
						<form role="form" name="inscripcion_form" id="inscripcion_form" method="post" enctype="multipart/form-data">
							<div class="row">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#principales" data-toggle="tab" aria-expanded="true">PRINCIPALES</a></li>
									<li class=""><a href="#academicos" data-toggle="tab" aria-expanded="false">ACADEMICOS</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade active in" id="principales">
										<div class="col-xs-6 col-md-4">
											<div id="Inscripcion_g" class="form-group input-group">
												<label class="control-label" for="Inscripcion_c">INSCRIPCION</label>
												<input id="Inscripcion_c" name="Inscripcion_c" type="text" class="form-control numeric" value="{CODIGO}" readonly>
												<span class="input-group-btn"><button id="Inscripcion_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="Alumno_g" class="form-group input-group">
												<label class="control-label" for="Alumno_c">ALUMNO</label>
												<input id="Alumno_c" name="Alumno_c" type="text" class="form-control hidden" value="{CODE_ALUMNO}">
												<input id="Alumno_n" name="Alumno_n" type="text" class="form-control" value="{CEDULA}" readonly>
												<span class="input-group-btn"><button id="Alumnos_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="nombres_g" class="form-group input-group">
												<label class="control-label" for="nombres_c">NOMBRE(S)</label>
												<input id="nombres_c" name="nombres_c" type="text" class="form-control" value="{ALUMNO}" readonly>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
											<div id="Periodos_g" class="form-group input-group">
												<label class="control-label" for="Periodos_c">PERIODO</label>
												<input id="Periodos_c" name="Periodos_c" type="text" readonly class="form-control hidden" value="{CPERIODO}">
												<input id="Periodos_n" name="Periodos_n" type="text" readonly class="form-control" value="{PERIODO}">
												<span class="input-group-btn"><button id="Periodos_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="Carreras_g" class="form-group input-group">
												<label class="control-label" for="Carreras_c">CARRERA</label>
												<input id="Carreras_c" name="Carreras_c" type="text" readonly class="form-control hidden" value="{CCARRERA}">
												<input id="Carreras_n" name="Carreras_n" type="text" readonly class="form-control" value="{CARRERA}">
												<span class="input-group-btn"><button id="Carreras_btn" class="btn btn-default search_data" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="convenio_g" class="form-group input-group">
					                          <label class="control-label" for="convenio_c">CONVENIO (DE APLICAR)</label>
					                          <select name="convenio_c" id="convenio_c" class="form-control">
					                            <option value="0">SIN CONVENIO</option>
					                            <!-- START BLOCK : convenios_det -->
					                            <option value="{CCONVENIO}" {selected}>{CONVENIO}</option>
					                            <!-- END BLOCK : convenios_det -->
					                          </select>
					                        </div>
										</div>
										<div class="col-xs-6 col-md-4">
											<div class="form-group input-group">
												<label class="control-label" for="status_c">ESTATUS</label>
												<input id="status_c" name="status_c" class="form-control" readonly type="text" value="{ESTATUS}">
											</div>
											<div id="f_ins_g" class="form-group input-group">
												<label class="control-label" for="f_ins_c">FECHA INSCRIPCION</label>
												<input id="f_ins_c" name="f_ins_c" type="text" class="form-control" value="{FECHA_INS}" readonly>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="academicos">
										<div class="panel panel-default">
											<div class="panel-heading">MATERIAS</div>
											<div class="panel-body">
												<div class="table-responsive">
													<table id="materias_det" class="table table-hover materias_det">
														<thead>
															<tr>
																<th width="40px">#</th>
																<th width="120px">CODIGO</th>
																<th>MATERIA</th>
																<th width="70px">SEM</th>
																<th width="70px">UC</th>
																<th>SECCION</th>
															</tr>
														</thead>
														<tbody>
															<!-- START BLOCK : mat_det -->
															<tr id="mat_">
																<td>
																	<button id="del_mat" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button>
																	<input type="hidden" name="cmate[]" id="cmate[]" class="form-control hidden" readonly value="{CMATE}">
																</td>
																<td><input name="mat_cod[]" id="mat_cod[]" class="form-control" readonly value="{MATE_CODIGO}"></td>
																<td><input name="mat_nam[]" id="mat_nam[]" class="form-control" readonly value="{MATE_NOMBRE}"></td>
																<td><input name="mat_sem[]" id="mat_sem[]" class="form-control" readonly value="{MATE_SEM}"></td>
																<td><input name="mat_uc[]" id="mat_uc[]" class="form-control" readonly value="{MATE_UC}"></td>
																<td><input name="mat_sec[]" id="mat_sec[]" class="form-control" readonly value="{MATE_SEC}"></td>
															</tr>
															<!-- END BLOCK : mat_det -->
														</tbody>
													</table>
													<p style="text-align:right;">
														<p></p>
														<a id="Materias_btn" class="btn btn-default form-btn search_data"><i class="fa fa-plus"></i> AGREGAR</a>
													</p>
												</div>
												<div class="row show-grid">
													<div class="col-xs-6 col-sm-6">
														<label class="control-label" for="materias_uc_c">U.C. INSCRITAS</label>
														<input id="materias_uc_c" name="materias_uc_c" class="form-control" readonly value="{UCS}">
													</div>
													<div id="pass_uc" class="col-xs-6 col-sm-6 hidden">
														<label class="control-label" for="clave_uc_c">CLAVE PARA UC EXTRAS</label>
														<input id="clave_uc_c" name="clave_uc_c" class="form-control">
													</div>
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
									<a id="bt_save" class="btn btn-default form-btn"><i class="fa fa-save"></i> GUARDAR</a>
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
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save_modal">ACEPTAR</button>
				<button type="button" class="btn btn-default close_modal">CANCELAR</button>
			</div>
		</div>
	</div>
</div>