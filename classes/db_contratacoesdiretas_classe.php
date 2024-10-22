<?php

/**
 * Classe `cl_contratacoesdiretas`
 * Responsável por realizar operações de consulta no banco de dados e manipular o resultado dessas operações.
 */
class cl_contratacoesdiretas
{
  // Propriedades públicas para armazenar informações sobre a operação de banco de dados e status de erro
  var $rotulo     = null;
  var $query_sql  = null;
  var $numrows    = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status = null;
  var $erro_sql   = null;
  var $erro_banco = null;
  var $erro_msg   = null;
  var $erro_campo = null;
  var $pagina_retorno = null;

  /**
   * Construtor padrão da classe.
   *
   * Inicializa a instância da classe sem parâmetros adicionais ou lógica interna.
   */
  public function __construct()
  {}

  /**
   * Método `sqlRecord`
   * Executa uma consulta SQL e manipula o resultado.
   *
   * @param string $sql A consulta SQL a ser executada.
   * @return mixed Retorna o resultado da consulta em caso de sucesso ou false em caso de falha.
   */
  public function sqlRecord($sql)
  {
    // Executa a consulta no banco de dados
    $result = db_query($sql);

    // Se a consulta falhar, define os erros e retorna false
    if (!$result) {
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    // Obtém o número de linhas retornadas pela consulta
    $this->numrows = pg_num_rows($result);

    // Se não houver registros, define mensagens de erro apropriadas
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:pcorcamval";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    // Retorna o resultado em caso de sucesso
    return $result;
  }

  /**
   * Método `sqlQuery`
   * Gera a consulta SQL padrão para seleção de registros na tabela `db_depart`.
   *
   * @return string A consulta SQL gerada.
   */
  private function sqlQuery(int $instit): string
  {
    $sql = "select db_depart.coddepto, db_depart.descrdepto from db_depart where instit = $instit and db_depart.coddepto > 0";
    return $sql;
  }

  /**
   * Gera a consulta SQL para recuperar a descrição de um departamento específico.
   *
   * @param int $department O identificador do departamento a ser consultado.
   * @return string A consulta SQL para buscar a descrição do departamento.
   */
  private function sqlQueryDepartment(int $department): string
  {
    $sql = "select db_depart.descrdepto from db_depart where db_depart.coddepto = $department";
    return $sql;
  }

  /**
   * Obtém uma lista de departamentos para uma instituição específica.
   *
   * Este método executa a consulta SQL para selecionar os departamentos de uma instituição e retorna um array
   * associativo com o código do departamento como chave e a descrição como valor.
   *
   * @param int $instit O código da instituição para filtrar os departamentos.
   * @return array Um array associativo contendo os departamentos (código => descrição).
   */
  public function getDepartmentsSelect(int $instit): array
  {
    $departamentos = $this->sqlRecord($this->sqlQuery($instit));
    for ($aco = 0; $aco < pg_num_rows($departamentos); $aco++) {
      $oDepartamentos = db_utils::fieldsMemory($departamentos, $aco);
      $aDepartamentos[$oDepartamentos->coddepto] = $oDepartamentos->descrdepto;
    }

    return $aDepartamentos;
  }

  /**
   * Obtém as informações do departamento selecionado e retorna um objeto com os detalhes.
   *
   * @param int $department O identificador do departamento a ser consultado.
   * @return object Um objeto contendo as informações do departamento.
   */
  public function getDepartmentSelect(int $department): object
  {
    $departamentos = $this->sqlRecord($this->sqlQueryDepartment($department));
    for ($aco = 0; $aco < pg_num_rows($departamentos); $aco++) {
      $oDepartamento = db_utils::fieldsMemory($departamentos, $aco);
    }

    return $oDepartamento;
  }

  /**
   * Gera a consulta SQL para o relatório de processos de contratação direta.
   *
   * Este método constrói uma consulta SQL para selecionar processos de contratação direta com base nos critérios fornecidos.
   * As condições de filtro incluem datas, categoria, departamento e outras restrições fixas.
   *
   * @param array $searchData Um array contendo os critérios de filtro para a consulta (ex: data_inicial, data_final, categoria, departamento).
   * @return string A consulta SQL gerada.
   */
  private function sqlQueryReport($searchData): string
  {
    $sql = "SELECT
              pcproc.pc80_codproc,
              pcproc.pc80_data,
              pcproc.pc80_numdispensa,
              pcproc.pc80_modalidadecontratacao,
              pcproc.pc80_categoriaprocesso,
              COALESCE(db_depart.descrdepto, '-') AS descrdepto,
              pcproc.pc80_resumo,
              COALESCE(liccontrolepncp.l213_numerocontrolepncp, '-') AS l213_numerocontrolepncp,
              COALESCE(TO_CHAR(liccontrolepncp.l213_dtlancamento, 'DD/MM/YYYY'), '-') AS l213_dtlancamento
            FROM
              pcproc
            LEFT JOIN liccontrolepncp ON
              pcproc.pc80_codproc = liccontrolepncp.l213_processodecompras
            LEFT JOIN db_depart on
              pcproc.pc80_depto = db_depart.coddepto
            WHERE 1=1";

    // Data inicial é obrigatória
    if (!empty($searchData['data_inicial'])) {
      $sql .= " AND pcproc.pc80_data >= '{$searchData['data_inicial']}'";
    }

    // Se data_final não existir, usar data_inicial
    $data_final = !empty($searchData['data_final']) ? $searchData['data_final'] : $searchData['data_inicial'];
    if (!empty($data_final)) {
      $sql .= " AND pcproc.pc80_data <= '{$data_final}'";
    }

    // Verifica se departamento foi passado
    if (!empty($searchData['departamento'])) {
      $sql .= " AND pcproc.pc80_depto = {$searchData['departamento']}";
    }

    // Verifica se categoria foi passada
    if (!empty($searchData['categoria'])) {
      $sql .= " AND pcproc.pc80_categoriaprocesso = {$searchData['categoria']}";
    }

    // Condição adicional fixa
    $sql .= " AND pcproc.pc80_dispvalor = true;";

    return $sql;
  }

  /**
   * Obtém os dados do relatório com base nos critérios fornecidos.
   *
   * Este método executa a consulta SQL gerada para o relatório de processos de contratação direta e retorna um array
   * contendo os resultados, caso existam.
   *
   * @param array $searchData Um array contendo os critérios de filtro para a consulta (ex: data_inicial, data_final, categoria, departamento).
   * @return array Um array contendo os registros do relatório, ou um array vazio se nenhum registro for encontrado.
   */
  public function getReportData(array $searchData): array
  {
    $aReport = array();
    $report = $this->sqlRecord($this->sqlQueryReport($searchData));

    for ($aco = 0; $aco < pg_num_rows($report); $aco++) {
      $aReport[] = db_utils::fieldsMemory($report, $aco);
    }

    return $aReport;
  }

  /**
   * Obtém os dados do relatório com base nos critérios fornecidos.
   *
   * Executa a consulta SQL gerada para o relatório de processos de contratação direta e retorna um JSON
   * indicando se existem registros para a geração do relatório.
   *
   * @param array $searchData Array contendo os critérios de filtro para a consulta (ex: data_inicial, data_final, categoria, departamento).
   * @return string JSON contendo um booleano que indica se há registros para o relatório.
   */
  public function getReportDataValidate(array $searchData): string
  {
    $reportResult = $this->sqlRecord($this->sqlQueryReport($searchData));
    $hasRecords = (pg_num_rows($reportResult) > 0);
    return $hasRecords;
  }

  /**
   * Retorna a descrição da categoria de processo com base no código fornecido.
   *
   * @param mixed $categoriaProcesso Código da categoria do processo.
   * @return string Descrição correspondente à categoria do processo.
   */
  public function getDescriptionCategoryProcess($categoriaProcesso): string
  {
    $descriptionCategoriaProcesso = "";

    switch ($categoriaProcesso) {
      case 1:
        $descriptionCategoriaProcesso = "Cessão";
        break;

      case 2:
        $descriptionCategoriaProcesso = "Compras";
        break;

      case 3:
        $descriptionCategoriaProcesso = "Informática (TIC)";
        break;

      case 4:
        $descriptionCategoriaProcesso = "Internacional";
        break;

      case 5:
        $descriptionCategoriaProcesso = "Locação Imóveis";
        break;

      case 6:
        $descriptionCategoriaProcesso = "Mão de Obra";
        break;

      case 7:
        $descriptionCategoriaProcesso = "Obras";
        break;

      case 8:
        $descriptionCategoriaProcesso = "Serviços";
        break;

      case 9:
        $descriptionCategoriaProcesso = "Serviços de Engenharia";
        break;

      case 10:
        $descriptionCategoriaProcesso = "Serviços de Saúde";
        break;

      default:
        $descriptionCategoriaProcesso = "Outros";
        break;
    }

    return $descriptionCategoriaProcesso;
  }

  /**
   * Retorna a modalidade de contratação com base no código de categoria do processo.
   *
   * @param mixed $categoriaProcesso Código da categoria do processo.
   * @return string Modalidade de contratação correspondente à categoria do processo.
   */
  public function getTypeContracting($categoriaProcesso): string
  {
    $descriptionCategoriaProcesso = "";

    switch ($categoriaProcesso) {
      case 8:
        $descriptionCategoriaProcesso = "Dispensa sem Disputa";
        break;

      case 9:
        $descriptionCategoriaProcesso = "Inexigibilidade";
        break;

      default:
        $descriptionCategoriaProcesso = "Outros";
        break;
    }

    return $descriptionCategoriaProcesso;
  }

  /**
   * Retorna o exercício com base nos dados de busca fornecidos.
   * O exercício consiste no intervalo entre as datas inicial e final,
   * ou apenas na data inicial se a data final não for fornecida.
   *
   * @param array $searchData Dados de busca contendo as chaves 'data_inicial' e 'data_final'.
   * @return string Exercício formatado como 'data_inicial - data_final' ou apenas 'data_inicial'.
   */
  public function getExercise(array $searchData): string
  {
    $exercise = null;

    if (!empty($searchData['data_inicial'])) {
      $exercise .= date('d/m/Y', strtotime($searchData['data_inicial']));
    }

    $data_final = !empty($searchData['data_final']) ? $searchData['data_final'] : $searchData['data_inicial'];
    if (!empty($data_final)) {
      $exercise .= " - " . date('d/m/Y', strtotime($data_final));
    }

    return $exercise;
  }

  /**
   * Retorna o departamento com base nos dados de busca fornecidos.
   * Se o departamento não estiver presente, retorna null.
   *
   * @param array $searchData Dados de busca contendo a chave 'departamento'.
   * @return string Nome do departamento ou null se não estiver disponível.
   */
  public function getDepartment(array $searchData): string
  {
    $department = null;

    if (!empty($searchData['departamento'])) {
      $oDepartment = $this->getDepartmentSelect($searchData['departamento']);
      $department = $oDepartment->descrdepto;
    } else {
      $department = "TODOS";
    }

    return $department;
  }

  /**
   * Retorna a categoria com base nos dados de busca fornecidos.
   * Se a categoria não estiver presente, retorna null.
   *
   * @param array $searchData Dados de busca contendo a chave 'categoria'.
   * @return string Categoria ou null se não estiver disponível.
   */
  public function getCategory(array $searchData): string
  {
    $category = null;

    if (!empty($searchData['categoria'])) {
      $category = $this->getDescriptionCategoryProcess($searchData['categoria']);
    } else {
      $category = "TODAS";
    }

    return $category;
  }
}
