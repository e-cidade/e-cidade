<?php

/**
 * Classe `cl_contratacoesdiretas`
 * Respons�vel por realizar opera��es de consulta no banco de dados e manipular o resultado dessas opera��es.
 */
class cl_contratacoesdiretas
{
  // Propriedades p�blicas para armazenar informa��es sobre a opera��o de banco de dados e status de erro
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
   * Construtor padr�o da classe.
   *
   * Inicializa a inst�ncia da classe sem par�metros adicionais ou l�gica interna.
   */
  public function __construct()
  {}

  /**
   * M�todo `sqlRecord`
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
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    // Obt�m o n�mero de linhas retornadas pela consulta
    $this->numrows = pg_num_rows($result);

    // Se n�o houver registros, define mensagens de erro apropriadas
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:pcorcamval";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    // Retorna o resultado em caso de sucesso
    return $result;
  }

  /**
   * M�todo `sqlQuery`
   * Gera a consulta SQL padr�o para sele��o de registros na tabela `db_depart`.
   *
   * @return string A consulta SQL gerada.
   */
  private function sqlQuery(int $instit): string
  {
    $sql = "select db_depart.coddepto, db_depart.descrdepto from db_depart where instit = $instit and db_depart.coddepto > 0";
    return $sql;
  }

  /**
   * Gera a consulta SQL para recuperar a descri��o de um departamento espec�fico.
   *
   * @param int $department O identificador do departamento a ser consultado.
   * @return string A consulta SQL para buscar a descri��o do departamento.
   */
  private function sqlQueryDepartment(int $department): string
  {
    $sql = "select db_depart.descrdepto from db_depart where db_depart.coddepto = $department";
    return $sql;
  }

  /**
   * Obt�m uma lista de departamentos para uma institui��o espec�fica.
   *
   * Este m�todo executa a consulta SQL para selecionar os departamentos de uma institui��o e retorna um array
   * associativo com o c�digo do departamento como chave e a descri��o como valor.
   *
   * @param int $instit O c�digo da institui��o para filtrar os departamentos.
   * @return array Um array associativo contendo os departamentos (c�digo => descri��o).
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
   * Obt�m as informa��es do departamento selecionado e retorna um objeto com os detalhes.
   *
   * @param int $department O identificador do departamento a ser consultado.
   * @return object Um objeto contendo as informa��es do departamento.
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
   * Gera a consulta SQL para o relat�rio de processos de contrata��o direta.
   *
   * Este m�todo constr�i uma consulta SQL para selecionar processos de contrata��o direta com base nos crit�rios fornecidos.
   * As condi��es de filtro incluem datas, categoria, departamento e outras restri��es fixas.
   *
   * @param array $searchData Um array contendo os crit�rios de filtro para a consulta (ex: data_inicial, data_final, categoria, departamento).
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

    // Data inicial � obrigat�ria
    if (!empty($searchData['data_inicial'])) {
      $sql .= " AND pcproc.pc80_data >= '{$searchData['data_inicial']}'";
    }

    // Se data_final n�o existir, usar data_inicial
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

    // Condi��o adicional fixa
    $sql .= " AND pcproc.pc80_dispvalor = true;";

    return $sql;
  }

  /**
   * Obt�m os dados do relat�rio com base nos crit�rios fornecidos.
   *
   * Este m�todo executa a consulta SQL gerada para o relat�rio de processos de contrata��o direta e retorna um array
   * contendo os resultados, caso existam.
   *
   * @param array $searchData Um array contendo os crit�rios de filtro para a consulta (ex: data_inicial, data_final, categoria, departamento).
   * @return array Um array contendo os registros do relat�rio, ou um array vazio se nenhum registro for encontrado.
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
   * Obt�m os dados do relat�rio com base nos crit�rios fornecidos.
   *
   * Executa a consulta SQL gerada para o relat�rio de processos de contrata��o direta e retorna um JSON
   * indicando se existem registros para a gera��o do relat�rio.
   *
   * @param array $searchData Array contendo os crit�rios de filtro para a consulta (ex: data_inicial, data_final, categoria, departamento).
   * @return string JSON contendo um booleano que indica se h� registros para o relat�rio.
   */
  public function getReportDataValidate(array $searchData): string
  {
    $reportResult = $this->sqlRecord($this->sqlQueryReport($searchData));
    $hasRecords = (pg_num_rows($reportResult) > 0);
    return $hasRecords;
  }

  /**
   * Retorna a descri��o da categoria de processo com base no c�digo fornecido.
   *
   * @param mixed $categoriaProcesso C�digo da categoria do processo.
   * @return string Descri��o correspondente � categoria do processo.
   */
  public function getDescriptionCategoryProcess($categoriaProcesso): string
  {
    $descriptionCategoriaProcesso = "";

    switch ($categoriaProcesso) {
      case 1:
        $descriptionCategoriaProcesso = "Cess�o";
        break;

      case 2:
        $descriptionCategoriaProcesso = "Compras";
        break;

      case 3:
        $descriptionCategoriaProcesso = "Inform�tica (TIC)";
        break;

      case 4:
        $descriptionCategoriaProcesso = "Internacional";
        break;

      case 5:
        $descriptionCategoriaProcesso = "Loca��o Im�veis";
        break;

      case 6:
        $descriptionCategoriaProcesso = "M�o de Obra";
        break;

      case 7:
        $descriptionCategoriaProcesso = "Obras";
        break;

      case 8:
        $descriptionCategoriaProcesso = "Servi�os";
        break;

      case 9:
        $descriptionCategoriaProcesso = "Servi�os de Engenharia";
        break;

      case 10:
        $descriptionCategoriaProcesso = "Servi�os de Sa�de";
        break;

      default:
        $descriptionCategoriaProcesso = "Outros";
        break;
    }

    return $descriptionCategoriaProcesso;
  }

  /**
   * Retorna a modalidade de contrata��o com base no c�digo de categoria do processo.
   *
   * @param mixed $categoriaProcesso C�digo da categoria do processo.
   * @return string Modalidade de contrata��o correspondente � categoria do processo.
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
   * Retorna o exerc�cio com base nos dados de busca fornecidos.
   * O exerc�cio consiste no intervalo entre as datas inicial e final,
   * ou apenas na data inicial se a data final n�o for fornecida.
   *
   * @param array $searchData Dados de busca contendo as chaves 'data_inicial' e 'data_final'.
   * @return string Exerc�cio formatado como 'data_inicial - data_final' ou apenas 'data_inicial'.
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
   * Se o departamento n�o estiver presente, retorna null.
   *
   * @param array $searchData Dados de busca contendo a chave 'departamento'.
   * @return string Nome do departamento ou null se n�o estiver dispon�vel.
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
   * Se a categoria n�o estiver presente, retorna null.
   *
   * @param array $searchData Dados de busca contendo a chave 'categoria'.
   * @return string Categoria ou null se n�o estiver dispon�vel.
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
