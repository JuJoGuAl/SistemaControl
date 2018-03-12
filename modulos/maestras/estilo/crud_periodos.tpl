<script>
$(document).ready(function(){
  var ref,url='undefined';
  $('.datatables').DataTable({
    responsive: true
  });
  $('.tooltip-tool').tooltip({
    selector: "[data-toggle=tooltip]",
    container: "body"
  });
  $('.modals').livequery(function(){
    $(this).on("hidden.bs.modal", function (){
      $(this).remove();
      document.location.reload();
    });
  });
  $(document).on('click', '.modal-btn', function(e){
    id = ($(this).attr('data-id'));
    action = ($(this).attr('data-action'));
    button = $(this).attr('id');
    ref = $(this).attr('href');
    url='./modal.php?mod=MAESTRAS&submod=CRUD_PERIODOS&pag=form_periodos&accion='+action+'&id='+id;
    $('.modal-container').load(url,function(result){
      $('#'+ref).modal({show:true});
    });
  });
});
</script> 
<!-- START BLOCK : crud_periodos -->
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row"><div class="col-lg-12"><h2 class="page-header">{mod_name}</h2></div></div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading titulares">
            <a id="bt_new" class="btn btn-default modal-btn" data-action="" href="form_periodos" data-toggle="modal"><i class="fa fa-plus"></i> NUEVO</a>
          </div>
          <div class="panel-body">
          <div class="modal-container"></div>
            <table width="100%" class="table table-striped table-bordered table-hover datatables" id="alumnos">
              <thead>
                <tr>
                  <th>PERIODO</th>
                  <th>FECHA INICIO</th>
                  <th>FECHA FIN</th>
                  <th>INSCRIPCION</th>
                  <th>SEMESTRE</th>
                  <th>ACCIONES</th>
                </tr>
              </thead>
              <tbody>
              <!-- START BLOCK : periodos_data -->
                <tr class="{class}">
                  <td>{PERIODO}</td>
                  <td>{FECHA_INI}</td>
                  <td>{FECHA_FIN}</td>
                  <td>{MINSCRIPCION}</td>
                  <td>{MSEMESTRE}</td>
                  <td>{actions}</td>
                </tr>
                <!-- END BLOCK : periodos_data -->
              </tbody>
            </table>
          </div>
        </div>      
      </div>
    </div>
  </div>
</div>
<!-- END BLOCK : crud_periodos -->