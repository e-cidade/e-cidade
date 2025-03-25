<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command;

use Illuminate\Support\Facades\DB;
class VerificaEmpenhoAbastecimentoElemento
{

    public function execute(string $codEmp): ?array
    {
        $codEmp = explode('/',$codEmp);
        $sql = $this->getEmpenhoAbastecimento($codEmp[0],$codEmp[1]);

        $result = DB::select(DB::raw($sql));

        return $result;
    }

    private function getEmpenhoAbastecimento(string $codEmp, int $anoEmp):string
    {

        $sql = "
                    SELECT DISTINCT empempenho.e60_numemp,
                                    e60_emiss,
                                    empempenho.e60_codemp
                    FROM empempenho
                    INNER JOIN cgm ON cgm.z01_numcgm = empempenho.e60_numcgm
                    INNER JOIN empelemento ON empempenho.e60_numemp = empelemento.e64_numemp
                    INNER JOIN orcelemento elementoempenho ON elementoempenho.o56_codele = empelemento.e64_codele
                    AND elementoempenho.o56_anousu = empempenho.e60_anousu
                    INNER JOIN db_config ON db_config.codigo = empempenho.e60_instit
                    INNER JOIN orcdotacao ON orcdotacao.o58_anousu = empempenho.e60_anousu
                    AND orcdotacao.o58_coddot = empempenho.e60_coddot
                    INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = empempenho.e60_codcom
                    INNER JOIN emptipo ON emptipo.e41_codtipo = empempenho.e60_codtipo
                    INNER JOIN concarpeculiar ON concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar
                    INNER JOIN db_config AS a ON a.codigo = orcdotacao.o58_instit
                    INNER JOIN orctiporec ON orctiporec.o15_codigo = orcdotacao.o58_codigo
                    INNER JOIN orcfuncao ON orcfuncao.o52_funcao = orcdotacao.o58_funcao
                    INNER JOIN orcsubfuncao ON orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
                    INNER JOIN orcprograma ON orcprograma.o54_anousu = orcdotacao.o58_anousu
                    AND orcprograma.o54_programa = orcdotacao.o58_programa
                    INNER JOIN orcelemento ON orcelemento.o56_codele = orcdotacao.o58_codele
                    AND orcelemento.o56_anousu = orcdotacao.o58_anousu
                    INNER JOIN orcprojativ ON orcprojativ.o55_anousu = orcdotacao.o58_anousu
                    AND orcprojativ.o55_projativ = orcdotacao.o58_projativ
                    INNER JOIN orcorgao ON orcorgao.o40_anousu = orcdotacao.o58_anousu
                    AND orcorgao.o40_orgao = orcdotacao.o58_orgao
                    INNER JOIN orcunidade ON orcunidade.o41_anousu = orcdotacao.o58_anousu
                    AND orcunidade.o41_orgao = orcdotacao.o58_orgao
                    AND orcunidade.o41_unidade = orcdotacao.o58_unidade
                    LEFT JOIN empcontratos ON si173_empenho::varchar = e60_codemp
                    AND e60_anousu = si173_anoempenho
                    LEFT JOIN contratos ON si173_codcontrato = si172_sequencial
                    LEFT JOIN aditivoscontratos ON extract(YEAR
                                                           FROM si174_dataassinaturacontoriginal) = si172_exerciciocontrato
                    AND (si174_nrocontrato = si172_nrocontrato)
                    LEFT JOIN empempaut ON empempenho.e60_numemp = empempaut.e61_numemp
                    LEFT JOIN empautoriza ON empempaut.e61_autori = empautoriza.e54_autori
                    LEFT JOIN db_depart ON empautoriza.e54_autori = db_depart.coddepto
                    LEFT JOIN empempenhocontrato ON empempenho.e60_numemp = empempenhocontrato.e100_numemp
                    LEFT JOIN acordo ON empempenhocontrato.e100_acordo = acordo.ac16_sequencial
                    LEFT JOIN convconvenios ON convconvenios.c206_sequencial = empempenho.e60_numconvenio
                    LEFT JOIN empresto ON e91_numemp = e60_numemp
                    WHERE e60_instit = 1
                        AND (elementoempenho.o56_elemento IN ('3339030010000',
                                                              '3390330100000',
                                                              '3390339900000',
                                                              '3339033990000',
                                                              '3339030030000',
                                                              '3339092000000',
                                                              '3339033000000',
                                                              '3339093010000',
                                                              '3339093020000',
                                                              '3339093030000',
                                                              '3449030000000',
                                                              '3339039990000')
                             OR elementoempenho.o56_elemento LIKE '335041%')
                      AND e60_codemp = '{$codEmp}'
                      AND e60_anousu = $anoEmp
                    ORDER BY e60_emiss DESC
        ";

        return $sql;
    }

}
