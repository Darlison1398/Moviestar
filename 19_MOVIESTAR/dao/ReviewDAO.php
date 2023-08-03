<?php

  require_once("models/Review.php");
 // require_once("dao/Message.php");
  require_once("dao/UserDAO.php");

  class ReviewDao implements ReviewDAOInterface{

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url){
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }



    // as próximas partes são os métodos que vem da interface que está em review
    public function buildReview($data){

        $reviewObject = new Review();

        $reviewObject->id = $data["id"];
        $reviewObject->rating = $data["rating"];
        $reviewObject->review = $data["review"];
        $reviewObject->users_id = $data["users_id"];
        $reviewObject->movies_id = $data["movies_id"];
        
        return $reviewObject;

    }


    public function create(Review $review){

        $stmt = $this->conn->prepare("INSERT INTO reviews (rating, review, movies_id, users_id)VALUES (:rating, :review, :movies_id, :users_id)");

        $stmt->bindParam(":rating", $review->rating);
        $stmt->bindParam(":review", $review->review);
        $stmt->bindParam(":movies_id", $review->movies_id);
        $stmt->bindParam(":users_id", $review->users_id);
  
        $stmt->execute();
        
        // filme adicionado
        $this->message->setMessage("Comentário adicionado com sucesso!", "success", "index.php");
    }


    public function getMoviesReview($id){

        $reviews = [];
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");

        $stmt->bindParam(":movies_id", $id);

        $stmt->execute();

        if($stmt->rowCount() > 0) {

           // $reviewsData= $stmt->fetch();
           $reviewsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

           $userDao = new UserDao($this->conn, $this->url);

            foreach($reviewsData as $review){

                $reviewObject = $this->buildReview($review);

                // chamar dados do usuário
                $user = $userDao->findById($reviewObject->users_id);

                $reviewObject->user = $user;

                $reviews[] = $reviewObject;
            }
        }
        
        return $reviews;

    }


    public function hasAlreadyReview($id, $users_id){

        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id AND  users_id = :users_id");

        $stmt->bindParam(":movies_id", $id);
        $stmt->bindParam(":users_id", $users_id);

        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }


    }

    public function getRating($id){

        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");

        $stmt->bindParam(":movies_id", $id);
        $stmt->execute();

        if($stmt->rowCount() > 0){

            $rating = 0;

            $reviews = $stmt->fetchAll();

            // calculando a média
            foreach($reviews as $review) {
                $rating += $review["rating"];
            }

            $rating = $rating / count($reviews);

        } else {
            $rating = "Não avaliado!";
        }

        return $rating;
    }

  }