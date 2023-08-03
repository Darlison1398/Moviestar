<?php
  
  class Movie {

    public $id;
    public $title;
    public $description;
    public $image;
    public $trailer;
    public $category;
    public $length;
    public $user_id;


    public function imageGenerateName(){
        return bin2hex(random_bytes(60) .".jpg");

        // o ideal seria criar um model só para receber imagens. Isso nos ajudaria a dividir a responsabilidade entre as classes
    }

  }

  interface MovieDAOInterface {

    public function buildMovie($data);
    public function findAll();
    public function getLatesMovies();
    public function getMoviesByCategory($category);
    public function getMoviesByUserId($id);
    public function findById($id);
    public function findByTitle($title);
    public function create(Movie $movie);
    public function update(Movie $movie);
    public function destroy($id);
  }

  ?>