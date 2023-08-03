<?php

  /// a session_start(), poderia estar aqui

  ### conectando com o bando de dados;

  $db_name = "moviestar";
  $db_host = "localhost";
  $db_user = "root";
  $db_pass = "Darlin13#5";

  $conn = new PDO("mysql:dbname=". $db_name . ";host=" . $db_host, $db_user, $db_pass);

  // habilitando erros PDO. Essas funções, nos mostra erros de conexão caso ocorra
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
