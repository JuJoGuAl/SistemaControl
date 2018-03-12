<script>
$(document).ready(function() {
    $(".cerrar-sesion").click(function(){
        $.ajax({
            type: "POST",
            url: "modulos/usuarios/index.php",
            data: "action=logout",
            success: function(msj){
                document.location.href = "./";
            }
        });
    });
});
</script>
<ul class="nav navbar-top-links navbar-right">
    <li class="dropdown">
        <a class="dropdown-toggle usuario" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-fw"></i> {user} <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
            <!-- <li><a href="#"><i class="fa fa-user fa-fw"></i> Cuenta</a></li> -->
            <!-- <li><a href="#"><i class="fa fa-lock fa-fw"></i> Cambiar clave</a></li> -->
            <li class="divider"></li>
            <li><a href="#" class="cerrar-sesion"><i class="fa fa-sign-out fa-fw"></i> Cerrar Sesión</a></li>
        </ul>
    </li>
</ul>