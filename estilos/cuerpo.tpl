<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SistemaControl</title>
    <!-- Bootstrap Core CSS -->
    <link href="./css/bootstrap.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="./css/metisMenu.min.css" rel="stylesheet">
    <!-- Datepicker -->
    <link href="./css/bootstrap-datepicker.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="./css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="./css/dataTables.responsive.css" rel="stylesheet">
     <!-- JQuery UI
    <link href="./css/jquery-ui.css" rel="stylesheet">-->
    <!-- Custom CSS -->
    <link href="./css/theme.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="./css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link type="image/x-icon" href="img/{icono}" rel="icon">
    <link type="image/x-icon" href="img/{icono}" rel="shortcut icon">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="./js/html5shiv.js"></script>
        <script src="./js/respond.min.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
        <div class="alert alert-danger">Este sistema no esta diseñado para Trabajar en este navegador, por favor contacte con el Departamento de AIT</div>
    <![endif]-->
    <!-- jQuery -->
    <script src="./js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="./js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="./js/metisMenu.min.js"></script>
    <!-- Datepicker -->
    <script src="./js/bootstrap-datepicker.js"></script>
    <script src="./js/bootstrap-datepicker.es.min.js"></script>
    <!-- DataTables -->
    <script src="./js/jquery.dataTables.js"></script>
    <script src="./js/dataTables.bootstrap.js"></script>
    <script src="./js/dataTables.responsive.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="./js/sb-admin-2.js"></script>
    <!-- LiveQuery -->
    <script src="./js/livequery.js"></script>
    <!-- JSONQuery -->
    <script src="./js/jquery.json.js"></script>
     <!-- Funciones -->
    <script src="./js/funciones.js"></script>
    <!-- START BLOCK : validar -->
    <script>
    alert('No tiene permiso para acceder a este modulo');
    document.location.href = "./";
    </script>
    <!-- END BLOCK : validar -->
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-brand" href="./">SistemaControl</a>
                <span class="navbar-brand" href="./">{institucion}</span>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- INCLUDE BLOCK : menu_user -->
            <!-- INCLUDE BLOCK : menu -->
        </nav>
        <!-- INCLUDE BLOCK : contenido -->
    </div>
</body>
</html>