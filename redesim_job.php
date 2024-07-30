<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once('bootstrap.php');

use App\Models\ISSQN\RedesimLogJob;

$env = parse_ini_file(".env");

if (!$env) {
    echo "Arquivo .env nao existe.";
    exit;
}

$eloquent = new EloquentBootstrap(
    $env['DB_SERVIDOR'],
    $env['DB_BASE'],
    $env['DB_USUARIO'],
    $env['DB_SENHA'],
    $env['DB_PORTA']
);

$eloquent->bootstrap();

$url = "http://localhost/{$env['APP_FOLDER_NAME']}/api/v1/redesim/companies";

$token = $env['API_REDESIM_TOKEN'];
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'redesim-token: ' . $token
));
curl_setopt($ch, CURLOPT_POST, true);

try {
    $redesimLogJob = RedesimLogJob::query()->create(
        [
            'q191_started' => date('Y-m-d H:i'),
        ]
    );
} catch (Throwable $throwable){
    exit($throwable->getMessage());
}

$response = curl_exec($ch);
$message = 'Resposta: '. $response;

if (curl_errno($ch)) {
    $message = 'Erro: ' . curl_error($ch);
}

$redesimLogJob->q191_ended = date('Y-m-d H:i');
$redesimLogJob->q191_response = $message;
$redesimLogJob->save();
curl_close($ch);
