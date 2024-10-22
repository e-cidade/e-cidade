<?php

require_once("model/licitacao/PortalCompras/Modalidades/Licitacao.model.php");
require_once("model/licitacao/PortalCompras/Comandos/EnviaUnidadeMedidaPcp.model.php");

class UnidadeMedida extends Licitacao
{
    // Arrays para armazenar diferentes categorias de siglas e descrições
    private $bodyPostShoppingPortal = []; // Corpo de inclusão para o pcp
    private $descricoesExistentesNoEcidade = []; // Descrições existentes no ecidade
    private $descricoesExistentesNoPcp = []; // Descrições existentes no portal de compras
    private $siglasInvalidas = []; // Siglas invalidas (size > 5)
    private $siglasExistentesNoEcidade = []; // Siglas existentes no ecidade
    private $siglasExistentesNoPcp = []; // Siglas existentes no portal de compras
    private $siglasFaltantesNoPcp = []; // Siglas que estão faltando
    private $siglasIncorretasECorretas = []; // Siglas incorretas e corretas
    private $respostaSiglaUnidadeGet = []; // Resposta da operação de captura das unidades de medidas

    public function __construct()
    {
        parent::__construct(); // Chama o construtor da classe pai
    }

    /**
     * Constrói a URL para acessar o portal de compras para uma chave pública específica.
     *
     * @param string|null $publicKey Chave pública do comprador.
     * @return string URL do portal de compras.
     */
    public function getUrlPortalCompras(string $publicKey = null): string
    {
        return $this->envs['URL'] . "/comprador/$publicKey/unidade";
    }

    /**
     * Constrói a URL para obter todas as unidades do portal de compras.
     *
     * @param string|null $publicKey Chave pública do comprador.
     * @return string URL para obter as unidades.
     */
    public function getUrlGetShoppingPortal(string $publicKey = null): string
    {
        return $this->envs['URL'] . "/comprador/$publicKey/unidades";
    }

    /**
     * Constrói a URL para postar uma nova unidade no portal de compras.
     *
     * @param string|null $publicKey Chave pública do comprador.
     * @return string URL para postar uma unidade.
     */
    public function getUrlPostShoppingPortal(string $publicKey = null): string
    {
        return $this->envs['URL'] . "/comprador/$publicKey/unidade";
    }

    /**
     * Valida siglas e descrições contra os dados do portal de compras.
     *
     * Este método extrai siglas e descrições dos resultados de licitação,
     * consulta o portal de compras para obter siglas e descrições existentes,
     * e verifica quais siglas estão faltando ou incorretas.
     *
     * @param array $resultadosLicitacao Resultados da licitação a serem validados.
     * @param int $numrows Número de linhas nos resultados da licitação.
     * @param string $chaveAcesso Chave de acesso para o portal de compras.
     * @return void
     */
    public function validateAcronymAndDescriptionShoppingPortal($resultadosLicitacao, $numrows, $chaveAcesso)
    {
        // Popula os arrays com siglas e descrições dos resultados fornecidos
        for ($i = 0; $i < $numrows; $i++) {
            $resultado = db_utils::fieldsMemory($resultadosLicitacao, $i);
            $this->siglasExistentesNoEcidade[] = utf8_encode($resultado->siglaunidade); // Armazena a sigla
            $this->descricoesExistentesNoEcidade[] = utf8_encode($resultado->descunidade); // Armazena a descrição
        }

        // Instancia as classes necessárias para a interação com a API
        $urlPortalCompras = $this->getUrlGetShoppingPortal($chaveAcesso);
        $enviadorUnidadeMedida = new EnviaUnidadeMedidaPcp();
        $respostaSiglaUnidade = $enviadorUnidadeMedida->get($urlPortalCompras); // Obtém unidades existentes do portal

        // Resposta da operação de captura das unidades de medidas
        $this->respostaSiglaUnidadeGet = $respostaSiglaUnidade;

        // Extrai siglas e descrições da resposta
        $this->siglasExistentesNoPcp = array_column($respostaSiglaUnidade, 'sigla'); // Siglas existentes
        $this->descricoesExistentesNoPcp = array_column($respostaSiglaUnidade, 'descricao'); // Descrições existentes

        // Identifica siglas faltantes
        $this->siglasFaltantesNoPcp = array_diff($this->siglasExistentesNoEcidade, $this->siglasExistentesNoPcp);

        // Unifica todas as unidades de medidas repetidas
        $this->siglasFaltantesNoPcp = $this->juntarUnidadesRepetidas($this->siglasFaltantesNoPcp);

        // Verifica siglas incorretas
        foreach ($this->siglasFaltantesNoPcp as $key => $siglaFaltante) {

            $this->siglasFaltantesNoPcp[$key] = utf8_encode($siglaFaltante);

            // Encontra o índice correto com base na descrição
            $indiceCorreto = array_search(ucfirst(strtolower($this->descricoesExistentesNoEcidade[$key])), $this->descricoesExistentesNoPcp);

            // Se uma descrição correta for encontrada, armazena as siglas incorretas e corretas
            if ($indiceCorreto !== false) {
                $this->siglasIncorretasECorretas[] = [
                    'incorreta' => $siglaFaltante,
                    'correta' => $this->siglasExistentesNoPcp[$indiceCorreto],
                    'descricao' => $this->descricoesExistentesNoEcidade[$key],
                ];
            } else {

                if (strlen($siglaFaltante) > 5) {
                    $this->siglasInvalidas[] = utf8_encode("A sigla $siglaFaltante é invalida pois passou de 5 caracteres.");
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
     * Esta função recebe um array de unidades de medida e retorna um novo array 
     * que contém apenas as unidades únicas. A função pode opcionalmente incluir 
     * a contagem de cada unidade de medida no resultado.
     *
     * @param array $array O array de unidades de medida a ser processado.
     * @return array Um array contendo as unidades de medida únicas, com ou sem 
     * a contagem, conforme especificado.
     */
    public function juntarUnidadesRepetidas($array): array
    {
        // Contar as ocorrências de cada elemento
        $contagem = array_count_values($array);
    
        // Criar um novo array com as unidades de medida, suas contagens e o primeiro índice
        $resultado = array();
        $indices_usados = array();  // Para rastrear o primeiro índice de cada unidade
        foreach ($array as $indice => $unidade) {
            // Verificar se já processamos essa unidade
            if (!isset($indices_usados[$unidade])) {
                $indices_usados[$unidade] = $indice;  // Armazenar o primeiro índice
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
     * Retorna as descrições existentes no pcp.
     *
     * @return array Array contendo descrições existentes no pcp.
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
     * Retorna as descrições existentes no ecidade.
     *
     * @return array Array contendo descrições faltantes no ecidade.
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
     * Retorna as siglas existentes no ecidade que não vão ser aceitas no pcp.
     *
     * @return array Array contendo siglas existentes no ecidade que não vão ser aceitas no pcp.
     */
    public function getSiglasInvalidas(): array
    {
        return $this->siglasInvalidas;
    }

    /**
     * Retorna a resposta da operação de captura das unidades de medidas.
     *
     * @return array Array contendo resposta da operação de captura das unidades de medidas.
     */
    public function getRespostaSiglaUnidadeGet(): array
    {
        return $this->respostaSiglaUnidadeGet;
    }

    /**
     * Serializa o objeto para representação JSON.
     *
     * @return array Representação em array do objeto.
     */
    public function jsonSerialize(): array
    {
        return [
            "sigla" => $this->objeto, // Sigla
            "descricao" => $this->tipoRealizacao, // Descrição
        ];
    }
}
