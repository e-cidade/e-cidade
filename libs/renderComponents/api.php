<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 0);

    // Permitir requisi��es de qualquer origem
    header("Access-Control-Allow-Origin: *");

    // Permitir m�todos HTTP espec�ficos (GET, POST, etc.)
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    // Permitir cabe�alhos personalizados na requisi��o
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // Verifica se � uma requisi��o OPTIONS (preflight)
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        // Responde com status 200 OK para requisi��es OPTIONS
        http_response_code(200);
        exit();
    }

    // Pega o valor da vari�vel 'component' na URL
    $component = isset($_GET['component']) ? $_GET['component'] : null;

    // Valida��o b�sica da vari�vel (permitir apenas letras, n�meros, h�fen e underscore)
    if ($component && preg_match('/^[a-zA-Z0-9_\/-]+$/', $component)) {

        // Define o caminho do arquivo dinamicamente
        $filePath = "libs/renderComponents/src/components/$component/doc.php";
        
        // Verifica se o arquivo existe antes de inclu�-lo
        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            // Arquivo n�o encontrado - retorna uma p�gina HTML amig�vel
            http_response_code(404);
            echo renderHtml('Arquivo n�o encontrado', 'O arquivo para o componente "' . htmlspecialchars($component) . '" n�o foi encontrado.');
        }
    } else {
        // Componente inv�lido - retorna uma p�gina HTML amig�vel
        http_response_code(400);
        echo renderHtml('Componente inv�lido', 'O componente fornecido � inv�lido ou est� faltando.');
    }

    // Fun��o para renderizar o HTML
    function renderHtml($title, $message) {
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . htmlspecialchars($title) . '</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f9;
                    color: #333;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .container {
                    text-align: center;
                    background-color: #fff;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    max-width: 500px;
                }
                h1 {
                    color: #e74c3c;
                    font-size: 24px;
                    margin-bottom: 16px;
                }
                p {
                    font-size: 18px;
                    margin-bottom: 24px;
                }
                a {
                    color: #3498db;
                    text-decoration: none;
                }
                a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>' . htmlspecialchars($title) . '</h1>
                <p>' . htmlspecialchars($message) . '</p>
            </div>
        </body>
        </html>';
    }
?>
