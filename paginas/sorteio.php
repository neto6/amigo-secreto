<?php

require_once('../lib.php');


$grupo = new Grupo();
$grupo->getGrupo($_GET['grupo_id']);

$grupo->doSorteio();
header('location:grupo.php?grupo_id='.$grupo->id);

?>