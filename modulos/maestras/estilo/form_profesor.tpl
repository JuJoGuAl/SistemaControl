<script>
$(document).ready(function(){
  var button = '', objeto='';
  $('#fecha_nac_c').datepicker({
      format: "dd/mm/yyyy",
      autoclose: true,
      todayHighlight: true,
      language: "es"
    });
  $('#pais_c').change(function(){
    var c_pais=($(this).val());
    $.ajax({
      url: './modulos/maestras/modal.php',
      type: 'POST',
      data: 'objeto=estados&pais='+c_pais,
      dataType:'json'
    })
    .done(function(data){
      $('#estado_c').html('');
      $('#estado_c').append("<option value='-1'>SELECCIONE...</option>");
      $.each(data, function(key,value){
        $('#estado_c').append("<option value='"+value.CESTADO+"'>"+value.nombre+"</option>");
      });
    });
  });
  $('#estado_c').change(function(){
    var c_edo=($(this).val());
    $.ajax({
      url: './modulos/maestras/modal.php',
      type: 'POST',
      data: 'objeto=ciudades&cedo='+c_edo,
      dataType:'json'
    })
    .done(function(data){
      $('#ciudad_c').html('');
      $('#ciudad_c').append("<option value='-1'>SELECCIONE...</option>");
      $.each(data, function(key,value){
        $('#ciudad_c').append("<option value='"+value.ciuc+"'>"+value.ciu+"</option>");
      })
    });
  });
  $(document).on('click', '.btn', function(){
      button = $(this).attr('id');
      clear_log();
      switch (button){
        case 'bt_save':
          if(verificar()){
            $('#form_profesores').submit();
          }
        break;
        default:
          objeto='undefined';
      }
    });
    $('#cedula_c').blur(function(){
      var ced='cedula';
      if (validar(ced)){
        if(IsCed(ced)){
          check_ced(ced,'prof',$('#cprofesor').val());
        }
      }
    });
    function verificar(){
      var ced='cedula', ap='apellidos', nom='nombres', sex='sexo', tel='telefono',
      pa='pais', edo='estado', cd='ciudad', fn='fecha_nac', ci='civil', dir='direccion', valido=true;
      valido = valido && validar(ced);
      valido = valido && IsCed(ced);
      valido = valido && check_ced(ced,'prof',$('#cprofesor').val());
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
      return valido;
    }
    $(function(){
      $('#form_profesores').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: './modulos/maestras/modal.php',
            type: 'POST',
            data: $('#form_profesores').serialize(),
            dataType:'json'
          })
        .done(function(alum){
          if(alum.titulo=="ERROR"){
            $('#log').addClass("alert-danger");
            $('#log').html(alum.texto);
            $('#log').fadeIn('slown');
          }else if(alum.titulo=="OK"){
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
<div class="modal modals fade" id="form_profesor" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
              <form role="form" name="form_profesores" id="form_profesores" method="post" enctype="multipart/form-data">
                <div class="row">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#personales" data-toggle="tab" aria-expanded="true">PERSONALES</a></li>
                    <li class=""><a href="#academicos" data-toggle="tab" aria-expanded="false">ACADEMICOS</a></li>
                    <li class=""><a href="#cate" data-toggle="tab" aria-expanded="false">DEDICACION / CAT</a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade active in" id="personales">
                      <div class="col-xs-6 col-md-4">
                        <div id="cedula_g" class="form-group input-group">
                          <label class="control-label" for="cedula_c">CEDULA</label>
                          <input id="cedula_c" name="cedula_c" type="text" class="form-control" {read} value="{CEDULA}">
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
                          <select name="pais_c" id="pais_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : pais_det -->
                            <option value="{CPAIS}" {selected}>{NOMBRE}</option>
                            <!-- END BLOCK : pais_det -->
                          </select>
                        </div>
                        <div id="estado_g" class="form-group input-group">
                          <label class="control-label" for="estado_c">ESTADO</label>
                          <select name="estado_c" id="estado_c" class="form-control">
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
                          <input id="fecha_nac_c" name="fecha_nac_c" type="text" class="form-control" value="{FECHA_NAC}">
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
                    	<div class="col-md-6">
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
                        <div id="prof_g" class="form-group input-group">
                          <label class="control-label" for="prof_c">PROFESION</label>
                          <select name="prof_c" id="prof_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : prof_det -->
                            <option value="{CPROFESION}" {selected}>{PROFESION}</option>
                            <!-- END BLOCK : prof_det -->
                          </select>
                        </div> 
                        <div id="f_ins_g" class="form-group input-group">
                          <label class="control-label" for="f_ins">FECHA DE REGISTRO</label>
                          <input id="f_ins" name="f_ins" type="text" class="form-control" readonly value="{FECHA_CREADO}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div id="obs_g" class="form-group input-group">
                          <label class="control-label" for="obs_c">OBSERVACIONES</label>
                          <textarea id="obs_c" name="obs_c" class="form-control" rows="2">{OBSERVACION}</textarea>
                        </div>                      
                      </div>
                    </div>
                    <div class="tab-pane fade" id="cate">
                      <div class="col-md-12">
                        <div id="ded_g" class="form-group input-group">
                          <label class="control-label" for="ded_c">DEDICACION</label>
                          <select name="ded_c" id="ded_c" class="form-control">
                             <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : prof_ded -->
                            <option value="{CDEDICACION}" {selected}>{DEDICACION}</option>
                            <!-- END BLOCK : prof_ded -->
                          </select>
                        </div>
                        <div id="cat_g" class="form-group input-group">
                          <label class="control-label" for="cat_c">CATEGORIA</label>
                          <select name="cat_c" id="cat_c" class="form-control">
                             <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : prof_cat -->
                            <option value="{CCATEGORIA}" {selected}>{CATEGORIA}</option>
                            <!-- END BLOCK : prof_cat -->
                          </select>
                        </div>
                        <div id="con_g" class="form-group input-group">
                          <label class="control-label" for="con_c">CONDICION LABORAL</label>
                          <select name="con_c" id="con_c" class="form-control">
                             <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : prof_con -->
                            <option value="{con_code}" {selected}>{con_name}</option>
                            <!-- END BLOCK : prof_con -->
                          </select>
                        </div>
                      </div>
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
                    <input type="hidden" id="cprofesor" name="cprofesor" class="form-control hidden" value="{id}">
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