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
		var button = '', objeto='',obj='';
		/*Verifico, los campos para su procesar*/
		function verificar(){
			var ced='cedula', nom='nombres', ffac='f_can', table1 = $('#conceptos_det'), total1='conceptos_total', table2 = $('#pagos_det'), total2='pagos_det_total', valido=true;
			valido = valido && validar(ced);
			valido = valido && validar(nom);
			valido = valido && validar(ffac);
			valido = valido && count_row(table1,'una Factura');
			valido = valido && IsNumber(total1);
			if(button!='bt_print'){
				valido = valido && check_abono();
			}
			valido = valido && count_row(table2,'una Forma de Pago');
			valido = valido && IsNumber(total2);
			valido = valido && NumberEqual(total1,total2);
			return valido;
		}
		function check_abono(){
			var pass=true;
			$('[id^="concp_"]').each(function() {
				var saldo = +$(this).find('[id^="total"]').val(), abono = +$(this).find('[id^="abono"]').val(), fac = $(this).find('[id^="factura"]').val();
				if(abono<=0){
					$(this).addClass("danger");
					$('#log').addClass("alert-danger");
					$('#log').html('El monto abonado de la Factura <strong>'+fac+'</strong> No puede ser Igual o Inferior a 0');
					$('#log').fadeIn('slown');
					pass=pass && false;
				}else if(abono > saldo){
					$(this).addClass("danger");
					$('#log').addClass("alert-danger");
					$('#log').html('El monto abonado de la Factura <strong>'+fac+'</strong> Supera el saldo de la misma!');
					$('#log').fadeIn('slown');
					pass=pass && false;
				}else{
					$(this).removeClass("danger");
					pass=pass && true;
				}
			});
			if(pass){
				clear_log();
			}
			return pass;
		}
		function totaliza(){
			var sub_total = 0, pago = 0;
			sub_total=$('input[name^="abono"]').sumValues();
			pago=$('input[name^="pago"]').sumValues();
			$('#conceptos_total_c').val(parseFloat(sub_total));
			$('#conceptos_total_n').val(parseFloat(sub_total));
			$('#pagos_det_total_c').val(parseFloat(pago));
			$('#conceptos_count_c').val(parseFloat($('#conceptos_det tbody tr').length));
		}
		function check_doc_pag(){
			$('[id^="pagos_det"]').each(function(){
				var tpago = $(this).find('[id^="tpago"]').val();
				if(tpago==1){
					$(this).find('[id^="doc"]').val(0);
					$(this).find('[id^="fecha"]').val($('#f_can_c').val());
					$(this).find('[id^="doc"]').prop('readonly', true);
					$(this).find('[id^="fecha"]').prop('readonly', true);
				}else{
					$(this).find('[id^="doc"]').val('');
					$(this).find('[id^="fecha"]').val($('#f_can_c').val());
					$(this).find('[id^="doc"]').prop('readonly', false);
					$(this).find('[id^="fecha"]').prop('readonly', false);
				}
			});
		}
		/*LiveQuery dispara un evento SIEMPRE que se cumpla una condicion*/
		/*Todos los inputs con la clase numeric, chequeo su keydown event*/
		$('.numeric').livequery(function(){
			$(this).on("input", function (){
				totaliza();
				check_abono();
			});
		});
		$('[id^="tpago"]').livequery(function(){
			$(this).on("change", function (){
				check_doc_pag();
			});
		});
		$('.fecha').livequery(function(){
			$(this).datepicker({
				format: "dd/mm/yyyy",
				autoclose: true,
				todayHighlight: true,
				language: "es"
			});
		});
		$('button[id^="del_conc"]').livequery(function(){
			$(this).click(function (){
				$(this).closest('[id^="concp_"]').remove();
				totaliza();
				check_abono();
			});
		});
		$('button[id^="del_pago"]').livequery(function(){
			$(this).click(function (){
				$(this).closest('[id^="pagos_det"]').remove();
				totaliza();
				check_abono();
			});
		});
		/*Verifico el STATUS*/
		function check_status(){
			if($('#status_c').val()=='PROCESADO'){
				$('.form-control').prop('disabled', true);
				$('[id^="del_conc"]').prop('disabled', true);
				$('[id^="del_pago"]').prop('disabled', true);
				$('#Cliente_btn').prop('disabled', true);
				$('#pagos_btn').prop('disabled', true);
				$('#bt_save').prop('disabled', true);
			}else{
				$('.form-control').prop('disabled', false);
				$('[id^="del_conc"]').prop('disabled', false);
				$('[id^="del_pago"]').prop('disabled', false);
				$('#Cliente_btn').prop('disabled', false);
				$('#pagos_btn').prop('disabled', false);
				$('#bt_save').prop('disabled', false);
			}
		}
		$('.save_modal').on("click",function(){
			$('#conceptos_det tbody').html('');
			$('.facs_clientes').each(function(){
				if($(this).is(":checked")){
					var fac = ($(this).closest("tr").find('._code').text());
					var per = ($(this).closest("tr").find('._peri').text());
					var monto = ($(this).closest("tr").find('._monto').text());
					var saldo = ($(this).closest("tr").find('._saldo').text());
					tr ='<tr id="concp_">';
					tr=tr+'<td><button id="del_conc" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button>';
					tr=tr+'<input name="cfactura[]" id="cfactura[]" type="hidden" class="form-control hidden" value="'+fac+'"></td>';
					tr=tr+'<td><input name="factura[]" id="factura[]" class="form-control" readonly value="'+fac+'"></td>';
					tr=tr+'<td><input name="peri[]" id="peri[]" class="form-control" readonly value="'+per+'">';
					tr=tr+'<td><input name="monto[]" id="monto[]" class="form-control numeric" readonly value="'+monto+'"></td>';
					tr=tr+'<td><input name="total[]" id="total[]" class="form-control numeric" readonly value="'+saldo+'"></td>';
					tr=tr+'<td><input name="abono[]" id="abono[]" class="form-control numeric" value="0"></td>';
					tr=tr+'</tr>';
					$("#conceptos_det tbody").append(tr);
					totaliza();
				}
			});
 			$('.nav-tabs a[href="#det"]').tab('show');
		});
		/*Al cerrar el modal Toma la linea Verde y carga sus datos en el FORM*/
		$('#Modal_').on('hidden.bs.modal', function (){
			if ($('.datatables tbody tr.success').hasClass('success')){
				if($(this).find('.dataTables_empty').text()==''){
					//Si la fila que se selecciona posee datos
					if(objeto=='Clientes'){
						var cliente=$('.datatables tbody tr.success .'+objeto+'_name').text();
						$('#Cliente_c').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#Cliente_n').val($('.datatables tbody tr.success .'+objeto+'_id').text());
						$('#cedula_c').val($('.datatables tbody tr.success .'+objeto+'_ced').text());
						$('#nombres_c').val(cliente);
						$(".save_modal").show();
						var ccliente = $('.datatables tbody tr.success .'+objeto+'_id').text();
						$.ajax({
							url: './modulos/caja/modal.php',
							type: 'POST',
							data: 'accion=facturas&ccliente='+ccliente,
							dataType:'json'
						})
						.done(function(data){
							$('#Modal_').modal('show');
							$('#ModalLabel').html('FACTURAS DEL CLIENTE '+cliente);
							$('#Modal_Text').html('Filtre según el criterio ingresándolo en el recuadro de <strong>Filtrar</strong>');
							$('#Modal_Text').show();
							$('#Modal_Content').html(data);
							$('#Modal_loader').hide();
							$('.datatables').DataTable({
								responsive: true
							});
						})
						.fail(function(){
							$('#ModalLabel').html('Error');
							$('#Modal_Text').hide();
							$('#Modal_Content').html('<i class="fa fa-warning"></i> Ocurrió un problema al intentar cargar la información, consulte a soporte');
							$('#Modal_loader').hide();
						});
					}else if(objeto=='Recibos'){
						var crecibo = $('.datatables tbody tr.success .'+objeto+'_id').text();
						$.ajax({
							url: './modulos/caja/modal.php',
							type: 'POST',
							data: 'accion=Recibos&crecibo='+crecibo,
							dataType:'json'
						})
						.done(function(data){
							$('#id').val(data.cab.CODIGO);
							$('#accion').val('block');
							$('#Recibo_c').val(data.cab.CODIGO);
							$('#Cliente_c').val(data.cab.CODE_CLIENTE);
							$('#Cliente_n').val(data.cab.CODE_CLIENTE);
							$('#cedula_c').val(data.cab.CEDULA);
							$('#nombres_c').val(data.cab.CLIENTE);
							$('#status_c').val(data.cab.ESTATUS);
							$('#f_can_c').val(data.cab.FECHA_CANCELACION);
							$('#conceptos_det tbody').html('');
							$.each(data.fac, function(key,value){
								tr ='<tr id="concp_">';
								tr=tr+'<td><button id="del_conc" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button>';
								tr=tr+'<input name="cfactura[]" id="cfactura[]" type="hidden" class="form-control hidden" value="'+value.CODE_FACTURA+'"></td>';
								tr=tr+'<td><input name="factura[]" id="factura[]" class="form-control" readonly value="'+value.CODE_FACTURA+'"></td>';
								tr=tr+'<td><input name="peri[]" id="peri[]" class="form-control" readonly value="'+value.PERIODO+'">';
								tr=tr+'<td><input name="monto[]" id="monto[]" class="form-control" readonly value="'+value.NETO_FACTURA+'"></td>';
								tr=tr+'<td><input name="total[]" id="total[]" class="form-control" readonly value="'+value.SALDO_FACTURA+'"></td>';
								tr=tr+'<td><input name="abono[]" id="abono[]" class="form-control" readonly value="'+value.MONTO_FACTURA+'"></td>';
								tr=tr+'</tr>';
								$("#conceptos_det tbody").append(tr);
							});
 							$('#pagos_det tbody').html('');
							$.each(data.det, function(key,value){
								tr ='<tr id="pagos_det">';
								tr=tr+'<td><button id="del_pago" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button></td>';
								tr=tr+'<td><input name="tpago[]" id="tpago[]" class="form-control" value="'+value.TIPO+'"></td>';
								tr=tr+'<td><input name="pago[]" id="pago[]" class="form-control" value="'+value.MONTO_PAGO+'"></td>';
								tr=tr+'<td><input name="doc[]" id="doc[]" class="form-control" value="'+value.DOCUMENTO+'"></td>';
								tr=tr+'<td><input name="fecha[]" id="fecha[]" class="form-control" value="'+value.FECHA_DET+'"></td>';
								tr=tr+'</tr>';
								$("#pagos_det tbody").append(tr);
							});
 							totaliza();
 							check_status();
						});
					}
				}
			}
		});
		/*Controlo las acciones de cada Boton en el Form*/
		$(document).on('click', '.btn', function(e){
			button = $(this).attr('id');
			clear_log();
			switch (button){
				case 'Cliente_btn':
					objeto='Clientes';
				break;
				case 'Recibo_btn':
					objeto='Recibos';
				break;
				case 'pagos_btn':
					$.ajax({
						url: './modulos/caja/modal.php',
						type: 'POST',
						data: 'accion=pagos',
						dataType:'json'
					})
					.done(function(data){
						tr ='<tr id="pagos_det">';
						tr=tr+'<td><button id="del_pago" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button></td>';
						tr=tr+'<td><select name="tpago[]" id="tpago[]" class="form-control"></select></td>';
						tr=tr+'<td><input name="pago[]" id="pago[]" class="form-control numeric" value="0"></td>';
						tr=tr+'<td><input name="doc[]" id="doc[]" class="form-control numeric" maxlength="10" value="0"></td>';
						tr=tr+'<td><input name="fecha[]" id="fecha[]" class="form-control fecha" value="'+$('#f_can_c').val()+'"></td>';
						tr=tr+'</tr>';
						$("#pagos_det tbody").append(tr);
						$.each(data.pago, function(key,value){
							$($("#pagos_det tbody tr:last")).find('[id^="tpago"]').append("<option value='"+value.CODIGO+"'>"+value.TIPO_PAGO+"</option>");
						});
						totaliza();
						check_doc_pag();
					})
					.fail(function(){
						console.log(data);
					});
				break;
				case 'bt_new':
					window.location.href = "?mod=CAJA&submod=GEN_PAGO";
				break;
				case 'bt_print':
					if(verificar()){
						imprimir('./modulos/reportes/caja_recibo.php?code='+$('#id').val());
					}
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
		/*Abre el Modal segun el boton seleccionado, me llena la tabla y la muestra, si no consigue objetos llena un Modal con Info Generica*/
		$(document).on('click', '.search_data', function(e){
			$('#Modal_Content').html('');
			$('#Modal_loader').show();
			$(".save_modal").hide();
			$.ajax({
				url: './modulos/caja/modal.php',
				type: 'POST',
				data: 'accion='+objeto+'&obj='+obj,
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
									<li class=""><a href="#pago" data-toggle="tab" aria-expanded="false">FORMA DE PAGO</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade active in" id="fact">
										<div class="col-xs-6 col-md-4">
											<div id="Recibo_g" class="form-group input-group">
												<label class="control-label" for="Recibo_c">RECIBO</label>
												<input id="Recibo_c" name="Recibo_c" type="text" class="form-control numeric" value="{CODIGO}" readonly>
												<span class="input-group-btn"><button id="Recibo_btn" class="btn btn-default search_data" data-toggle="modal" data-target="#Modal_" type="button"><i class="fa fa-search"></i></button></span>
											</div>
											<div id="Cliente_g" class="form-group input-group">
												<label class="control-label" for="Cliente_c">CLIENTE</label>
												<input id="Cliente_c" name="Cliente_c" type="text" class="form-control hidden" value="{CODE_CLIENTE}">
												<input id="Cliente_n" name="Cliente_n" type="text" class="form-control" value="{CODE_CLIENTE}" autofocus>
												<span class="input-group-btn"><button id="Cliente_btn" class="btn btn-default search_data" data-toggle="modal" data-target="#Modal_" type="button"><i class="fa fa-search"></i></button></span>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
											<div id="cedula_g" class="form-group input-group">
												<label class="control-label" for="cedula_c">CEDULA</label>
												<input id="cedula_c" name="cedula_c" type="text" class="form-control" value="{CEDULA}"readonly>
											</div>
											<div id="nombres_g" class="form-group input-group">
												<label class="control-label" for="nombres_c">NOMBRE(S)</label>
												<input id="nombres_c" name="nombres_c" type="text" class="form-control" value="{CLIENTE}" readonly>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
											<div class="form-group input-group">
												<label class="control-label" for="status_c">ESTATUS</label>
												<input id="status_c" name="status_c" class="form-control" readonly type="text" value="{ESTATUS}">
											</div>
											<div id="f_can_g" class="form-group input-group">
												<label class="control-label" for="f_can_c">FECHA PAGO</label>
												<input id="f_can_c" name="f_can_c" type="text" class="form-control" value="{FECHA_CANCELACION}" readonly>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="det">
										<div class="panel panel-default">
											<div class="panel-heading">FACTURAS</div>
											<div class="panel-body">
												<div class="table-responsive">
													<table id="conceptos_det" class="table table-hover conceptos_det">
														<thead>
															<tr>
																<th width="50px">#</th>
																<th>FACTURA</th>
																<th>PERIODO</th>
																<th width="170px">MONTO</th>
																<th width="170px">SALDO</th>
																<th width="170px">ABONO</th>
															</tr>
														</thead>
														<tbody>
															<!-- START BLOCK : can_fac -->
															<tr id="pagos_det">
																<td>
																	<button id="del_conc" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button>
																	<input type="hidden" name="cfactura[]" id="cfactura[]" class="form-control hidden" readonly value="{CODE_FACTURA}">
																</td>
																<td><input name="factura[]" id="factura[]" class="form-control" readonly value="{CODE_FACTURA}"></td>
																<td><input name="peri[]" id="peri[]" class="form-control" readonly value="{PERIODO}"></td>
																<td><input name="monto[]" id="monto[]" class="form-control" readonly value="{NETO_FACTURA}"></td>
																<td><input name="total[]" id="total[]" class="form-control" readonly value="{SALDO_FACTURA}"></td>
																<td><input name="abono[]" id="abono[]" class="form-control" readonly value="{MONTO_FACTURA}"></td>
															</tr>
															<!-- END BLOCK : can_fac -->
														</tbody>
													</table>
												</div>
												<div class="row show-grid">
													<div class="col-xs-6 col-sm-6">
														<label class="control-label" for="conceptos_count_c">DOCUMENTOS A PAGAR</label>
														<input id="conceptos_count_c" type="text" readonly value="{FACTURAS}" class="form-control">
													</div>
													<div class="col-xs-6 col-sm-6">
														<label class="control-label" for="conceptos_total_c">MONTO A PAGAR</label>
														<input id="conceptos_total_c" type="text" readonly value="{MONTO_CANCELACION}" class="form-control">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="pago">
										<div class="panel panel-default">
											<div class="panel-heading">FORMA DE PAGOS</div>
											<div class="panel-body">
												<div class="table-responsive">
													<table id="pagos_det" class="table table-hover">
														<thead>
															<tr>
																<th width="50px">#</th>
																<th>FORMA DE PAGO</th>
																<th>MONTO</th>
																<th>DOCUMENTO</th>
																<th>FECHA</th>
															</tr>
														</thead>
														<tbody>
															<!-- START BLOCK : can_det -->
															<tr id="pagos_det">
																<td><button id="del_pago" class="btn btn-default btn-xs" type="button"><i class="fa fa-times"></i></button></td>
																<td><input name="tpago[]" id="tpago[]" class="form-control" readonly value="{TIPO}"></td>
																<td><input name="pago[]" id="pago[]" class="form-control numeric" readonly value="{MONTO_PAGO}"></td>
																<td><input name="doc[]" id="doc[]" class="form-control numeric" readonly value="{DOCUMENTO}"></td>
																<td><input name="fecha[]" id="fecha[]" class="form-control numeric" readonly value="{FECHA_DET}"></td>
															</tr>
															<!-- END BLOCK : can_det -->
														</tbody>
													</table>
													<p style="text-align:right;">
														<p></p>
														<a id="pagos_btn" class="btn btn-default form-btn"><i class="fa fa-plus"></i> AGREGAR</a>
													</p>
												</div>
												<div class="row show-grid">
													<div class="col-xs-6 col-sm-6">
														<label class="control-label" for="conceptos_total_n">MONTO A PAGAR</label>
														<input id="conceptos_total_n" type="text" readonly value="{MONTO_CANCELACION}" class="form-control">
													</div>
													<div class="col-xs-6 col-sm-6">
														<label class="control-label" for="pagos_det_total_c">TOTAL DE PAGO</label>
														<input id="pagos_det_total_c" type="text" readonly value="{MONTO_CANCELACION}" class="form-control">
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
				<button type="button" class="btn btn-primary save_modal" data-dismiss="modal">ACEPTAR</button>
			</div>
		</div>
	</div>
</div>