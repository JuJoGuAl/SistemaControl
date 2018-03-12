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
      var nombre='nombre', user='user', pass1='pass1', valido=true;
      valido = valido && validar(nombre);
      valido = valido && validar(user);
      <!-- START BLOCK : val_clave -->
      valido = valido && validar(pass1);
      <!-- END BLOCK : val_clave -->
      return valido;
    }
    $(function(){
      $('#form').on('submit', function(e){
        e.preventDefault();
        $.post('./modulos/usuarios/form_usuarios.php',$('#form').serialize(),function(data, status, xhr){
          if(data.titulo=="ERROR"){
            mensaje(data.texto,"ERROR");
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
<div class="modal modals fade" id="form_usuarios" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
                    <li class=""><a href="#permisos" data-toggle="tab" aria-expanded="false">PERMISOS</a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade active in" id="principales">
                      <div class="col-md-6">
                        <div id="nombre_g" class="form-group input-group">
                          <label class="control-label" for="nombre_c">NOMBRE</label>
                          <input id="nombre_c" name="nombre_c" type="text" class="form-control" value="{EMPLEADO}">
                        </div>
                        <!-- START BLOCK : st_block -->
                        <div id="status_g" class="form-group input-group">
                          <label class="control-label" for="status_c">STATUS</label>
                          <select name="status_c" id="status_c" class="form-control">
                            <!-- START BLOCK : st_det -->
                            <option value="{st_code}" {selected}>{st_name}</option>
                            <!-- END BLOCK : st_det -->
                          </select>
                        </div>
                        <!-- END BLOCK : st_block -->
                      </div>
                      <div class="col-md-6">
                        <div id="user_g" class="form-group input-group">
                          <label class="control-label" for="user_c">USUARIO</label>
                          <input id="user_c" name="user_c" type="text" class="form-control" value="{CUSUARIO}" {read}>
                        </div>
                        <div id="pass1_g" class="form-group input-group">
                          <label class="control-label" for="pass1_c">CONTRASEÑA</label>
                          <input id="pass1_c" name="pass1_c" type="password" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="permisos">
                      <div class="col-xs-12 col-md-8">
                        <div id="permisos_g" class="form-group input-group">
                          <label class="control-label" for="permisos_c">MODULOS</label>
                          <select id="permisos_c" name="permisos_c[]" multiple="multiple" class="form-control" style="height:200px;">
                            <!-- START BLOCK : perm -->
                            <option value="{CMODULO}" {selected}>{MENU} - {MODULO}</option>
                            <!-- END BLOCK : perm -->
                          </select>
                          <p class="help-block">Permisos del Usuario, Para seleccionar varias opciones utilice la tecla CTRL</p>
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