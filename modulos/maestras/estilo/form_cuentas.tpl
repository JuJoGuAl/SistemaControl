<script>
$(document).ready(function(){
  var button = '', objeto='';
  $(document).on('click', '.btn', function(){
      button = $(this).attr('id');
      clear_log();
      switch (button){
        case 'bt_save':
          if(verificar()){
            $('#form').submit();
          }
        break;
        default:
          objeto='undefined';
      }
    });
    function verificar(){
      var cuenta='cuenta',tcuenta='tcuenta',tbanco='banco',valido=true;
      valido = valido && validar(cuenta);
      valido = valido && list(tcuenta);
      valido = valido && list(tbanco);
      return valido;
    }
    $(function(){
      $('#form').on('submit', function(e){
        e.preventDefault();
        $.post('./modulos/maestras/modal.php',$('#form').serialize(),function(data, status, xhr){
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
<form role="form" name="form" id="form" method="post" enctype="multipart/form-data">
<div class="modal modals fade" id="form_cuentas" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
                      <div class="col-xs-6 col-md-6">
                        <div id="cuenta_g" class="form-group input-group">
                          <label class="control-label" for="cuenta_c">CUENTA (NUMERO)</label>
                          <input id="cuenta_c" name="cuenta_c" type="text" class="form-control" value="{CUENTA}">
                        </div>
                        <div id="tcuenta_g" class="form-group input-group">
                          <label class="control-label" for="tcuenta_c">TIPO DE CUENTA</label>
                          <select name="tcuenta_c" id="tcuenta_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : cuenta_det -->
                            <option value="{code}" {selected}>{tipo}</option>
                            <!-- END BLOCK : cuenta_det -->
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-6">
                        <div id="banco_g" class="form-group input-group">
                          <label class="control-label" for="banco_c">BANCO</label>
                          <select name="banco_c" id="banco_c" class="form-control pais">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : bancos_det -->
                            <option value="{CODIGO}" {selected}>{BANCO}</option>
                            <!-- END BLOCK : bancos_det -->
                          </select>
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
                    <input type="hidden" id="code" name="code" class="form-control hidden" value="{code}">
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