<script>
$(document).ready(function(){
  var button = '', objeto='';
  if($('#accion').val()=='save_edt'){
    if($('#ver').val()=='1'){
      $('#ver').prop('checked',true);
    }else{
      $('#ver').prop('checked',false);
    }
  }
  $('#ver').change(function(){
    if ($(this).prop('checked')){
      $('#ver').val(1);
    }else{
      $('#ver').val(0);
    }
  });
  $(document).on('click', '.btn', function(){
      button = $(this).attr('id');
      clear_log();
      switch (button){
        case 'bt_save':
          if(verificar()){
            $('#materias_form').submit();
          }
        break;
        case 'bt_carreras':
          objeto='Carreras';
        break;
        default:
          objeto='undefined';
      }
    });
    function verificar(){
      var code='codigo',desc='descripcion',valido=true;
      valido = valido && validar(code);
      valido = valido && validar(desc);
      return valido;
    }
    $(function(){
      $('#materias_form').on('submit', function(e){
        e.preventDefault();
        $.post('./modulos/maestras/modal.php',$('#materias_form').serialize(),function(data, status, xhr){
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
  });
});
</script>
<form role="form" name="materias_form" id="materias_form" method="post" enctype="multipart/form-data">
<div class="modal modals fade" id="form_materias" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
                <div class="row">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#principales" data-toggle="tab" aria-expanded="true">PRINCIPALES</a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade active in" id="principales">
                      <div class="col-xs-6 col-md-4">
                        <div id="codigo_g" class="form-group input-group">
                          <label class="control-label" for="codigo_c">CODIGO</label>
                          <input id="codigo_c" name="codigo_c" type="text" class="form-control" value="{CODIGO}">
                        </div>
                        <div id="descripcion_g" class="form-group input-group">
                          <label class="control-label" for="descripcion_c">DESCRIPCION</label>
                          <input id="descripcion_c" name="descripcion_c" type="text" class="form-control" value="{DESCRIPCION}">
                        </div>
                        <div id="ht_g" class="form-group input-group">
                          <label class="control-label" for="ht_c">HORAS TEORICAS</label>
                          <input id="ht_c" name="ht_c" type="text" class="form-control numeric" value="{HT}">
                        </div>
                        <div id="hp_g" class="form-group input-group">
                          <label class="control-label" for="hp_c">HORAS PRACTICAS</label>
                          <input id="hp_c" name="hp_c" type="text" class="form-control numeric" value="{HP}">
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-4">
                        <div id="cm1_g" class="form-group input-group">
                          <label class="control-label" for="cm1_c">CUPO MAXIMO 1</label>
                          <input id="cm1_c" name="cm1_c" type="text" class="form-control numeric" value="{CUPO1}">
                        </div>
                        <div id="cm2_g" class="form-group input-group">
                          <label class="control-label" for="cm2_c">CUPO MAXIMO 2</label>
                          <input id="cm2_c" name="cm2_c" type="text" class="form-control numeric" value="{CUPO2}">
                        </div>
                        <div id="uc_g" class="form-group input-group">
                          <label class="control-label" for="uc_c">UNIDAD DE CREDITO</label>
                          <input id="uc_c" name="uc_c" type="text" class="form-control numeric" value="{UC}">
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-4">
                        <div class="form-group input-group">
                          <label class="checkbox-inline" for="ver">
                            <input id="ver" name="ver" type="checkbox" value="{VER}">¿VERANO?
                          </label>
                        </div>
                        <!-- START BLOCK : st_block -->
                        <div id="status_g" class="form-group input-group">
                          <label class="control-label" for="status_c">STATUS</label>
                          <select name="status_c" id="status_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : st_det -->
                            <option value="{st_code}" {selected}>{st_name}</option>
                            <!-- END BLOCK : st_det -->
                          </select>
                        </div>
                        <!-- END BLOCK : st_block -->
                        <div id="notas_g" class="form-group input-group">
                          <label class="control-label" for="notas_c">NOTAS</label>
                          <textarea id="notas_c" name="notas_c" class="form-control" rows="2">{NOTAS}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>                
                </div>
                <div class="row">
                  <div class="col-lg-12"><p></p><div id="log" class="log alert">{mensaje}</div></div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <p style="text-align:center;">
                      <input type="hidden" id="accion" name="accion" class="form-control hidden" value="{accion}">
                      <input type="hidden" id="obj" name="obj" class="form-control hidden" value="{obj}">
                    <input type="hidden" id="cmateria" name="cmateria" class="form-control hidden" value="{id}">
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="Modal_Carreras" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="ModalLabel">Carreras</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading" id="Modal_Text">Filtre según el criterio ingresándolo en el recuadro de <strong>Filtrar</strong></div>
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
      <!-- <div class="modal-footer">
        <button type="button" id="Modal_Close" class="btn btn-default Modal_Close" data-dismiss="modal">CERRAR</button>
        <button type="button" id="Modal_Save" class="btn btn-primary Modal_Save" data-dismiss="modal">ACEPTAR</button>
      </div> -->
    </div>
  </div>
</div>
</form>