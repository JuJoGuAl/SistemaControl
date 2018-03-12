<script>
$(document).ready(function(){
  var button = '', objeto='';
  $(document).on('click', '.btn', function(){
      button = $(this).attr('id');
      clear_log();
      switch (button){
        case 'bt_save':
          if(verificar()){
            $('#secciones_form').submit();
          }
        break;
        default:
          objeto='undefined';
      }
    });
    function verificar(){
      var sec='seccion',cup='cup',sem='sem',car='carreras',valido=true;
      valido = valido && validar(sec);
      valido = valido && list(car);
      valido = valido && validar(sem);
      valido = valido && IsNumber(sem);
      valido = valido && validar(cup);
      valido = valido && IsNumber(cup);
      return valido;
    }
    $('#seccion_c').blur(function(){
      var sec='seccion';
      if (validar(sec)){
         check_data('seccion','sec',$('#cseccion').val());
      }
    });
    $(function(){
      $('#secciones_form').on('submit', function(e){
        e.preventDefault();
        $.post('./modulos/maestras/modal.php',$('#secciones_form').serialize(),function(data, status, xhr){
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
<form role="form" name="secciones_form" id="secciones_form" method="post" enctype="multipart/form-data">
<div class="modal modals fade" id="form_secciones" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
                      <div class="col-xs-8 col-md-6">
                        <div id="seccion_g" class="form-group input-group">
                          <label class="control-label" for="seccion_c">SECCION</label>
                          <input id="seccion_c" name="seccion_c" type="text" class="form-control" value="{SECCION}">
                        </div>
                        <div id="carreras_g" class="form-group input-group">
                          <label class="control-label" for="carreras_c">CARRERAS DISPONIBLES</label>
                          <select name="carreras_c" id="carreras_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : carreras_det -->
                            <option value="{CCARRERA}" {selected}>{DESCRIPCION}</option>
                            <!-- END BLOCK : carreras_det -->
                          </select>
                        </div>
                        <div id="sem_g" class="form-group input-group">
                          <label class="control-label" for="sem_c">SEMETRE</label>
                          <input id="sem_c" name="sem_c" type="text" class="form-control numeric" value="{SEMESTRE}">
                        </div>
                        <div id="cup_g" class="form-group input-group">
                          <label class="control-label" for="cup_c">CUPOS</label>
                          <input id="cup_c" name="cup_c" type="text" class="form-control numeric" value="{CUPOS}">
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
                    <input type="hidden" id="cseccion" name="cseccion" class="form-control hidden" value="{id}">
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
</form>