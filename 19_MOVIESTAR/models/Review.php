<?php

 class Review{
    // aqui, eu coloco todos os campos de dados que o usuário deve ter
    public $id;
    public $rating;
    public $review;
    public $users_id;
    public $movies_id;

 }

 interface ReviewDAOInterface{

    public function buildReview($data);
    public function create(Review $review);
    public function getMoviesReview($id);
    public function hasAlreadyReview($id, $users_id);
    public function getRating($id);

 }