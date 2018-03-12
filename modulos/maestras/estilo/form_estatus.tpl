<script>
$(document).ready(function(){
  var button = '', objeto='';
  $(document).on('click', '.btn', function(){
      button = $(this).attr('id');
      clear_log();
      switch (button){
        case 'bt_save':
          if(verificar()){
            $('#estatus_form').submit();
          }
        break;
        default:
          objeto='undefined';
      }
    });
    function verificar(){
      var desc='descripcion', clase='clase',valido=true;
      valido = valido && validar(desc);
      valido = valido && list(clase);
      return valido;
    }
    $(function(){
      $('#estatus_form').on('submit', function(e){
        e.preventDefault();
        $.post('./modulos/maestras/modal.php',$('#estatus_form').serialize(),function(data, status, xhr){
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
<div class="modal modals fade" id="form_estatus" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
              <form role="form" name="estatus_form" id="estatus_form" method="post" enctype="multipart/form-data">
                <div class="row">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#principales" data-toggle="tab" aria-expanded="true">PRINCIPALES</a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade active in" id="principales">
                      <div class="col-xs-6 col-md-4">
                        <div id="descripcion_g" class="form-group input-group">
                          <label class="control-label" for="descripcion_c">DESCRIPCION</label>
                          <input id="descripcion_c" name="descripcion_c" type="text" class="form-control" value="{NOMBRE}">
                        </div>
                        <div id="clase_g" class="form-group input-group">
                          <label class="control-label" for="clase_c">CLASIFICACION</label>
                          <select name="clase_c" id="clase_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : clase_det -->
                            <option value="{clase_cod}" {selected}>{clase_name}</option>
                            <!-- END BLOCK : clase_det -->
                          </select>
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
                    <input type="hidden" id="cstatus" name="cstatus" class="form-control hidden" value="{id}">
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