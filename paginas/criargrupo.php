<?php

require_once('../lib.php');

if (isset($_POST['nome'])) {
  $grupo = new Grupo();
  $grupo -> nome = $_POST['nome'];
  $grupo -> data_sorteio = $_POST['data_sorteio'];
  $grupo -> valor_medio = $_POST['valor_medio'];
  $grupo -> addGrupo();

  $grupo -> addUsuario($_SESSION['usuario_id']);
  $grupo -> addModerador($_SESSION['usuario_id']);

  header('location:grupo.php?grupo_id='.$grupo->id);
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
        <h3 class="title">Criar um novo grupo</h3>
        <p class="category">Para poder convidar seus amigos!</p>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="row">
          <form action="" method="post">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" class="form-control" name="nome" placeholder="">
            </div>
            <div class="form-group">
                <label>Data do sorteio</label>
                <input type="text" class="form-control" name="data_sorteio" placeholder="">
            </div>
            <div class="form-group">
                <label>Valor médio</label>
                <input type="text" class="form-control" name="valor_medio" placeholder="">
            </div>
            <input type="submit" class="btn btn-primary" value="Criar" />
          </form>
        </div>
      </div>
    </div>
    <?php require_once('_footer.php'); ?>