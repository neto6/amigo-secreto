<?php

require_once('../lib.php');


$grupo = new Grupo();
$grupo->getGrupo($_GET['grupo_id']);

$grupo_usuarios = $grupo->getGrupoUsuarios();
$grupo_solicitacoes = $grupo->getGrupoSolicitacoes();
$grupo_moderadores = $grupo->getGrupoModeradores();

$grupo_usuarios_ids = array();
foreach ($grupo_usuarios as $usuario) {$grupo_usuarios_ids[] = $usuario->id;}

$grupo_solicitacoes_ids = array();
foreach ($grupo_solicitacoes as $usuario) {$grupo_solicitacoes_ids[] = $usuario->id;}

$grupo_moderadores_ids = array();
foreach ($grupo_moderadores as $usuario) {$grupo_moderadores_ids[] = $usuario->id;}

?>

<?php require_once('_header.php'); ?>
  <div class="wrapper">
    <div class="page-header clear-filter page-header-large" filter-color="orange">
      <div class="page-header-image" data-parallax="true" style="background-image:url('../assets/img/bg5.jpg');">
      </div>
      <div class="container">
        <div class="photo-container">
          <img src="../assets/img/group.jpg" alt="">
        </div>
        <h3 class="title"><?php echo $grupo->nome; ?></h3>
        <div class="content">
          <div class="social-description">
            <h2><?php echo count($grupo_usuarios); ?></h2>
            <p>Integrantes</p>
          </div>
          <div class="social-description">
            <h2><?php echo substr($grupo->data_sorteio, 0, 5); ?></h2>
            <p>Sorteio</p>
          </div>
          <div class="social-description">
            <h2><?php echo $grupo->valor_medio; ?></h2>
            <p>Valor (R$)</p>
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="button-container">
          <?php if (!in_array($_SESSION['usuario_id'], $grupo_usuarios_ids) && $grupo->sorteio_realizado == 0) { ?>
            <?php if (!in_array($_SESSION['usuario_id'], $grupo_solicitacoes_ids)) { ?>
            <a href="solicitar.php?grupo_id=<?php echo $grupo->id; ?>" class="btn btn-primary btn-round btn-lg">
            Solicitar participação</a>
            <?php } else { ?>
              <a href="#" class="btn btn-primary btn-round btn-lg">
            Solicitação enviada!</a>
            <?php } ?>
          <?php } else { ?> 
            <?php if (in_array($_SESSION['usuario_id'], $grupo_moderadores_ids) && $grupo->sorteio_realizado == 0) { ?>
            <a href="sorteio.php?grupo_id=<?php echo $grupo->id; ?>" class="btn btn-primary btn-round btn-lg">
            Realizar Sorteio</a>
            <?php } ?>
          <?php } ?>
        </div>


      <?php if (in_array($_SESSION['usuario_id'], $grupo_usuarios_ids)) { ?>
        <?php if ($grupo->sorteio_realizado == 0) { ?>
          <br><br><div class="alert alert-warning" role="alert">
            O sorteio deste grupo ainda não foi realizado!
          </div>
        <?php } else { ?>
          <br><br><div class="alert alert-success" role="alert">
            O sorteio deste grupo foi realizado:
            <!-- Button trigger modal -->
            <a href="#" data-toggle="modal" data-target="#exampleModal" style="color:white">
              <strong>Veja quem é seu amigo secreto!</strong>
            </a>
          </div>
        <?php } ?>
      <?php } ?>


        <?php if (count($grupo_solicitacoes) > 0 && in_array($_SESSION['usuario_id'], $grupo_moderadores_ids)) { ?>

        <h3 class="title">Solicitações Pendentes</h3>
        <div class="row">


        <?php foreach ($grupo_solicitacoes as $usuario) { ?>
            <div class="card col-md-3" style="padding:10px">
              <img class="card-img-top" src="../assets/img/ryan.jpg" alt="Card image cap">
              <div class="card-body">
                <h4 class="card-title"><?php echo $usuario->nome; ?></h4>
                <a href="aprovar.php?grupo_id=<?php echo $grupo->id; ?>&usuario_id=<?php echo $usuario->id; ?>" class="btn btn-success">Aprovar</a>
                <a href="recusar.php?grupo_id=<?php echo $grupo->id; ?>&usuario_id=<?php echo $usuario->id; ?>" class="btn btn-danger">Recusar</a>
              </div>
            </div>
        <?php } ?>
        
        </div>

        <?php } ?>
        


        <h3 class="title">Integrantes</h3>
        <div class="row">


        <?php foreach ($grupo_usuarios as $usuario) { ?>
            <div class="card col-md-3" style="padding:10px">
              <img class="card-img-top" src="../assets/img/ryan.jpg" alt="Card image cap">
              <div class="card-body">
                <h4 class="card-title"><?php echo $usuario->nome; ?></h4>
                <p class="card-text"><?php echo $usuario->bio; ?></p>
                <a href="perfil.php?usuario_id=<?php echo $usuario->id; ?>" class="btn btn-primary">Ver perfil</a>
              </div>
            </div>
        <?php } ?>


        </div>

      </div>
    </div>

    <?php if ($grupo->sorteio_realizado == 1) { 
      
      $amigo = $grupo->getUsuarioAmigo($_SESSION['usuario_id']);
      
    ?>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Seu amigo secreto é</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>
              <img class="card-img-top" src="../assets/img/ryan.jpg" alt="Card image cap">
              <div class="card-body">
                <h4 class="card-title"><?php echo $amigo->nome; ?></h4>
                <p class="card-text"><?php echo $amigo->bio; ?></p>
                <a href="perfil.php?usuario_id=<?php echo $amigo->id; ?>" class="btn btn-primary">Ver perfil</a>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <?php } ?>

    <?php require_once('_footer.php'); ?>