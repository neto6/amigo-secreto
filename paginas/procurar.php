<?php

require_once('../lib.php');

if (isset($_GET['q'])) {
    $db = new DB();
    $result = $db->select("SELECT * FROM grupos WHERE nome LIKE '%$_GET[q]%'");
}

?>

<?php require_once('_header.php'); ?>
  <div class="wrapper">
    <div class="page-header clear-filter page-header-small" filter-color="orange">
      <div class="page-header-image" data-parallax="true" style="background-image:url('../assets/img/bg5.jpg');">
      </div>
      <div class="container">
        <div class="photo-container">
          <img src="../assets/img/ryan.jpg" alt="">
        </div>
        <h3 class="title">Pesquisa de grupos</h3>
        <p class="category">Encontre o grupo de seus amigos!</p>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <h3 class="title">Resultados:</h3>
            <?php if ($result->num_rows == 0) {
                echo "<div class='text-center'>Nenhum resultado encontrado.</div>";
            } else { ?>

              <div class="row">
                  
              <?php while ($row = $result->fetch_assoc()) { ?>
                  <div class="card col-md-3" style="padding:10px">
                    <img class="card-img-top" src="../assets/img/group.jpg" alt="Card image cap">
                    <div class="card-body">
                      <h4 class="card-title"><?php echo $row['nome']; ?></h4>
                      <a href="grupo.php?grupo_id=<?php echo $row['id']; ?>" class="btn btn-primary">Acessar grupo</a>
                    </div>
                  </div>
              <?php } ?>

              </div>

            <?php } ?>
      </div>
    </div>
    <?php require_once('_footer.php'); ?>