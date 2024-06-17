<?php

use ECidade\V3\Extension\Registry;
use ECidade\V3\Extension\Request;
use App\Models\ISSQN\RedesimSettings;

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
/**
 * @todo O php deste arquivo deve ser movido para um controller quando o laravel estiver pronto
 */

/**
 * @var Request $request
 */
$request = Registry::get('app.request');
$databaseInstance = Database::getInstance();
$redesimSettings = new RedesimSettings();
$message = '';

function updateRedesimSettings(Request $request): string
{
    $data = $request->post()->all();
    try {
        RedesimSettings::where('q180_sequencial', $data['q180_sequencial'])
            ->update(
                [
                    'q180_url_api' => $data['q180_url_api'],
                    'q180_client_id' => $data['q180_client_id'],
                    'q180_vendor_id' => $data['q180_vendor_id'],
                    'q180_access_key' => $data['q180_access_key'],
                ]
            );
    } catch (Exception $exception) {
       return $exception->getMessage();
    }
    return 'Alterado com sucesso!';
}

function deleteRedesimSettings(Request $request): string
{
    $id = $request->post()->get('q180_sequencial');
    try {
        RedesimSettings::where('q180_sequencial', $id)->delete();
    } catch (Exception $exception) {
        return $exception->getMessage();
    }
    return 'Removido com sucesso!';
}

function postRedesimSettings(Request $request): string
{
    $data = $request->post()->all();

    try {
        RedesimSettings::insert(
            [
                'q180_url_api' => $data['q180_url_api'],
                'q180_client_id' => $data['q180_client_id'],
                'q180_vendor_id' => $data['q180_vendor_id'],
                'q180_access_key' => $data['q180_access_key'],
            ]
        );
    } catch (Exception $exception) {
        return $exception->getMessage();
    }
    return 'Incluído com sucesso!';
}

if ($request->post()->has('alterar')) {
    $message = updateRedesimSettings($request);
}

if ($request->post()->has('incluir')) {
    $message = postRedesimSettings($request);
}

if ($request->post()->has('remover')) {
    $message = deleteRedesimSettings($request);
}

$data = $redesimSettings->all()->first();
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
    #redesim_table {
        min-width: 470px;
    }

    #redesim_table input {
        width: 100%;
    }
</style>
<div class="container">
    <form id="frmRedesimSettings" name="form1" method="post" action="">
        <input type="hidden" name="q180_sequencial" id="q180_sequencial" value="<?= $data->q180_sequencial ?>">
        <fieldset>
            <legend>Integração Redesim</legend>
            <table id="redesim_table">
                <tr>
                    <th style="width: 50%"></th>
                    <th style="width: 50%"></th>
                </tr>
                <tr>
                    <td>
                        <strong>End Point API URL:</strong>
                    </td>
                    <td>
                        <input required type="text" name="q180_url_api" id="q180_url_api" value="<?= $data->q180_url_api ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Identificador do Cliente:</strong>
                    </td>
                    <td>
                        <input required type="text" name="q180_client_id" id="q180_client_id" value="<?= $data->q180_client_id ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Identificador do Fornecedor:</strong>
                    </td>
                    <td>
                        <input required type="text" name="q180_vendor_id" id="q180_vendor_id" value="<?= $data->q180_vendor_id ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Chave de acesso para utilização da API:</strong>
                    </td>
                    <td>
                        <input required type="text" name="q180_access_key" id="q180_access_key" value="<?= $data->q180_access_key ?>">
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="actions">
            <input type="submit" id="saveRedesimSettings" name="<?= empty($data->q180_sequencial) ? 'incluir' : 'alterar' ?>"
                   value="<?= empty($data->q180_sequencial) ? 'Incluir' : 'Alterar' ?>">
            <input type="button" id="deleteRedesimSettings" value="Excluir" <?=empty($data->q180_sequencial) ? 'disabled' : '' ?> />
        </div>
    </form>
</div>
<script type="text/javascript" src="scripts/scripts.js"></script>

<script type="text/javascript">
    const message = '<?=$message?>';
    const form1 = document.getElementById('frmRedesimSettings');
    const deleteRedesimSettings = document.getElementById('deleteRedesimSettings');

    if (message !== '') {
        alert(message);
    }

    const handleRemoveSettings = (e) => {
        e.preventDefault();
        if(confirm('Deseja remover?') === true) {
            input = document.createElement('input');
            input.setAttribute('name', 'remover');
            input.setAttribute('type', 'hidden');
            form1.appendChild(input);
            form1.submit();
        }
    }
    deleteRedesimSettings.addEventListener('click', handleRemoveSettings)
</script>
