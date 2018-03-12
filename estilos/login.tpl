<script>
$(document).ready(function(){
    $("#login").submit(function(){
        //$("#img_ajax").fadeIn('fast');
        var username = $('#user').val(), pass = $('#password').val();
        if (username==''){
            mensaje("DEBE INGRESAR EL USUARIO","ERROR");
        }else if(pass==''){
            mensaje("DEBE INGRESAR LA CLAVE","ERROR");
        }
        else{
            $.ajax({
                type: "POST",
                url: "./modulos/usuarios/index.php",
                data: "username=" + username + "&pass=" + pass + "&action=val_log",
                success: function(msj){
                    //alert(msj);
                    if (msj==1){
                        document.location.reload();
                    }else if(msj==2){
                        mensaje("USUARIO INVALIDO","ERROR");
                    }else if(msj==3){
                        mensaje("CLAVE INVALIDA","ERROR");
                    }else if(msj==4){
                        mensaje("USUARIO SIN PERMISO","ERROR");
                    }else if(msj==5){
                        mensaje("USUARIO INACTIVO","ERROR");
                    }else {
                        mensaje(msj,"ERROR");
                    }
                },
                error: function(x,err,msj){
                    if(x.status==0){
                    alert('You are offline!!\n Please Check Your Network.');
                    }else if(x.status==404){
                    alert('Requested URL not found.');
                    }else if(x.status==500){
                    alert('Internel Server Error.');
                    }else if(e=='parsererror'){
                    alert('Error.\nParsing JSON Request failed.');
                    }else if(e=='timeout'){
                    alert('Request Time out.');
                    }else {
                    alert('Unknow Error.\n'+x.responseText);
                    }
                }
            });
        }
        return false;
    });
});
</script>
<div id="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <!-- <h3 class="panel-title">Inicie Sección</h3> -->
                        <img width="35%" src="./img/{logo}" alt="">
                    </div>
                    <div class="panel-body">
                        <form role="form" name="login" id="login">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Usuario" id="user" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Clave" id="password" type="password" value="">
                                </div>
                                <input value="Entrar" type="submit" class="btn btn-lg btn-success btn-block"/>
                            </fieldset>
                            <p></p>
                            <div id="log" class="log alert alert-danger"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>