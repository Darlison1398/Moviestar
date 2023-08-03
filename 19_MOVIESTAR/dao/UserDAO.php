<?php  

  ## AQUI No DAO,  teremos que criar a conexão do sistema com o banco de dados
  // vamos implementar a interface criada no model
  // vamos trazer o model

  require_once("models/User.php");
  require_once("models/Message.php");

  class UserDAO implements UserDAOinterface {

    private $conn;
    private $url;
    private $message;

    // construtor dessa classe
    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);

    }


    public function buildUser($data){

        $user = new User();

        $user->id = $data["id"];
        $user->name = $data["name"];
        $user->lastname = $data["lastname"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->image = $data["image"];
        $user->bio = $data["bio"];
        $user->token = $data["token"];

        return $user;
    }

    public function create(User $user, $authUser = false){

      $stmt = $this->conn->prepare("INSERT INTO users(name, lastname, email, password, token) VALUES (:name, :lastname, :email, :password, :token)");

      $stmt->bindParam(":name", $user->name);
      $stmt->bindParam(":lastname", $user->lastname);
      $stmt->bindParam(":email", $user->email);
      $stmt->bindParam(":password", $user->password);
      $stmt->bindParam(":token", $user->token);

      $stmt->execute();

      // autenticar usuário

      if($authUser) {
        $this->setTokenToSession($user->token);
      }

    }

    public function update(User $user, $redirect = true){

      $stmt = $this->conn->prepare("UPDATE users SET name = :name, lastname = :lastname, email = :email, image = :image, bio = :bio, token = :token WHERE id = :id");

      $stmt->bindParam(":name", $user->name);
      $stmt->bindParam(":lastname", $user->lastname);
      $stmt->bindParam(":email", $user->email);
      $stmt->bindParam(":image", $user->image);
      $stmt->bindParam(":bio", $user->bio);
      $stmt->bindParam(":token", $user->token);
      $stmt->bindParam(":id", $user->id);

      $stmt->execute();

      if($redirect){
        $this->message->setMessage("Dados atualizados com sucesso!", "success", "editprofile.php");
      }

    }

    public function verifyToken($protected = false){

      if(!empty($_SESSION["token"])){
        // pega o token da session
        $token = $_SESSION["token"];
        $user = $this->findByToken($token);

        if($user){
          return $user;
        } else if($protected) {
          // redireciona o usuário não autenticado
          $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
        } 

      } else if($protected) {
        $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
      }

    }

    public function setTokenToSession($token, $redirect = true){

      // salvar token na secção
      $_SESSION["token"] = $token;

      if($redirect) {

        // redireciona para o perfil
        $this->message->setMessage("Seja bem-vindo!", "sucess", "editprofile.php");
      }

    }

    public function authenticateUser($email, $password){

      $user = $this->findByEmail($email);

      if($user){
        //checar se as senhas são iguais
        if(password_verify($password, $user->password)){
          // gerar um token
          $token = $user->generateToken();
          $this->setTokenToSession($token, false);
          $user->token = $token;
          $this->update($user, false);

          return true;

          
        } else{
          return false;
        }
        
      } else {

        return false;
      }
    }

    public function findByEmail($email){

      // verificando se o campo de email foi preenchido
      if($email != ""){

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");

        $stmt->bindParam(":email", $email);

        $stmt->execute();

        if($stmt->rowCount() > 0){
          $data = $stmt->fetch();
          $user = $this->buildUser($data);

          return $user;

        }else {
          return false;
        }

      } else {
        return false;
      }
    }

    public function findById($id){

      if($id != ""){

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        if($stmt->rowCount() > 0){
          $data = $stmt->fetch();
          $user = $this->buildUser($data);

          return $user;

        }else {
          return false;
        }

      } else {
        return false;
      }

    }

    public function findByToken($token){

      // pesquisa por token

      if($token != ""){

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");

        $stmt->bindParam(":token", $token);

        $stmt->execute();

        if($stmt->rowCount() > 0){
          $data = $stmt->fetch();
          $user = $this->buildUser($data);

          return $user;

        }else {
          return false;
        }

      } else {
        return false;
      }

    }

    public function destroyToken(){

      // remove o token da session
      $_SESSION["token"] = "";

      // redirecionar e apresentar uma mensagem de sucesso
      $this->message->setMessage("Logout com sucesso!", "success", "index.php");
    }

    public function changePassword(User $user){

      $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE id = :id");

      $stmt->bindParam(":password", $user->password);
      $stmt->bindParam(":id", $user->id);
      $stmt->execute();

      $this->message->setMessage("Senha alterada com sucesso!", "success", "editprofile.php");
    }

  }