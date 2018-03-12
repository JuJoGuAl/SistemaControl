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
    url='./modal.php?mod=MAESTRAS&submod=CRUD_TURNOS&pag=form_turnos&accion='+action+'&id='+id;
    $('.modal-container').load(url,function(result){
      $('#'+ref).modal({show:true});
    });
  });
});
</script> 
<!-- START BLOCK : crud_turnos -->
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row"><div class="col-lg-12"><h2 class="page-header">{mod_name}</h2></div></div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading titulares">
            <a id="bt_new" class="btn btn-default modal-btn" data-action="" href="form_turnos" data-toggle="modal"><i class="fa fa-plus"></i> NUEVO</a>
          </div>
          <div class="panel-body">
          <div class="modal-container"></div>
            <table width="100%" class="table table-striped table-bordered table-hover datatables" id="alumnos">
              <thead>
                <tr>
                  <th>CODIGO</th>
                  <th>TURNO</th>
                  <th>ESTATUS</th>
                  <th>ACCIONES</th>
                </tr>
              </thead>
              <tbody>
              <!-- START BLOCK : turnos_data -->
                <tr class="{class}">
                  <td>{CTURNO}</td>
                  <td>{TURNO}</td>
                  <td>{STATUS}</td>
                  <td>{actions}</td>
                </tr>
                <!-- END BLOCK : turnos_data -->
              </tbody>
            </table>
          </div>
        </div>      
      </div>
    </div>
  </div>
</div>
<!-- END BLOCK : crud_turnos -->