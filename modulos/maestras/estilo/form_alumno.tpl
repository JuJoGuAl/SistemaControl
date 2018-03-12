<script>
$(document).ready(function(){
  var button = '', objeto='';
  $('.fecha').datepicker({
      format: "dd/mm/yyyy",
      autoclose: true,
      todayHighlight: true,
      language: "es"
    });
  $('.pais').change(function(){
    var c_pais=($(this).val()),edo=($(this).attr('data-edo'));
    $.ajax({
      url: './modulos/maestras/modal.php',
      type: 'POST',
      data: 'objeto=estados&pais='+c_pais,
      dataType:'json'
    })
    .done(function(data){
      $('#'+edo).html('');
      $('#'+edo).append("<option value='-1'>SELECCIONE...</option>");
      $.each(data, function(key,value){
        $('#'+edo).append("<option value='"+value.CESTADO+"'>"+value.nombre+"</option>");
      });
    });
  });
  $('.edo').change(function(){
    var c_edo=($(this).val()),ciu=($(this).attr('data-ciu'));
    $.ajax({
      url: './modulos/maestras/modal.php',
      type: 'POST',
      data: 'objeto=ciudades&cedo='+c_edo,
      dataType:'json'
    })
    .done(function(data){
      $('#'+ciu).html('');
      $('#'+ciu).append("<option value='-1'>SELECCIONE...</option>");
      $.each(data, function(key,value){
        $('#'+ciu).append("<option value='"+value.ciuc+"'>"+value.ciu+"</option>");
      })
    });
  });
  $(document).on('click', '.btn', function(){
    button = $(this).attr('id');
    clear_log();
    switch (button){
      case 'bt_save':
        if(verificar()){
          $('#estudiante_form').submit();
        }
      break;
      default:
        objeto='undefined';
    }
  });
  $('#todo_c').click(function(){
    $(':checkbox').prop('checked',this.checked);
  });
  $('#cedula_c').blur(function(){
    var ced='cedula';
    if (validar(ced)){
      if(IsCed(ced)){
        check_ced(ced,'alum',$('#calumno').val());
      }
    }
  });
    function verificar(){
      var ced='cedula', ap='apellidos', nom='nombres', sex='sexo', tel='telefono',
      pa='pais', edo='estado', cd='ciudad', fn='fecha_nac', ci='civil', dir='direccion',valido=true;
      valido = valido && validar(ced);
      valido = valido && IsCed(ced);
      valido = valido && check_ced(ced,'alum',$('#calumno').val());
      valido = valido && validar(ap);
      valido = valido && validar(nom);
      valido = valido && list(sex);
      valido = valido && validar(tel);
      valido = valido && list(pa);
      valido = valido && list(edo);
      valido = valido && list(cd);
      valido = valido && validar(fn);
      valido = valido && list(ci);
      valido = valido && validar(dir);
      if($('#plantel_c').val()!=''){
        var new_camp='';
        $(".obligatorio").each(function(){
          new_camp=($(this).attr('id').split('_c'));
          if(new_camp[0]=='plantel_tipo' || new_camp[0]=='pais_plantel' || new_camp[0]=='estado_plantel' || new_camp[0]=='ciudad_plantel'){
            valido = valido && list(new_camp[0]);
          }else{
            valido = valido && validar(new_camp[0]);
          }
        });
      }
      return valido;
    }
    $(function(){
      $('#estudiante_form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: './modulos/maestras/modal.php',
            type: 'POST',
            data: $('#estudiante_form').serialize(),
            dataType:'json'
          })
        .done(function(alum){
          if(alum.titulo=="ERROR"){
            mensaje(alum.texto,"ERROR");
          }else if(alum.titulo=="OK"){
            clear_log();
            location.reload(true);
          }else{
            console.log("Estatus: "+status+"XHR: "+xhr);
          }
        })
        .fail(function(x,err,msj){
          console.log(msj);
        });
      });
  });
});
</script>
<div class="modal modals fade" id="form_alumno" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="ModalLabel">{form_title}</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading" id="Modal_Text">{form_subtitle}</div>
            <div class="panel-body">
              <form role="form" name="estudiante_form" id="estudiante_form" method="post" enctype="multipart/form-data">
                <div class="row">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#personales" data-toggle="tab" aria-expanded="true">PERSONALES</a></li>
                    <!-- START BLOCK : pst_acade -->
                    <li class=""><a href="#academicos" data-toggle="tab" aria-expanded="false">ACADEMICOS</a></li>
                    <!-- END BLOCK : pst_acade -->
                    <li class=""><a href="#egreso" data-toggle="tab" aria-expanded="false">DATOS EGRESO</a></li>
                    <li class=""><a href="#ALUM_DOCUMENTOS" data-toggle="tab" aria-expanded="false">DOCUMENTOS</a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade active in" id="personales">
                      <div class="col-xs-6 col-md-4">
                        <div id="cedula_g" class="form-group input-group">
                          <label class="control-label" for="cedula_c">CEDULA</label>
                          <input id="cedula_c" name="cedula_c" type="text" class="form-control" {read} value="{CEDULA}" maxlength="10">
                          <p class="help-block">El formato de la Cédula debe ser VXXXXXXX</p>
                        </div>
                        <div id="apellidos_g" class="form-group input-group">
                          <label class="control-label" for="apellidos_c">APELLIDOS</label>
                          <input id="apellidos_c" name="apellidos_c" type="text" class="form-control" value="{APELLIDOS}">
                        </div>
                        <div id="nombres_g" class="form-group input-group">
                          <label class="control-label" for="nombres_c">NOMBRES</label>
                          <input id="nombres_c" name="nombres_c" type="text" class="form-control" value="{NOMBRES}">
                        </div>
                        <div id="sexo_g" class="form-group input-group">
                          <label class="control-label" for="sexo_c">SEXO</label>
                          <select name="sexo_c" id="sexo_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : sexos_det -->
                            <option value="{sex_code}" {selected}>{sex_name}</option>
                            <!-- END BLOCK : sexos_det -->
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-4">
                        <div id="telefono_g" class="form-group input-group">
                          <label class="control-label" for="telefono_c">TELEFONO</label>
                          <input id="telefono_c" name="telefono_c" type="text" class="form-control" value="{TELEFONOS}">
                        </div>
                        <div id="pais_g" class="form-group input-group">
                          <label class="control-label" for="pais_c">PAIS</label>
                          <select name="pais_c" id="pais_c" data-edo="estado_c" class="form-control pais">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : pais_det -->
                            <option value="{CPAIS}" {selected}>{NOMBRE}</option>
                            <!-- END BLOCK : pais_det -->
                          </select>
                        </div>
                        <div id="estado_g" class="form-group input-group">
                          <label class="control-label" for="estado_c">ESTADO</label>
                          <select name="estado_c" id="estado_c" data-ciu="ciudad_c" class="form-control edo">
                          <!-- START BLOCK : edos_det -->
                            <option value="{CESTADO}" {selected}>{NOMBRE}</option>
                            <!-- END BLOCK : edos_det -->
                          </select>
                        </div>
                        <div id="ciudad_g" class="form-group input-group">
                          <label class="control-label" for="ciudad_c">CIUDAD</label>
                          <select name="ciudad_c" id="ciudad_c" class="form-control">
                          <!-- START BLOCK : ciuds_det -->
                            <option value="{CCIUDAD}" {selected}>{NOMBRE}</option>
                            <!-- END BLOCK : ciuds_det -->
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-4">
                        <div id="fecha_nac_g" class="form-group input-group">
                          <label class="control-label" for="fecha_nac_c">FECHA NAC</label>
                          <input id="fecha_nac_c" name="fecha_nac_c" type="text" class="form-control fecha" value="{FECHA_NAC}">
                        </div>                        
                        <div id="civil_g" class="form-group input-group">
                          <label class="control-label" for="civil_c">EDO. CIVIL</label>
                          <select name="civil_c" id="civil_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : edo_det -->
                            <option value="{edo_name}" {selected}>{edo_name}</option>
                            <!-- END BLOCK : edo_det -->
                          </select>
                        </div>
                        <div id="direccion_g" class="form-group input-group">
                          <label class="control-label" for="direccion_c">DIRECCION</label>
                          <textarea id="direccion_c" name="direccion_c" class="form-control" rows="2">{DIRECCION}</textarea>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="academicos">
                      <div class="col-xs-6 col-md-4">
                        <div id="f_ins_g" class="form-group input-group">
                          <label class="control-label" for="f_ins">FECHA DE REGISTRO</label>
                          <input id="f_ins" name="f_ins" type="text" class="form-control" readonly value="{FECHA_REG}">
                        </div>
                        <!-- START BLOCK : st_block -->
                        <div id="status_g" class="form-group input-group">
                          <label class="control-label" for="status_c">STATUS</label>
                          <select name="status_c" id="status_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : st_det -->
                            <option value="{CESTATUS}" {selected}>{NOMBRE}</option>
                            <!-- END BLOCK : st_det -->
                          </select>
                        </div>
                        <!-- END BLOCK : st_block -->
                      </div>
                    </div>
                    <div class="tab-pane fade" id="ALUM_DOCUMENTOS">
                      <div class="col-xs-12 col-md-8">
                        <div id="ALUM_DOCUMENTOS_g" class="form-group">
                          <label class="control-label" for="ALUM_DOCUMENTOS_c">DOCUMENTOS CONSIGNADOS</label>
                          <div class="checkbox">
                            <label class="checks"><input id="todo_c" name="todo_c" type="checkbox" value=""><strong>SELECCIONAR TODOS</strong></label>
                          </div>
                          <!-- START BLOCK : ALUM_DOCUMENTOS -->
                          <div class="checkbox">
                            <label class="checks"><input id="doc[]" name="doc[]" type="checkbox" value="{CDOCUMENTO}" {checked}>{DESCRIPCION}</label>
                          </div>
                          <!-- END BLOCK : ALUM_DOCUMENTOS -->
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="egreso">
                    <!-- START BLOCK : plantel_egreso -->
                      <div class="col-xs-6 col-md-4">
                        <div id="plantel_g" class="form-group input-group">
                          <label class="control-label" for="plantel_c">PLANTEL</label>
                          <input id="plantel_c" name="plantel_c" type="text" class="form-control obligatorio" value="{PLANTEL}">
                        </div>
                        <div id="plantel_tipo_g" class="form-group input-group">
                          <label class="control-label" for="plantel_tipo_c">TIPO DE PLANTEL</label>
                          <select name="plantel_tipo_c" id="plantel_tipo_c" class="form-control obligatorio">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : plantel_tipo -->
                            <option value="{tipo_code}" {selected}>{tipo_name}</option>
                            <!-- END BLOCK : plantel_tipo -->
                          </select>
                        </div>
                        <div id="plantel_num_g" class="form-group input-group">
                          <label class="control-label" for="plantel_num_c">NO. PLANTEL</label>
                          <input id="plantel_num_c" name="plantel_num_c" type="text" class="form-control obligatorio" value="{NO_PLANTEL}">
                        </div>
                        <div id="year_g" class="form-group input-group">
                          <label class="control-label" for="year_c">AÑO DE EGRESO</label>
                          <input id="year_c" name="year_c" type="text" class="form-control numeric obligatorio" value="{GRADUA_YEAR}">
                        </div> 
                      </div>
                      <div class="col-xs-6 col-md-4">
                        <div id="especialidad_g" class="form-group input-group">
                          <label class="control-label" for="especialidad_c">ESPECIALIDAD</label>
                          <input id="especialidad_c" name="especialidad_c" type="text" class="form-control" value="{ESPECIALIDAD}">
                        </div>
                        <div id="pais_plantel_g" class="form-group input-group">
                          <label class="control-label" for="pais_plantel_c">PAIS</label>
                          <select name="pais_plantel_c" id="pais_plantel_c" data-edo="estado_plantel_c" class="form-control pais obligatorio">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : pais_plantel_det -->
                            <option value="{CPAIS}" {selected}>{NOMBRE}</option>
                            <!-- END BLOCK : pais_plantel_det -->
                          </select>
                        </div>
                        <div id="estado_plantel_g" class="form-group input-group">
                          <label class="control-label" for="estado_plantel_c">ESTADO</label>
                          <select name="estado_plantel_c" id="estado_plantel_c" data-ciu="ciudad_plantel_c" class="form-control edo obligatorio">
                          <!-- START BLOCK : edos_plantel_det -->
                            <option value="{CESTADO}" {selected}>{NOMBRE}</option>
                            <!-- END BLOCK : edos_plantel_det -->
                          </select>
                        </div>
                        <div id="ciudad_plantel_g" class="form-group input-group">
                          <label class="control-label" for="ciudad_plantel_c">CIUDAD</label>
                          <select name="ciudad_plantel_c" id="ciudad_plantel_c" class="form-control obligatorio">
                          <!-- START BLOCK : ciuds_plantel_det -->
                            <option value="{CCIUDAD}" {selected}>{NOMBRE}</option>
                            <!-- END BLOCK : ciuds_plantel_det -->
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-4">
                        <div id="fec_cert_g" class="form-group input-group">
                          <label class="control-label" for="fec_cert_c">FECHA DE CERTIFICACION</label>
                          <input id="fec_cert_c" name="fec_cert_c" type="text" class="form-control fecha" value="{FEC_CERTIFICACION}">
                        </div>                        
                        <div id="fec_titulo_g" class="form-group input-group">
                          <label class="control-label" for="fec_titulo_c">FECHA EXP TITULO</label>
                          <input id="fec_titulo_c" name="fec_titulo_c" type="text" class="form-control fecha" value="{FEC_EXP_TITULO}">
                        </div>
                        <div id="no_reg_g" class="form-group input-group">
                          <label class="control-label" for="no_reg_c">NO. REGISTRO</label>
                          <input id="no_reg_c" name="no_reg_c" type="text" class="form-control" value="{NO_REGISTRO}">
                        </div>
                        <div id="ins_cod_g" class="form-group input-group">
                          <label class="control-label" for="ins_cod_c">NO. INSCRIPCION</label>
                          <input id="ins_cod_c" name="ins_cod_c" type="text" class="form-control" value="{NO_INSCRIPCION}">
                        </div>
                      </div>
                      <!-- END BLOCK : plantel_egreso -->
                    </div>
                  </div>                
                </div>
                <div class="row">
                  <div class="col-lg-12"><p></p><div id="log" class="log alert {inv_mensaje_class}">{inv_mensaje}</div></div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <p style="text-align:center;">
                      <input type="hidden" id="accion" name="accion" class="form-control hidden" value="{accion}">
                      <input type="hidden" id="obj" name="obj" class="form-control hidden" value="{obj}">
                      <input type="hidden" id="calumno" name="calumno" class="form-control hidden" value="{id}">
                      <a id="bt_save" class="btn btn-default form-btn"><i class="fa fa-save"></i> GUARDAR</a>
                    </p>
                  </div>
                  <!-- START BLOCK : det_datos -->
                  <div class="col-lg-12" style="font-size: 12px; text-align: right;">
                    <p><strong>CREADO POR: </strong>{USUARIO_CREADO}</p>
                    <p><strong>MODIFICADO POR: </strong>{USUARIO_MODIFICACION}</p>
                  </div>
                  <!-- END BLOCK : det_datos -->
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>