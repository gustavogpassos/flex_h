	<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 26/12/2017
 * Time: 16:47
 */

    session_start();

if (isset($_GET['logout'])) {
    session_destroy();
}



if(isset($_SESSION['logado']) && $_SESSION['logado']==true){
    header("Location: home.php");
}

require 'controller/adminController.php';

$admin = new AdminController();

if (isset($_POST['usuario']) && isset($_POST['senha'])) {
    $admin->login($_POST['usuario'], $_POST['senha']);

    if ($_SESSION['logado'] == false) {
        session_destroy();
        echo '<script>window.alert("Usuario ou senha incorretos, tente novamente.");</script>';
    } else {
        header("Location: home.php");
    }
}
?>

<html>
<head>
    <title>Login - Sistema Flex</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="lib/sources/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="lib/sources/icons/open-iconic-master/font/css/open-iconic-bootstrap.css" type="text/css"
          rel="stylesheet">
    <script src="lib/sources/js/jquery.min.js"></script>
    <script src="lib/sources/js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body><br/>
<div class="container">
    <div class="col-md-4" style="margin: auto">
        <form class="form-signin" action="index.php" method="post">
            <h2>Login - Sistema Flex</h2>
            <hr/>
            <label for="inputEmail" class="sr-only">Usuário</label>
            <input type="text" name="usuario" id="inputEmail" class="form-control" placeholder="Usuário" required
                   autofocus><br/>
            <label for="inputPassword" class="sr-only">Senha</label>
            <input type="password" name="senha" id="inputPassword" class="form-control" placeholder="Senha" required>
            <hr/>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
        </form>
        <span class="badge badge-light">V 4.1.1</span>
    </div>
</div> <!-- /container -->
</body>
</html>
