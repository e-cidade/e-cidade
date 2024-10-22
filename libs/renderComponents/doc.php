<?php 
    require_once 'libs/renderComponents/src/helpers/ComponentRenderer.php';

    try {
        $component = new ComponentRenderer(true);
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo 'Ocorreu um erro ao inicializar o componente.';
    }
?>