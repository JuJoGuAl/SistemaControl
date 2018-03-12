<script>
$(document).ready(function(){
  var button = '', objeto='';
  $('.fecha').datepicker({
      format: "dd/mm/yyyy",
      autoclose: true,
      todayHighlight: true,
      language: "es"
    });
  $(document).on('click', '.btn', function(){
      button = $(this).attr('id');
      clear_log();
      switch (button){
        case 'bt_save':
          if(verificar()){
            $('#periodos_form').submit();
          }
        break;
        default:
          objeto='undefined';
      }
    });
    function verificar(){
      var per='per',tper='tper',fi='f_ini',ff='f_fin',fg='f_gra',pro='pro',fir='f_inir',ffr='f_finr',fii='f_inii',ffi='f_fini', mins='mins', msem='msem', valido=true;
      valido = valido && validar(per);
      valido = valido && list(tper);
      valido = valido && validar(fi);
      valido = valido && validar(ff);
      valido = valido && validar(fg);
      //valido = valido && validar(pro);//PROMOCION NO ES OBLIGADA
      valido = valido && validar(mins);
      valido = valido && IsNumber(mins);
      valido = valido && validar(msem);
      valido = valido && IsNumber(msem);
      valido = valido && validar(fir);
      valido = valido && validar(ffr);
      valido = valido && validar(fii);
      valido = valido && validar(ffi);
      return valido;
    }
    $(function(){
      $('#periodos_form').on('submit', function(e){
        e.preventDefault();
        $.post('./modulos/maestras/modal.php',$('#periodos_form').serialize(),function(data, status, xhr){
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
<div class="modal modals fade" id="form_periodos" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
              <form role="form" name="periodos_form" id="periodos_form" method="post" enctype="multipart/form-data">
                <div class="row">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#principales" data-toggle="tab" aria-expanded="true">PRINCIPALES</a></li>
                    <li><a href="#inscripciones" data-toggle="tab" aria-expanded="false">INSCRIPCIONES</a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade active in" id="principales">
                      <div class="col-xs-6 col-md-4">
                        <div id="per_g" class="form-group input-group">
                          <label class="control-label" for="per_c">PERIODO</label>
                          <input id="per_c" name="per_c" type="text" class="form-control numeric" value="{F_PER}" maxlength="4">
                        </div>
                        <div id="tper_g" class="form-group input-group">
                          <label class="control-label" for="tper_c">TIPO PERIODO</label>
                          <select name="tper_c" id="tper_c" class="form-control">
                            <option value="-1">SELECCIONE...</option>
                            <!-- START BLOCK : per_array -->
                            <option value="{per_code}" {selected}>{per_name}</option>
                            <!-- END BLOCK : per_array -->
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-4">
                        <div id="f_ini_g" class="form-group input-group">
                          <label class="control-label" for="f_ini_c">FECHA INICIO</label>
                          <input id="f_ini_c" name="f_ini_c" type="text" class="form-control fecha" value="{FECHA_INI}">
                        </div>
                        <div id="f_fin_g" class="form-group input-group">
                          <label class="control-label" for="f_fin_c">FECHA FIN</label>
                          <input id="f_fin_c" name="f_fin_c" type="text" class="form-control fecha" value="{FECHA_FIN}">
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-4">
                        <div id="f_gra_g" class="form-group input-group">
                          <label class="control-label" for="f_gra_c">FECHA GRADO</label>
                          <input id="f_gra_c" name="f_gra_c" type="text" class="form-control fecha" value="{FECHA_GRAD}">
                        </div>
                        <div id="pro_g" class="form-group input-group">
                          <label class="control-label" for="pro_c">PROMOCION</label>
                          <input id="pro_c" name="pro_c" type="text" class="form-control" value="{PROMO}" maxlength="4">
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-6">
                        <div id="mins_g" class="form-group input-group">
                          <label class="control-label" for="mins_c">MONTO INSCRIPCION</label>
                          <input id="mins_c" name="mins_c" type="text" class="form-control numeric" value="{MINSCRIPCION}">
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-6">
                        <div id="msem_g" class="form-group input-group">
                          <label class="control-label" for="msem_c">MONTO SEMESTRE</label>
                          <input id="msem_c" name="msem_c" type="text" class="form-control numeric" value="{MSEMESTRE}">
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-6">
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
                    <div class="tab-pane fade" id="inscripciones">
                      <div class="col-xs-6 col-md-4">
                        <div id="f_inir_g" class="form-group input-group">
                          <label class="control-label" for="f_inir_c">INICIO INSCRIPCION REGULARES</label>
                          <input id="f_inir_c" name="f_inir_c" type="text" class="form-control fecha" value="{FECHA_INIR}">
                        </div>
                        <div id="f_finr_g" class="form-group input-group">
                          <label class="control-label" for="f_finr_c">FIN INSCRIPCION REGULARES</label>
                          <input id="f_finr_c" name="f_finr_c" type="text" class="form-control fecha" value="{FECHA_FINR}">
                        </div>
                      </div>
                      <div class="col-xs-6 col-md-4">
                        <div id="f_inii_g" class="form-group input-group">
                          <label class="control-label" for="f_inii_c">INICIO INSCRIPCION IRREGULARES</label>
                          <input id="f_inii_c" name="f_inii_c" type="text" class="form-control fecha" value="{FECHA_INII}">
                        </div>
                        <div id="f_fini_g" class="form-group input-group">
                          <label class="control-label" for="f_fini_c">FIN INSCRIPCION IRREGULARES</label>
                          <input id="f_fini_c" name="f_fini_c" type="text" class="form-control fecha" value="{FECHA_FINI}">
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
                    <input type="hidden" id="cperiodo" name="cperiodo" class="form-control hidden" value="{id}">
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