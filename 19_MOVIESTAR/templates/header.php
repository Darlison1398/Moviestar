<?php
  
  require_once("globals.php");

  // banco de dados
  require_once("db.php");

  // Message
  require_once("models/Message.php");

  require("dao/UserDAO.php");

  // instanciando o Message aqui
  $message = new Message($BASE_URL);

  $flassMessage = $message->getMessage();

  if(!empty($flassMessage["msg"])) {
    // limpar a menssagem
    $message->clearMessage();
  }


  // instanciando o UserDAO
  $userDAO = new UserDAO($conn, $BASE_URL);

  // CHAMANDO O método que vai preencher os dados do usuário
  $userData = $userDAO->verifyToken(false);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moviestar</title>
    <link rel="short icon" href="<?= $BASE_URL ?>img/moviestar.ico" />

    <!-- Bootstrap.css  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.css" integrity="sha512-lp6wLpq/o3UVdgb9txVgXUTsvs0Fj1YfelAbza2Kl/aQHbNnfTYPMLiQRvy3i+3IigMby34mtcvcrh31U50nRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--  Font Awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS DO PROJETO -->  
    <link rel="stylesheet" href="<?= $BASE_URL ?>css/styles.css">



</head>
<body>

    <header>
        <!-- ATENÇÃO: GRANDE parte dos componentes e estilos adicionados aqui veio do bootstrap -->
        <nav id="main-navbar" class="navbar navbar-expand-lg">
            <a href="<?= $BASE_URL ?>" class="navbar-brand">
               <img src="<?= $BASE_URL ?>img/logo.svg" alt="Moviestar" id="logo">
               <span id="moviestar-title">Moviestar</span>
            </a>
            <!-- BOTÃO DO MENU HAMBÚRGUER -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Togglenavigattion">
                <i class="fas fa-bars"></i>
            </button>
            <form action="<?= $BASE_URL ?>search.php" method="GET" id="search-form" class="form-inline my-2 my-lg-0">
                <input type="text" name="q" id="search" class="form-control mr-sm-2" type="search" placeholder="Buscar filmes" aria-label="Search">
                <button class="btn my-2 my-sm-0" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav">

                    <?php if($userData): ?>

                        <li class="nav-item">
                            <a href="<?= $BASE_URL ?>newmovie.php" class="nav-link">
                               <i class="far fa-plus-square"></i>Incluir Filme
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= $BASE_URL ?>dashboard.php" class="nav-link">Meus filmes</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= $BASE_URL ?>editprofile.php" class="nav-link bold"> <?= $userData->name ?> </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= $BASE_URL ?>logout.php" class="nav-link">Sair</a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a href="<?= $BASE_URL ?>auth.php" class="nav-link">Entrar / Cadastrar</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </nav>
    </header>

    <?php if(!empty($flassMessage["msg"])): ?>
        <div class="msg-container">
           <p class="msg <?= $flassMessage["type"] ?>"> <?= $flassMessage["msg"] ?> </p>
        </div>
    <?php endif; ?>