<?php

require_once('../lib.php');


$usuario = new Usuario();
$usuario->getUsuario($_SESSION['usuario_id']);

$meus_grupos = $usuario->getUsuarioGrupos();

?>

<?php require_once('_header.php'); ?>
  <div class="wrapper">
    <div class="page-header clear-filter page-header-large" filter-color="orange">
      <div class="page-header-image" data-parallax="true" style="background-image:url('../assets/img/bg5.jpg');">
      </div>
      <div class="container">
        <div class="photo-container">
          <img src="../assets/img/ryan.jpg" alt="">
        </div>
        <h3 class="title"><?php echo $usuario->nome; ?></h3>
        <p class="category"><?php echo $usuario->bio; ?></p>
        <div class="content">
          <div class="social-description">
            <h2><?php echo $usuario->camiseta; ?></h2>
            <p>Camiseta</p>
          </div>
          <div class="social-description">
            <h2><?php echo $usuario->calca; ?></h2>
            <p>Calça</p>
          </div>
          <div class="social-description">
            <h2><?php echo $usuario->sapatos; ?></h2>
            <p>Sapatos</p>
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="button-container">
          <a href="criargrupo.php" class="btn btn-primary btn-round btn-lg">Criar Grupo</a>

          <form class="form-inline" method="GET" action="procurar.php">
            <div class="form-group col-md-9">
                <input type="text" class="form-control col-md-12" name="q" placeholder="Procurar Grupo Pelo Nome">
            </div>
            <input type="submit" class="btn btn-primary col-md-2" value="Pesquisar" />
          </form>

        </div>
        <h3 class="title">Meus Grupos</h3>
            <?php if (count($meus_grupos) == 0) {
                echo "<div class='text-center'>Você ainda não participa de nenhum grupo.</div>";
            } else { ?>

              <div class="row">
                  
              <?php foreach ($meus_grupos as $grupo) { ?>
                  <div class="card col-md-3" style="padding:10px">
                    <img class="card-img-top" src="../assets/img/group.jpg" alt="Card image cap">
                    <div class="card-body">
                      <h4 class="card-title"><?php echo $grupo->nome; ?></h4>
                      <a href="grupo.php?grupo_id=<?php echo $grupo->id; ?>" class="btn btn-primary">Acessar grupo</a>
                    </div>
                  </div>
              <?php } ?>

              </div>

            <?php } ?>
      </div>
    </div>
    <?php require_once('_footer.php'); ?>