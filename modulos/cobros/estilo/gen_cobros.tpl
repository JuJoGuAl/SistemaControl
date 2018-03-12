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
		var button = '', objeto='',obj='';
		/*Verifico, los campos para su procesar*/
		function get_ced(obj){
			element=$('#'+obj+'_c');
			var val='', label = $("label[for='"+obj+"_c']").text();
			var response=false;
			if (element.is(':input')){
				val = element.val();
			 }else{
			 	val = element.text();
			}
			if(val!=''){
				$.ajax({
					type: "POST",
					url: "./modulos/cobros/modal.php",
					data: "accion=check_ced&ced="+val,
					async: false,
					dataType:'json'
				})
				.done(function(data){
					if(data){
						$('#nombres_c').val(data.APELLIDOS+' '+data.NOMBRES);
						$('#cedula_n').val(data.CALUMNO);
						$('.has-error').removeClass("has-error");
						$('#log').removeClass("alert-danger");
						$('#log').fadeOut('slown');
						response=true;
					}else{
						$('#nombres_c').val('');
						$('#'+obj+'_g').addClass("has-error");
						$('#log').addClass("alert-danger");
						$('#log').html('La Cedula '+val+' no se Encuentra Registrada en el Sistema!');
						$('#log').fadeIn('slown');
						response=false;
					}
				})
				.fail(function(x,err,msj){
					console.log(msj);
				});
			}
			return response;
		}
		function verificar(){
			var cced='cedula', cperiodo='Periodos',ffac='f_fact', table = $('#conceptos_det'), total_rece='conceptos_total', valido=true;
			valido = valido && validar(cced);
			valido = valido && IsNumber(cced);			
			valido = valido && validar(cperiodo);
			valido = valido && IsNumber(cperiodo);
			valido = valido && validar(ffac);
			valido = valido && count_row(table,'un Concepto');
			valido = valido && IsNumber(total_rece);
			return valido;
		}
		/*Hago los calculos para los Art Cargados*/
		function totaliza(){
			var sub_total = 0;
			$('[id^="concp"]').each(function(){
				var cant=+$(this).find('[id^="cant"]').val(), costo=+$(this).find('[id^="precio"]').val();
				var costot = ((cant*costo)).toFixed(2);
				$(this).find('[id^="total"]').val(costot);
			});
			sub_total=$('input[name^="total"]').sumValues();
			$('#conceptos_sub_total_c').val(parseFloat(sub_total));
			$('#conceptos_total_c').val(parseFloat(sub_total));
			$('#conceptos_count_c').val(parseFloat($('#conceptos_det tbody tr').length));
		}
		/*LiveQuery dispara un evento SIEMPRE que se cumpla una condicion*/
		/*Todos los inputs con la clase numeric, chequeo su keydown event*/
		$('.numeric').livequery(function(){
			$(this).on("input", function (){
				totaliza();
			});
		});
		$('button[id^="del_conc"]').livequery(function(){
			$(this).click(function (){
				$(this).closest('[id^="concp"]').remove();
				totaliza();
			});
		});
		/*Verifico el STATUS*/
		function check_status(){
			if($('#status_c').val()=='PROCESADA' || $('#status_c').val()=='ANULADA'){
				$('.form-control').prop('disabled', true);
				$('#Periodos_btn').prop('disabled', true);
				$('[id^="del_conc"]').prop('disabled', true);
				$('#Conceptos_btn').prop('disabled', true);
				$('#bt_save').prop('disabled', true);
			}else{
				$('.form-control').prop('disabled', false);
				$('#Periodos_btn').prop('disabled', false);
				$('[id^="del_conc"]').prop('disabled', false);
				$('#Conceptos_btn').prop('disabled', false);
				$('#bt_save').prop('disabled', false);
			}
		}
		/*Al cerrar el modal Toma la linea Verde y carga sus datos en el FORM*/
		$('#Modal_').on('hidden.bs.modal', function (){
			if ($('.datatables tbody tr.success').hasClass('success')){
				if($(this).find('.dataTables_empty').text()==''){
					//Si la fila que se selecciona posee datos
					if(objeto=='Conceptos'){
						var tr;
						tr ='<tr id="concp">';
						tr=tr+'<td><button id="del_conc" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button>';
						tr=tr+'<input name="cconcepto[]" id="cconcepto[]" type="hidden" class="form-control hidden" value="'+$('.datatables tbody tr.success .'+objeto+'_id').text()+'">';
						tr=tr+'<input name="cfactura_det[]" id="cfactura_det[]" type="hidden" class="form-control hidden" value="0"></td>';
						tr=tr+'<td><input name="concepto[]" id="concepto[]" class="form-control" value="'+$('.datatables tbody tr.success .'+objeto+'_name').text()+'" readonly></td>';
						tr=tr+'<td><input name="cant[]" id="cant[]" class="form-control numeric" value="0">';
						tr=tr+'<td><input name="precio[]" id="precio[]" class="form-control numeric" value="'+$('.datatables tbody tr.success .'+objeto+'_costo').text()+'" readonly></td>';
						tr=tr+'<td><input name="total[]" id="total[]" class="form-control" value="0" readonly></td>';
						tr=tr+'</tr>';
						$("#conceptos_det tbody").append(tr);
						totaliza();
						check_status();
					}else if(objeto=='Facturas'){
						var cfactura = $('.datatables tbody tr.success .'+objeto+'_id').text();
						$.ajax({
							url: './modulos/cobros/modal.php',
							type: 'POST',
							data: 'accion='+objeto+'&cfactura='+cfactura,
							dataType:'json'
						})
						.done(function(trans){
							$('#Factura_c').val(cfactura);
							$('#cfactura').val(cfactura);
							$('#cedula_n').val(trans.CALUMNO);
							$('#cedula_c').val(trans.CEDULA);
							$('#nombres_c').val(trans.ALUMNO);
							$('#status_c').val(trans.ESTATUS);
							$('#Periodos_c').val(trans.CPERIODO);
							$('#Periodos_n').val(trans.PERIODO);
							$('#f_fact_c').val(trans.FECHA_FACTURA);
							$('#accion').val("block");
							clear_log();
							var tr;
							$('#conceptos_det tbody').html('');
							$.each(trans.mov_det, function(key,value){
								tr ='<tr id="concp">';
								tr=tr+'<td><button id="del_conc" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button>';
								tr=tr+'<input name="cconcepto[]" id="cconcepto[]" type="hidden" class="form-control hidden" value="'+value.CCONCEPTO+'">';
								tr=tr+'<input name="cfactura_det[]" id="cfactura_det[]" type="hidden" class="form-control hidden" value="'+value.CFACTURA_DET+'"></td>';
								tr=tr+'<td><input name="concepto[]" id="concepto[]" class="form-control" readonly value="'+value.CONCEPTO+'"></td>';
								tr=tr+'<td><input name="cant[]" id="cant[]" class="form-control" readonly value="'+value.CANTIDAD+'">';
								tr=tr+'<td><input name="precio[]" id="precio[]" class="form-control" readonly value="'+value.PRECIO+'"></td>';
								tr=tr+'<td><input name="total[]" id="total[]" class="form-control" readonly value="'+value.TOTAL+'"></td>';
								tr=tr+'</tr>';
								$("#conceptos_det tbody").append(tr);
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
					}
				}
			}
		});
		/*Controlo las acciones de cada Boton en el Form*/
		$(document).on('click', '.btn', function(e){
			button = $(this).attr('id');
			clear_log();
			switch (button){
				case 'Periodos_btn':
					objeto='Periodos';
				break;
				case 'Conceptos_btn':
					objeto='Conceptos';
				break;
				case 'factura_btn':
					objeto='Facturas';
					obj='FAC';
				break;
				case 'bt_new':
					window.location.href = "?mod=COBROS&submod=GEN_COBROS";
				break;
				case 'bt_save':
					if(verificar()){
						$('#conceptos_form').submit();
					}
				break;
				case 'bt_exit':
					window.location.href = "./";
				break;
				default:
					objeto='undefined';
			}
		});
		$('#cedula_c').on("keypress", function(e){
			if (e.keyCode == 13){
				if($('#accion').val()=="save_new"){
	 				var ced='cedula';
		 			if (validar(ced)){
		 				if(IsCed(ced)){
		 					get_ced(ced);
		 				}else{ $('#nombres_c').val(''); }
		 			}else{ $('#nombres_c').val(''); }
	 			}
				//return false;
			}
		});
 		$('#cedula_c').blur(function(){
 			if($('#accion').val()=="save_new"){
 				var ced='cedula';
	 			if (validar(ced)){
	 				if(IsCed(ced)){
	 					get_ced(ced);
	 				}else{ $('#nombres_c').val(''); }
	 			}else{ $('#nombres_c').val(''); }
 			}
 		});
		/*Abre el Modal segun el boton seleccionado, me llena la tabla y la muestra, si no consigue objetos llena un Modal con Info Generica*/
		$(document).on('click', '.search_data', function(e){
			$('#Modal_Content').html('');
			$('#Modal_loader').show();
			if(objeto=="Conceptos"){
				var non_con = new Array();
				$('#conceptos_det tbody tr td input[id^="cconcepto"]').each(function(row, tr){
					non_con.push($(this).val());
				});
			}
			$.ajax({
				url: './modulos/cobros/modal.php',
				type: 'POST',
				data: 'accion='+objeto+'&non_conc='+non_con+'&obj='+obj,
				dataType:'json'
			})
			.done(function(data){
				//console.log(data);
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
						<form role="form" name="conceptos_form" id="conceptos_form" method="post" enctype="multipart/form-data">
							<div class="row">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#fact" data-toggle="tab" aria-expanded="true">FACTURA</a></li>
									<li class=""><a href="#det" data-toggle="tab" aria-expanded="false">DETALLE</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade active in" id="fact">
										<div class="col-xs-6 col-md-4">
											<div id="Factura_g" class="form-group input-group">
												<label class="control-label" for="Factura_c">FACTURA</label>
												<input id="Factura_c" name="Factura_c" type="text" class="form-control numeric" value="{CODIGO}" readonly>
												<span class="input-group-btn"><button id="factura_btn" class="btn btn-default search_data" data-toggle="modal" data-target="#Modal_" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="cedula_g" class="form-group input-group">
												<label class="control-label" for="cedula_c">CEDULA</label>
												<input id="cedula_n" name="cedula_n" type="text" class="form-control hidden" value="{CALUMNO}">
												<input id="cedula_c" name="cedula_c" type="text" class="form-control" value="{CEDULA}" autofocus>
												<p class="help-block">El formato de la Cédula debe ser VXXXXXXX</p>
											</div>
											<div id="nombres_g" class="form-group input-group">
												<label class="control-label" for="nombres_c">NOMBRE(S)</label>
												<input id="nombres_c" name="nombres_c" type="text" class="form-control" value="{ALUMNO}" readonly>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
											<div class="form-group input-group">
												<label class="control-label" for="status_c">ESTATUS</label>
												<input id="status_c" name="status_c" class="form-control" readonly type="text" value="{ESTATUS}">
											</div>
											<div id="Periodos_g" class="form-group input-group">
												<label class="control-label" for="Periodos_c">PERIODO</label>
												<input id="Periodos_c" name="Periodos_c" type="text" readonly class="form-control hidden" value="{CPERIODO}">
												<input id="Periodos_n" name="Periodos_n" type="text" readonly class="form-control" value="{PERIODO}">
												<span class="input-group-btn"><button id="Periodos_btn" class="btn btn-default search_data" data-toggle="modal" data-target="#Modal_" type="button"><i class="fa fa-search"></i></button></span>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
											<div id="f_fact_g" class="form-group input-group">
												<label class="control-label" for="f_fact_c">FECHA FACTURA</label>
												<input id="f_fact_c" name="f_fact_c" type="text" class="form-control" value="{FECHA_FACTURA}" readonly>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="det">
										<div class="panel panel-default">
											<div class="panel-heading">CONCEPTOS</div>
											<div class="panel-body">
												<div class="table-responsive">
													<table id="conceptos_det" class="table table-hover conceptos_det">
														<thead>
															<tr>
																<th width="50px">#</th>
																<th>DESCRIPCION</th>
																<th width="170px">CANT</th>
																<th width="170px">PRECIO</th>
																<th width="170px">TOTAL</th>
															</tr>
														</thead>
														<tbody>
														<!-- START BLOCK : cobro_det -->
														<tr id="concp">
															<td>
																<button id="del_conc" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button>
																<input name="cconcepto[]" id="cconcepto[]" type="hidden" class="form-control hidden" value="{CCONCEPTO}">
																<input name="cfactura_det[]" id="cfactura_det[]" type="hidden" class="form-control hidden" value="{CFACTURA_DET}">
															</td>
															<td><input name="concepto[]" id="concepto[]" class="form-control" readonly value="{CONCEPTO}"></td>
															<td><input name="cant[]" id="cant[]" class="form-control numeric" readonly value="{CANTIDAD}"></td>
															<td><input name="precio[]" id="precio[]" class="form-control numeric" readonly value="{PRECIO}"></td>
															<td><input name="total[]" id="total[]" class="form-control" readonly value="{TOTAL}"></td>
														</tr>
														<!-- END BLOCK : cobro_det -->
														</tbody>
													</table>
													<p style="text-align:right;">
														<p></p>
														<a id="Conceptos_btn" class="btn btn-default form-btn search_data" data-toggle="modal" data-target="#Modal_"><i class="fa fa-plus"></i> AGREGAR</a>
													</p>
												</div>
												<div class="row show-grid">
													<div class="col-xs-6 col-sm-4">
														<label class="control-label" for="conceptos_count_c">CONCEPTOS</label>
														<input id="conceptos_count_c" type="text" readonly value="{CONCEPTOS}" class="form-control">
													</div>
													<div class="col-xs-6 col-sm-4">
														<label class="control-label" for="conceptos_sub_total_c">SUB TOTAL</label>
														<input id="conceptos_sub_total_c" type="text" readonly value="{monto_total}" class="form-control">
													</div>
													<div class="clearfix visible-xs"></div>
													<div class="col-xs-6 col-sm-4">
														<label class="control-label" for="conceptos_total_c">TOTAL</label>
														<input id="conceptos_total_c" type="text" readonly value="{monto_total}" class="form-control">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12"><p></p><div id="log" class="log alert {inv_mensaje_class}">{mensaje}</div></div>
							</div>
							<div class="row">
								<div class="col-lg-12">
								<p style="text-align:center;">
									<input type="hidden" id="accion" name="accion" class="form-control hidden" value="{accion}">
									<input type="hidden" id="cfactura" name="cfactura" class="form-control hidden" value="{cfactura}">
									<a id="bt_new" class="btn btn-default form-btn"><i class="fa fa-file-o"></i> NUEVO</a>
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
		</div>
	</div>
</div>