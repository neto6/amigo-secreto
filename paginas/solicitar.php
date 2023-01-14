<?php

require_once('../lib.php');


$grupo = new Grupo();
$grupo->getGrupo($_GET['grupo_id']);

$grupo->addSolicitacao($_SESSION['usuario_id']);
header('location:grupo.php?grupo_id='.$grupo->id);

?>