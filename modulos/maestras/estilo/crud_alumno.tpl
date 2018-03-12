<script>
$(document).ready(function(){
  var ref,url='undefined';
  $('.datatables').DataTable({
    responsive: true
  });
  $('.tooltip-alumnos').tooltip({
    selector: "[data-toggle=tooltip]",
    container: "body"
  });
  $(document).on("hidden.bs.modal", "#"+ref, function () {
    $('#'+ref).remove(); // Remove from DOM. NO ESTA FUNCIONANDO
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
    url='./modal.php?mod=MAESTRAS&submod=CRUD_ALUMNO&pag=form_alumno&accion='+action+'&id='+id;
    $('.modal-container').load(url,function(result){
      $('#'+ref).modal({show:true});
    });
  });
});
</script> 
<!-- START BLOCK : crud_alumno -->
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row"><div class="col-lg-12"><h2 class="page-header">{mod_name}</h2></div></div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading titulares">
            <a id="bt_new" class="btn btn-default modal-btn" data-action="" href="form_alumno" data-toggle="modal"><i class="fa fa-plus"></i> NUEVO</a>
          </div>
          <div class="panel-body">
          <div class="modal-container"></div>
            <table width="100%" class="table table-striped table-bordered table-hover datatables" id="alumnos">
              <thead>
                <tr>
                  <th>CEDULA</th>
                  <th>NOMBRES</th>
                  <th>APELLIDOS</th>
                  <th>F-NAC</th>
                  <th style="width: 50px">SEXO</th>
                  <th>STATUS</th>
                  <th>ACC</th>
                </tr>
              </thead>
              <tbody>
              <!-- START BLOCK : alumnos_data -->
                <tr class="{class}">
                  <td>{CEDULA}</td>
                  <td>{NOMBRES}</td>
                  <td>{APELLIDOS}</td>
                  <td>{FECHA_NAC}</td>
                  <td style="text-align: center">{sex}</td>
                  <td>{STATUS_NAME}</td>
                  <td>{actions}</td>
                </tr>
                <!-- END BLOCK : alumnos_data -->
              </tbody>
            </table>
          </div>
        </div>      
      </div>
    </div>
  </div>
</div>
<!-- END BLOCK : crud_alumno -->