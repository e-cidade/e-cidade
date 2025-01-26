<?php

require_once("model/licitacao/PortalCompras/Modalidades/Licitacao.model.php");
require_once("model/licitacao/PortalCompras/Comandos/EnviaUnidadeMedidaPcp.model.php");

class UnidadeMedida extends Licitacao
{
    // Arrays para armazenar diferentes categorias de siglas e descri��es
    private $bodyPostShoppingPortal = []; // Corpo de inclus�o para o pcp
    private $descricoesExistentesNoEcidade = []; // Descri��es existentes no ecidade
    private $descricoesExistentesNoPcp = []; // Descri��es existentes no portal de compras
    private $siglasInvalidas = []; // Siglas invalidas (size > 5)
    private $siglasExistentesNoEcidade = []; // Siglas existentes no ecidade
    private $siglasExistentesNoPcp = []; // Siglas existentes no portal de compras
    private $siglasFaltantesNoPcp = []; // Siglas que est�o faltando
    private $siglasIncorretasECorretas = []; // Siglas incorretas e corretas
    private $respostaSiglaUnidadeGet = []; // Resposta da opera��o de captura das unidades de medidas

    public function __construct()
    {
        parent::__construct(); // Chama o construtor da classe pai
    }

    /**
     * Constr�i a URL para acessar o portal de compras para uma chave p�blica espec�fica.
     *
     * @param string|null $publicKey Chave p�blica do comprador.
     * @return string URL do portal de compras.
     */
    public function getUrlPortalCompras(string $publicKey = null): string
    {
        return $this->envs['URL'] . "/comprador/$publicKey/unidade";
    }

    /**
     * Constr�i a URL para obter todas as unidades do portal de compras.
     *
     * @param string|null $publicKey Chave p�blica do comprador.
     * @return string URL para obter as unidades.
     */
    public function getUrlGetShoppingPortal(string $publicKey = null): string
    {
        return $this->envs['URL'] . "/comprador/$publicKey/unidades";
    }

    /**
     * Constr�i a URL para postar uma nova unidade no portal de compras.
     *
     * @param string|null $publicKey Chave p�blica do comprador.
     * @return string URL para postar uma unidade.
     */
    public function getUrlPostShoppingPortal(string $publicKey = null): string
    {
        return $this->envs['URL'] . "/comprador/$publicKey/unidade";
    }

    /**
     * Valida siglas e descri��es contra os dados do portal de compras.
     *
     * Este m�todo extrai siglas e descri��es dos resultados de licita��o,
     * consulta o portal de compras para obter siglas e descri��es existentes,
     * e verifica quais siglas est�o faltando ou incorretas.
     *
     * @param array $resultadosLicitacao Resultados da licita��o a serem validados.
     * @param int $numrows N�mero de linhas nos resultados da licita��o.
     * @param string $chaveAcesso Chave de acesso para o portal de compras.
     * @return void
     */
    public function validateAcronymAndDescriptionShoppingPortal($resultadosLicitacao, $numrows, $chaveAcesso)
    {
        // Popula os arrays com siglas e descri��es dos resultados fornecidos
        for ($i = 0; $i < $numrows; $i++) {
            $resultado = db_utils::fieldsMemory($resultadosLicitacao, $i);
            $this->siglasExistentesNoEcidade[] = utf8_encode($resultado->siglaunidade); // Armazena a sigla
            $this->descricoesExistentesNoEcidade[] = utf8_encode($resultado->descunidade); // Armazena a descri��o
        }

        // Instancia as classes necess�rias para a intera��o com a API
        $urlPortalCompras = $this->getUrlGetShoppingPortal($chaveAcesso);
        $enviadorUnidadeMedida = new EnviaUnidadeMedidaPcp();
        $respostaSiglaUnidade = $enviadorUnidadeMedida->get($urlPortalCompras); // Obt�m unidades existentes do portal

        // Resposta da opera��o de captura das unidades de medidas
        $this->respostaSiglaUnidadeGet = $respostaSiglaUnidade;

        // Extrai siglas e descri��es da resposta
        $this->siglasExistentesNoPcp = array_column($respostaSiglaUnidade, 'sigla'); // Siglas existentes
        $this->descricoesExistentesNoPcp = array_column($respostaSiglaUnidade, 'descricao'); // Descri��es existentes

        // Identifica siglas faltantes
        $this->siglasFaltantesNoPcp = array_diff($this->siglasExistentesNoEcidade, $this->siglasExistentesNoPcp);

        // Unifica todas as unidades de medidas repetidas
        $this->siglasFaltantesNoPcp = $this->juntarUnidadesRepetidas($this->siglasFaltantesNoPcp);

        // Verifica siglas incorretas
        foreach ($this->siglasFaltantesNoPcp as $key => $siglaFaltante) {

            $this->siglasFaltantesNoPcp[$key] = utf8_encode($siglaFaltante);

            // Encontra o �ndice correto com base na descri��o
            $indiceCorreto = array_search(ucfirst(strtolower($this->descricoesExistentesNoEcidade[$key])), $this->descricoesExistentesNoPcp);

            // Se uma descri��o correta for encontrada, armazena as siglas incorretas e corretas
            if ($indiceCorreto !== false) {
                $this->siglasIncorretasECorretas[] = [
                    'incorreta' => $siglaFaltante,
                    'correta' => $this->siglasExistentesNoPcp[$indiceCorreto],
                    'descricao' => $this->descricoesExistentesNoEcidade[$key],
                ];
            } else {

                if (strlen($siglaFaltante) > 5) {
                    $this->siglasInvalidas[] = utf8_encode("A sigla $siglaFaltante � invalida pois passou de 5 caracteres.");
                }

                $this->bodyPostShoppingPortal[] = [
                    'sigla' => utf8_encode($siglaFaltante),
                    'descricao' => $this->descricoesExistentesNoEcidade[$key],
                ];
            } 
        }
    }

