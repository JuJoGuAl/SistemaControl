<script>
$(document).ready(function(){
  var ref,url='undefined';
  $('#mats_car').DataTable({
    responsive: true
  });
  $('.tooltip-mats_cars').tooltip({
    selector: "[data-toggle2=tooltip]",
    container: "body"
  });
  $('#Modal_Carreras').on('show.bs.modal', function (){
    var non_car = new Array();
    $('#mats_car tbody tr td input[id^="ccarrera"]').each(function(row, tr){
      non_car.push($(this).val());
    });
    $.ajax({
      cache: false,
      type: 'POST',
      url: './modulos/maestras/modal.php',
      data: 'no_car='+non_car+'&obj=mat&accion=get_car',
      dataType:'json',
      success: function(data){
        $('#carreras_c').html('');
        $('#carreras_c').append("<option value='-1'>SELECCIONE...</option>");
        $.each(data, function(key,value){
          $('#carreras_c').append("<option value='"+value.CCARRERA+"'>"+value.DESCRIPCION+"</option>");
        });
      }
    });
  });
  $('#Modal_Preslaciones_new').on('show.bs.modal', function (){
    var non_pres = new Array();
    $('#mats_pres tbody tr td input[id^="cprelacion"]').each(function(row, tr){
      non_pres.push($(this).val());
    });
    non_pres.push($("#cmateria").val());
    var carrera = $("#ccarrera").val();
    var sem1 = $("#csem").val();
    $.ajax({
      cache: false,
      type: 'POST',
      url: './modulos/maestras/modal.php',
      data: 'no_pre='+non_pres+'&obj=mat&accion=get_pres'+'&car='+carrera+'&sem='+sem1,
      dataType:'json',
      success: function(data){
        $('#preslacion_c').html('');
        $('#preslacion_c').append("<option value='-1'>SELECCIONE...</option>");
        $.each(data, function(key,value){
          $('#preslacion_c').append("<option value='"+value.CMATERIA+"'>"+value.MATERIA+"</option>");
        });
      }
    });
  });
  $('#tipo_c').on('change', function (){
    var tipo = $(this).val();
    if (tipo==1){
      $('#cant_g').hide();
      $('#preslacion_g').show();
    }else if (tipo==2){
      $('#cant_g').show();
      $('#preslacion_g').hide();
    }else{
      $('#cant_g').hide();
      $('#preslacion_g').hide();
    }
  });
  $(document).on('click', '.presla', function(){
    $('#mats_pres tbody').html('');
    var ccar = ($(this).attr('data-car')), cmat = $('#cmateria').val();
    $.ajax({
      cache: false,
      type: 'POST',
      url: './modulos/maestras/modal.php',
      data: 'ccar='+ccar+'&cmat='+cmat+'&obj=mat&accion=get_presla',
      dataType:'json',
      success: function(data){
        $.each(data, function(key,value){
          var tr;
          tr = '<tr>';
          tr = tr + '<td>'+value.PRELACION+'<input type="hidden" id="cprelacion" name="cprelacion" class="form-control hidden" value="'+value.CPRELACION+'"></td></td>';
          tr = tr + '<td>'+value.CANTIDAD+'</td>';
          tr = tr + '<td>'+value.acciones+'</td>';
          tr = tr + '</tr>';
          $("#mats_pres tbody").append(tr);
        });
        $('.tooltip-mats_pres').tooltip({
          selector: "[data-toggle2=tooltip]",
          container: "body"
        });
      }
    });
  });
  function verificar(){
    var ccar='carreras', sem='semestre',valido=true;
    valido = valido && list(ccar);
    valido = valido && validar(sem);
    valido = valido && IsNumber(sem);
    return valido;
  }
  function verificar_pres(){
    var tipo='tipo', op = $('#tipo_c').val(), pres='preslacion', cant='cant',valido=true;
    valido = valido && list(tipo,'log_presla');
    if(op==1){
      valido = valido && list(pres,'log_presla');
    }else if(op==2){
      valido = valido && IsNumber(cant,'log_presla');
    }
    return valido;
  }
  $(document).on('click', '.btn', function(){
    button = $(this).attr('id');
    clear_log();
    switch (button){
      case 'bt_save':
        if(verificar()){
          $('#car_mats_form').submit();
        }
      break;
      case 'bt_save_pre':
      clear_log('log_presla');
      if(verificar_pres()){
          $('#mats_pres_form').submit();
        }
      break;
      default:
        objeto='undefined';
    }
  });
  $('#car_mats_form').on('submit', function(e){
    e.preventDefault();
    $.post('./modulos/maestras/modal.php',$('#car_mats_form').serialize(),function(data, status, xhr){
      if(data.titulo=="ERROR"){
        $('#log').addClass("alert-danger");
        $('#log').html(data.texto);
        $('#log').fadeIn('slown');
      }else if(data.titulo=="OK"){
        location.reload(true);
      }else{
        console.log("Estatus: "+status+"XHR: "+xhr);
      }
    },"json");
  });
  $('#mats_pres_form').on('submit', function(e){
    e.preventDefault();
    $.post('./modulos/maestras/modal.php',$('#mats_pres_form').serialize(),function(data, status, xhr){
      if(data.titulo=="ERROR"){
        $('#log_presla').addClass("alert-danger");
        $('#log_presla').html(data.texto);
        $('#log_presla').fadeIn('slown');
      }else if(data.titulo=="OK"){
        location.reload(true);
      }else{
        console.log("Estatus: "+status+"XHR: "+xhr);
      }
    },"json");
  });
  $('#Modal_Preslaciones').on('show.bs.modal', function(event) {
      $("#cpreslacion").val($(event.relatedTarget).data('id'));
      $("#ccarrera").val($(event.relatedTarget).data('car'));
      $("#csem").val($(event.relatedTarget).data('sem'));
  });
});
</script> 
<div class="modal modals fade" id="crud_materias_carreras" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-principal">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="ModalLabel">{car_name}</h4>
        <input type="hidden" id="cmateria" name="cmateria" class="form-control hidden" value="{mat_id}">
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading" id="Modal_Text">{form_subtitle}</div>
            <div class="panel-body">
                <div class="row">
                  <div class="col-xs-12">
                    <p style="text-align:right;">
                        <p></p>
                        <a id="bt_carreras" class="btn btn-default form-btn" data-toggle="modal" data-target="#Modal_Carreras"><i class="fa fa-plus"></i> NUEVA</a>
                      </p>
                    <div class="table-responsive">
                      <table width="100%" class="table table-striped table-bordered table-hover" id="mats_car">
                        <thead>
                          <tr>
                            <th>CODIGO</th>
                            <th>DESCRIPCION</th>
                            <th>SEM</th>
                            <th>ACCIONES</th>
                          </tr>
                        </thead>
                        <tbody>
                        <!-- START BLOCK : data_mats_cars -->
                        <tr class="{class}">
                          <td><input name="ccarrera[]" id="ccarrera[]" type="hidden" class="form-control hidden" value="{CCARRERA}">{CODIGO}</td>
                          <td>{DESCRIPCION}</td>
                          <td>{SEMESTRE}</td>
                          <td>{actions}</td>
                        </tr>
                        <!-- END BLOCK : data_mats_cars -->
                        </tbody>
                      </table>
                    </div>
                  </div>                
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="Modal_Carreras" tabindex="-1" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="ModalLabel">CARRERAS POR MATERIA</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading" id="Modal_Text">UTILICE EL FORMULARIO PARA CREAR UNA RELACIÓN ENTRE LA MATERIA Y UNA CARRERA</div>
            <div class="panel-body">
              <div class="dataTable_wrapper" id="Modal_Content">
                <form role="form" name="car_mats_form" id="car_mats_form" method="post" enctype="multipart/form-data">
                  <div id="carreras_g" class="form-group input-group">
                    <label class="control-label" for="carreras_c">CARRERAS DISPONIBLES</label>
                    <select name="carreras_c" id="carreras_c" class="form-control">
                    </select>
                  </div>
                  <div id="semestre_g" class="form-group input-group">
                    <label class="control-label" for="semestre_c">SEMESTRE</label>
                    <input id="semestre_c" name="semestre_c" type="text" class="form-control numeric" maxlength="2">
                  </div>
                  <div class="col-xs-12">
                    <div class="row">
                      <div class="col-lg-12"><p></p><div id="log" class="log alert">{mensaje}</div></div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <p style="text-align:center;">
                          <input type="hidden" id="accion" name="accion" class="form-control hidden" value="{accion}">
                          <input type="hidden" id="obj" name="obj" class="form-control hidden" value="{obj}">
                          <input type="hidden" id="cmateria" name="cmateria" class="form-control hidden" value="{mat_id}">
                          <a id="bt_save" class="btn btn-default form-btn"><i class="fa fa-save"></i> GUARDAR</a>
                        </p>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="Modal_Preslaciones" tabindex="-1" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="ModalLabel">PRELACIONES DE LA MATERIA</h4>
        <input type="hidden" id="ccarrera" name="ccarrera" class="form-control hidden">
        <input type="hidden" id="csem" name="csem" class="form-control hidden">
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading" id="Modal_Text">{form_subtitle2}</div>
            <div class="panel-body">
                <div class="row">
                  <div class="col-xs-12">
                    <p style="text-align:right;">
                        <p></p>
                        <a id="bt_carreras" class="btn btn-default form-btn" data-toggle="modal" data-target="#Modal_Preslaciones_new"><i class="fa fa-plus"></i> NUEVA</a>
                      </p>
                    <div class="table-responsive">
                      <table width="100%" class="table table-striped table-bordered table-hover" id="mats_pres">
                        <thead>
                          <tr>
                            <th>REQUISITO</th>
                            <th>CANT</th>
                            <th>ACCIONES</th>
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
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="Modal_Preslaciones_new" tabindex="-1" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="ModalLabel">REQUISITOS POR MATERIA - CARRERA</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading" id="Modal_Text">UTILICE EL FORMULARIO PARA CREAR UN REQUISITO</div>
            <div class="panel-body">
              <div class="dataTable_wrapper" id="Modal_Content">
                <form role="form" name="mats_pres_form" id="mats_pres_form" method="post" enctype="multipart/form-data">
                  <div id="tipo_g" class="form-group input-group">
                    <label class="control-label" for="tipo_c">TIPO DE PRELACIÓN</label>
                    <select name="tipo_c" id="tipo_c" class="form-control">
                      <option value='-1'>SELECCIONE...</option>
                      <option value='1'>REQUISITO</option>
                      <option value='2'>UC APROBADAS</option>
                    </select>
                  </div>
                  <div id="cant_g" class="form-group input-group" style="display:none;">
                    <label class="control-label" for="cant_c">CANTIDAD</label>
                    <input id="cant_c" name="cant_c" type="text" class="form-control numeric" maxlength="2">
                  </div>
                  <div id="preslacion_g" class="form-group input-group" style="display:none;">
                    <label class="control-label" for="preslacion_c">CO-REQUISITO</label>
                    <select name="preslacion_c" id="preslacion_c" class="form-control">
                    </select>
                  </div>
                  <div class="col-xs-12">
                    <div class="row">
                      <div class="col-lg-12"><p></p><div id="log_presla" class="log alert">{mensaje}</div></div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <p style="text-align:center;">
                          <input type="hidden" id="accion" name="accion" class="form-control hidden" value="{accion2}">
                          <input type="hidden" id="obj" name="obj" class="form-control hidden" value="{obj}">
                          <input type="hidden" id="cpreslacion" name="cpreslacion" class="form-control hidden" value="{car_id">
                          <a id="bt_save_pre" class="btn btn-default form-btn"><i class="fa fa-save"></i> GUARDAR</a>
                        </p>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>