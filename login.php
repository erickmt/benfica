<!DOCTYPE html>

<html lang="pt"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Benfica - Autenticação</title>

    <link rel="shortcut icon" href="library/benfica.png" />
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="dist/css/signin.css" rel="stylesheet">
    
  </head>

  <body> <!-- cz-shortcut-listen="true">-->
    <div class="container">

      <form class="form-signin" id="login" method="post">
        <h2 class="form-signin-heading"><center>Sistema de Gerenciamento e Vendas Benfica</center></h2>
        <label  class="sr-only">Login:</label>
        <input type="text" id="login" name="login" class="form-control" maxlength="32" placeholder="Digite seu usuário" required="" autofocus="">
        <label for="inputPassword" class="sr-only">Senha:</label>
        <input type="password" id="senha" name="senha" class="form-control" maxlength="32" placeholder="Digite sua senha" required="">
        <button type="submit" class="btn btn-lg btn-primary btn-block">Entrar</button>
        <br>
        <div class="alert alert-danger" id="erroLogin" style="display: none; margin: 0%;" ></div>
      </form>


    </div>
    <!-- Inclusão JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/metisMenu/metisMenu.min.js"></script>
    <script src="vendor/raphael/raphael.min.js"></script>
    <script src="vendor/morrisjs/morris.min.js"></script>
    <script src="data/morris-data.js"></script>
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="js/funcoes.js"></script>
    
  </body>
  
</html>