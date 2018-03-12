<script>
$(document).ready(function(){
  var ref,url='undefined';
  $('.datatables').DataTable({
    responsive: true
  });
  $('.tooltip-acciones').tooltip({
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
    url='./modal.php?mod=MAESTRAS&submod=CRUD_BANCOS&pag='+ref+'&accion='+action+'&id='+id;
    $('.modal-container').load(url,function(result){
      $('#'+ref).modal({show:true});
    });
  });
});
</script> 
<!-- START BLOCK : CRUD -->
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row"><div class="col-lg-12"><h2 class="page-header">{mod_name}</h2></div></div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading titulares">
            <a id="bt_new" class="btn btn-default modal-btn" data-action="" href="form_bancos" data-toggle="modal"><i class="fa fa-plus"></i> NUEVO</a>
          </div>
          <div class="panel-body">
          <div class="modal-container"></div>
            <table width="100%" class="table table-striped table-bordered table-hover datatables" id="tables_">
              <thead>
                <tr>
                  <th>CODIGO</th>
                  <th>BANCO</th>
                  <th>ESTATUS</th>
                  <th>ACCIONES</th>
                </tr>
              </thead>
              <tbody>
              <!-- START BLOCK : DATA -->
                <tr class="{class}">
                  <td>{CODIGO}</td>
                  <td>{BANCO}</td>
                  <td>{STATUS}</td>
                  <td>{actions}</td>
                </tr>
                <!-- END BLOCK : DATA -->
              </tbody>
            </table>
          </div>
        </div>      
      </div>
    </div>
  </div>
</div>
<!-- END BLOCK : CRUD -->