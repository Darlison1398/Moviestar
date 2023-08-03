<?php
  
  require_once("globals.php");
  require_once("db.php");
  require_once("models/User.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");

  // instanciamos o Message aqui
  $message = new Message($BASE_URL);

  $userDao = new UserDao($conn, $BASE_URL);

  // resgata o tipo do formulário
  $type = filter_input(INPUT_POST, "type");
  
  // verifica o tipo de formulário
  if($type === "register"){

    // todos os dados que vem do post / formulário
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    //verificação de dados minimos
    if($name && $lastname && $email && $password){
        // verificar se as senhas são iguais
        if($password === $confirmpassword){

          // verificar se o email já está cadastrado no sistema
          if($userDao->findByEmail($email) === false){
            $user = new User();

            // criação de token
            $userToken = $user->generateToken();
            $finalPassword = $user->generatePassword($password);

            // Montando o objeto
            $user->name = $name;
            $user->lastname = $lastname;
            $user->email = $email;
            $user->password = $finalPassword;
            $user->token = $userToken;


            // autenticando o usuário
            $auth = true;

            $userDao->create($user, $auth);

          } else {
            $message->setMessage("Usuário já cadastrado. Tente outro e-mail.", "error", "back");
          }

        } else{
            $message->setMessage("As senhas não são iguais.", "error", "back");
        }

    } else {
        // enviar uma mensagem de erro, dados faltantes
        $message->setMessage("Por favor, preencha todos os campos.", "error",  "back");
    }

  } else if($type === "login"){

    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");

    // tenta autenticar usuário
    if($userDao->authenticateUser($email, $password)){
      $message->setMessage("Seja bem-vindo.", "success", "editprofile.php");

    } else{
      $message->setMessage("Usuários e/ou senha incorretas.", "error", "back");
    }
  } else{
    $message->setMessage("Informações inválidas", "error", "index.php");
  }