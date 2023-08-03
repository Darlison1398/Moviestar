
<?php
// header do sistema
  require_once("templates/header.php");
  require_once("dao/MovieDAO.php");

  // DAO dos filmes
  $movieDao = new MovieDAO($conn, $BASE_URL);

  $latesMovies = $movieDao->getLatesMovies();
 // print_r($latesMovies);

  $actionMovies = $movieDao->getMoviesByCategory("ação");

  $comedyMovies = $movieDao->getMoviesByCategory("comédia");

?>

    <!--  MAIN -->
    <div id="main-container" class="container-fluid">
        <h2 class="section-title">Filmes novos</h2>
        <p class="section-description">Veja as críticas dos útlimos filmes adicionados no Moviestar</p>
        <div class="movies-container">
          <!--  Imprimindo vários filmes com a função do php  -->

          <?php foreach ($latesMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
          <?php endforeach; ?>

          <?php if(count($latesMovies) === 0) :?>
            <p class="empty-list">Ainda não há filmes cadastrado!</p>
          <?php endif; ?>   
        </div>

        <h2 class="section-title">Ação</h2>
        <p class="section-description">Veja os melhores filmes de ação</p>
        <div class="movies-container">
          <?php foreach ($actionMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
          <?php endforeach; ?>

          <?php if(count($actionMovies) === 0) :?>
            <p class="empty-list">Ainda não há filmes de ação cadastrado!</p>
          <?php endif; ?>
        </div>


        <h2 class="section-title">Comédia</h2>
        <p class="section-description">Veja os melhores filmes de ação</p>
        <div class="movies-container">
          <?php foreach ($comedyMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
          <?php endforeach; ?>

          <?php if(count($comedyMovies) === 0) :?>
            <p class="empty-list">Ainda não há filmes de comédia cadastrado!</p>
          <?php endif; ?>
        </div>
    </div>


<?php
// footer do sistema
  require_once("templates/footer.php");
?>

