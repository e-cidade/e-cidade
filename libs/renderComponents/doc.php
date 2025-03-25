<?php
    require_once 'libs/renderComponents/src/helpers/ComponentRenderer.php';
    require_once 'libs/renderComponents/src/helpers/Directories.php';

    try {
        
        $component = new ComponentRenderer(true);

        function includeOnceAsset($type, $path) {
            static $loadedAssets = [
                'css' => [],
                'js' => [],
            ];
    
            $directories = new Directories(true);
    
            // Verifica se o asset já foi carregado
            if (!in_array($path, $loadedAssets[$type])) {
                $loadedAssets[$type][] = $path;
    
                // Adiciona a tag de acordo com o tipo de asset
                if ($type === 'css') {
                    echo '<link rel="stylesheet" type="text/css" href="' . $directories->getBaseUrl() . htmlspecialchars($path) . '">' . PHP_EOL;
                } elseif ($type === 'js') {
                    echo '<script src="' . $directories->getBaseUrl() . htmlspecialchars($path) . '"></script>' . PHP_EOL;
                }
            }
        }

    } catch (Exception $e) {

        error_log($e->getMessage());
        echo 'Ocorreu um erro ao inicializar o componente.';
        
    }
?>