<?php

require_once("model/licitacao/PortalCompras/Comandos/EnviaUnidadeMedidaPcpInterface.model.php");

class EnviaUnidadeMedidaPcp implements EnviaUnidadeMedidaPcpInterface
{

    public function get(string $url): array
    {
        try {

            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);
            $resultado = json_decode($response->getBody());
            return $resultado;

        } catch(GuzzleHttp\Exception\ClientException $e) {

            $resultado = json_decode($e->getResponse()->getBody()->getContents());
        
            if (isset($resultado->error->status) && $resultado->error->status == 404) {
                $message = utf8_encode("A URL de conexão do portal de compras está incorreta. Verifique as configurações de conexão com o portal de compras.");
            } else {
                $message = $resultado->mensagem ?? utf8_encode("Ocorreu um erro inesperado.");
            }
        
            return [
                'success' => 2,
                'message' => $message,
            ];
        
        } catch (GuzzleHttp\Exception\ServerException $e) {
            // Verificando se a resposta é um erro 502
            if ($e->getResponse()->getStatusCode() === 502) {
                return [
                    'success' => 2,
                    'message' => utf8_encode("O servidor de origem do portal de contas publicas pode estar inativo ou sobrecarregado, não respondendo às solicitações, tente novamente mais tarde!"),
                ];
            }
        
            // Caso de outro erro do servidor
            return [
                'success' => 2,
                'message' => utf8_encode("Ocorreu um erro no servidor."),
            ];
        }
    }


    public function create(array $unidadeMedida, string $url): object
    {
        try {
        
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($unidadeMedida));

            $response = json_decode(curl_exec($ch));
            curl_close($ch);

            return $response;

        } catch(GuzzleHttp\Exception\ClientException $e) {

            $resultado = json_decode($e->getResponse()->getBody()->getContents());

            return [
                'success' => $resultado['success'],
                'message' => $resultado->mensagem,
            ];
        }
    }
}
