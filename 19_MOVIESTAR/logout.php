<?php
  

  require_once("templates/header.php");
  include_once("dao/UserDAO.php");

  $userDao = new UserDao($conn, $BASE_URL);

  // O userDao verifica se o usuário está logado

  if($userDao){
    $userDao->destroyToken();
  }