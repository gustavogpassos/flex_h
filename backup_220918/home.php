<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 17/01/2018
 * Time: 14:23
 */

session_start();

//verifica se existe sessão ativa
if(isset($_SESSION) && $_SESSION['logado']==false){
    header('Location: index.php?logout');
}

//Carrega controlador principal
require_once "lib/mainController.php";

//print_r($_SESSION);
?>

<html>
<head>
    <title>Home - Sistema Flex</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheet files -->
    <link href="lib/sources/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="lib/sources/icons/open-iconic-master/font/css/open-iconic-bootstrap.css" type="text/css"
          rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="lib/sources/datatables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="lib/sources/css/app.css"/>

    <!-- Javascript files -->
    <script src="lib/sources/js/jquery.min.js"></script>
    <script src="lib/sources/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="lib/sources/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="lib/sources/js/jquery.maskMoney.min.js"></script>
    <script type="text/javascript" src="lib/sources/js/app.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between">
    <div class="container">
        <a class="navbar-brand" href="#">Sistema Flex</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-item nav-link" href="home.php">Solicitações</a>
                </li>
                <?php if($_SESSION['tpuser']=='loja'){ ?>
                <li class="nav-item">
                    <!-- <a class="nav-item nav-link" href="home.php?action=novo">Nova Solicitação</a>-->
                    <a class="nav-item btn btn-success" href="home.php?action=novo">Nova Solicitação</a>
                </li>
                <?php } ?>
            </ul>
            <div class=" navbar-nav">
                <a class="nav-link nav-item"><?php echo $_SESSION['usuario']; ?></a>
                <a href="index.php?logout" class="nav-item nav-link"><span class="oi oi-power-standby"></span> &nbsp; Sair</a>
            </div>
        </div>


    </div>
</nav>
<br/>
<div class="container">

    <div class="row">
        <div class="col-md-12 jumbotron">
            <?php new MainController(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#flexlist').DataTable({
            "lengthChange": false,
            "order": [[ 0, 'desc' ]],
            "language": {
                "paginate": {
                    "next": "Próximo",
                    "previous": "Anterior"
                },
                "search": "Buscar",
                "infoEmpty": "",
                "zeroRecords": "Não há dados correspondentes",
                "lengthMenu": "",
                "infoFiltered": "",
                "info": ""
            }
        });
    })
</script>
</body>
</html>