    /**
     * Agrupa unidades de medida em um array, consolidando entradas repetidas.
     *
     * Esta fun��o recebe um array de unidades de medida e retorna um novo array 
     * que cont�m apenas as unidades �nicas. A fun��o pode opcionalmente incluir 
     * a contagem de cada unidade de medida no resultado.
     *
     * @param array $array O array de unidades de medida a ser processado.
     * @return array Um array contendo as unidades de medida �nicas, com ou sem 
     * a contagem, conforme especificado.
     */
    public function juntarUnidadesRepetidas($array): array
    {
        // Contar as ocorr�ncias de cada elemento
        $contagem = array_count_values($array);
    
        // Criar um novo array com as unidades de medida, suas contagens e o primeiro �ndice
        $resultado = array();
        $indices_usados = array();  // Para rastrear o primeiro �ndice de cada unidade
        foreach ($array as $indice => $unidade) {
            // Verificar se j� processamos essa unidade
            if (!isset($indices_usados[$unidade])) {
                $indices_usados[$unidade] = $indice;  // Armazenar o primeiro �ndice
            }
        }
    
        foreach ($contagem as $unidade => $quantidade) {
            $primeiro_indice = $indices_usados[$unidade];
            $resultado[$primeiro_indice] = $unidade;
        }
    
        return $resultado;
    }

    /**
     * Retorna as siglas incorretas e corretas.
     *
     * @return array Array contendo siglas incorretas e corretas.
     */
    public function getSiglasIncorretasECorretas(): array
    {
        return $this->siglasIncorretasECorretas;
    }

    /**
     * Retorna as siglas existentes pcp.
     *
     * @return array Array contendo siglas existentes no pcp.
     */
    public function getSiglasExistentesNoPcp(): array
    {
        return $this->siglasExistentesNoPcp;
    }

    /**
     * Retorna as descri��es existentes no pcp.
     *
     * @return array Array contendo descri��es existentes no pcp.
     */
    public function getDescricoesExistentesNoPcp(): array
    {
        return $this->descricoesExistentesNoPcp;
    }

    /**
     * Retorna as siglas faltantes mp Pcp.
     *
     * @return array Array contendo siglas faltantes no pcp.
     */
    public function getSiglasFaltantesNoPcp(): array
    {
        return $this->siglasFaltantesNoPcp;
    }

    /**
     * Retorna as siglas existentes no ecidade.
     *
     * @return array Array contendo siglas existentes no ecidade.
     */
    public function getSiglasExistentesNoEcidade(): array
    {
        return $this->siglasExistentesNoEcidade;
    }

    /**
     * Retorna as descri��es existentes no ecidade.
     *
     * @return array Array contendo descri��es faltantes no ecidade.
     */
    public function getDescricoesExistentesNoEcidade(): array
    {
        return $this->descricoesExistentesNoEcidade;
    }

    /**
     * Retorna o corpo para envio ao portal do pcp com as siglas faltantes no ecidade.
     *
     * @return array Array contendo o corpo de envio ao pcp com as siglas faltantes no ecidade.
     */
    public function getBodyPostShoppingPortal(): array
    {
        return $this->bodyPostShoppingPortal;
    }

    /**
     * Retorna as siglas existentes no ecidade que n�o v�o ser aceitas no pcp.
     *
     * @return array Array contendo siglas existentes no ecidade que n�o v�o ser aceitas no pcp.
     */
    public function getSiglasInvalidas(): array
    {
        return $this->siglasInvalidas;
    }

    /**
     * Retorna a resposta da opera��o de captura das unidades de medidas.
     *
     * @return array Array contendo resposta da opera��o de captura das unidades de medidas.
     */
    public function getRespostaSiglaUnidadeGet(): array
    {
        return $this->respostaSiglaUnidadeGet;
    }

    /**
     * Serializa o objeto para representa��o JSON.
     *
     * @return array Representa��o em array do objeto.
     */
    public function jsonSerialize(): array
    {
        return [
            "sigla" => $this->objeto, // Sigla
            "descricao" => $this->tipoRealizacao, // Descri��o
        ];
    }
}
