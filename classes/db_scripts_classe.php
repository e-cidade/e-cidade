<?php

class cl_scripts
{

    var $erro_msg = null;
    var $erro = false;

    function cl_scripts()
    {
        $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
    }

    // funcões
    function alteraDocumento($lancamento, $proximodoc, $anteriordoc)
    {

        if (in_array($anteriordoc, array('200', '208', '210', '212', '214'))) {
            if (!in_array($proximodoc, array('200', '208', '210', '212', '214'))) {
                $this->erro_msg = "Se o lançamento contábil for do documento $anteriordoc, o sistema permitirá alterar para os documentos 200, 208, 210, 212, 214";
                $this->erro = true;
                return false;
            }
        }
        if (in_array($anteriordoc, array('201', '209', '211', '213', '215'))) {
            if (!in_array($proximodoc, array('201', '209', '211', '213', '215'))) {
                $this->erro_msg = "Se o lançamento contábil for do documento $anteriordoc, o sistema permitirá alterar para os documentos 209, 211, 213 e 215";
                $this->erro = true;
                return false;
            }
        }

        if (in_array($anteriordoc, array('39', '3', '23', '202', '204', '206', '33'))) {
            if (!in_array($proximodoc, array('39', '3', '23', '202', '204', '206', '33'))) {
                $this->erro_msg = "Se o lançamento contábil for do documento $anteriordoc, o sistema permitirá alterar para os documentos 39, 3, 23, 202, 204, 206, 33";
                $this->erro = true;
                return false;
            }
        }

        if (in_array($anteriordoc, array('40', '4', '24', '203', '205', '207', '34'))) {
            if (!in_array($proximodoc, array('40', '4', '24', '203', '205', '207', '34'))) {
                $this->erro_msg = "Se o lançamento contábil for do documento $anteriordoc, o sistema permitirá alterar para os documentos 40, 4, 24, 203, 205, 207, 34";
                $this->erro = true;
                return false;
            }
        }


        if (in_array($anteriordoc, array('160', '150', '130'))) {
            if (!in_array($proximodoc, array('160', '150', '130'))) {
                $this->erro_msg = "Se o lançamento contábil for do documento $anteriordoc, o sistema permitirá alterar para os documentos 160, 150 e 130";
                $this->erro = true;
                return false;
            }
        }

        if (in_array($anteriordoc, array('161', '151', '120'))) {
            if (!in_array($proximodoc, array('161', '151', '120'))) {
                $this->erro_msg = "Se o lançamento contábil for do documento $anteriordoc, o sistema permitirá alterar para os documentos 161, 151 e 120";
                $this->erro = true;
                return false;
            }
        }

        if (in_array($anteriordoc, array('162', '152', '131'))) {
            if (!in_array($proximodoc, array('162', '152', '131'))) {
                $this->erro_msg = "Se o lançamento contábil for do documento $anteriordoc, o sistema permitirá alterar para os documentos 152 e 131";
                $this->erro = true;
                return false;
            }
        }

        if (in_array($anteriordoc, array('163', '153', '121'))) {
            if (!in_array($proximodoc, array('163', '153', '121'))) {
                $this->erro_msg = "Se o lançamento contábil for do documento $anteriordoc, o sistema permitirá alterar para os documentos 163, 153 e 121";
                $this->erro = true;
                return false;
            }
        }

        if (in_array($anteriordoc, array('100', '101', '115', '116', '122', '123', '124', '125', '126', '127', '418', '419'))) {
            if (!in_array($proximodoc, array('100', '101', '115', '116', '122', '123', '124', '125', '126', '127', '418', '419'))) {
                $this->erro_msg = "Se o lançamento contábil for do documento $anteriordoc, o sistema permitirá alterar para os documentos 100,101,115,116,122,123,124,125,126,127,418 e 419";
                $this->erro = true;
                return false;
            }
        }

        $result = db_query("update conlancamdoc set c71_coddoc=$proximodoc where c71_codlan = '$lancamento'");

        if ($result === false) {
            $this->erro_msg = @pg_last_error();
            $this->erro = true;
            return false;
        }

        $this->erro_msg = "";
        $this->erro_msg = "Alteração efetuada com Sucesso\\n Aviso: é necessário realizar o reprocessamento do lançamento no Módulo Contabilidade - Procedimentos - Utilitários da Contabilidade - Processa Lançamentos";

        $resmanut = db_query("select nextval('db_manut_log_manut_sequencial_seq') as seq");
        $seq = pg_result($resmanut, 0, 0);
        $result = db_query("insert into db_manut_log values($seq,'Alt Documento: " . $anteriordoc . " para " . $proximodoc . " no lançamento " . $lancamento . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");

        return true;
    }

    function excluiEmpenho($seq_emp)
    {

        // ini_set('display_errors', 'On');
        // error_reporting(E_ALL);

        $ano = db_getsession('DB_anousu');
        $instit = db_getsession('DB_instit');
        $anousu = $ano . "-01-01";

        $resultOp = db_query("SELECT e50_codord FROM retencaopagordem
                          JOIN retencaoreceitas ON e23_retencaopagordem = e20_sequencial
                          JOIN retencaocorgrupocorrente ON e47_retencaoreceita = e23_sequencial
                          JOIN corgrupocorrente ON k105_sequencial = e47_corgrupocorrente
                          JOIN conlancamcorrente ON (c86_id, c86_data, c86_autent) = (k105_id, k105_data, k105_autent)
                          JOIN conlancamdoc ON c71_codlan = c86_conlancam
                          JOIN conlancam ON c71_codlan = c70_codlan
                          JOIN pagordem ON e20_pagordem = e50_codord
                          JOIN empempenho ON e50_numemp = e60_numemp
                          WHERE e60_numemp = $seq_emp
                            AND c71_coddoc IN (100, 101, 160, 162)
                          ORDER BY 1");

        if ($resultOp) {

            $aPagOrdem = pg_fetch_array($resultOp);

            foreach ($aPagOrdem as $pagordem) {

                $sqlExcluirOp = "CREATE TEMPORARY TABLE w_lancamentos ON COMMIT DROP AS
                             SELECT c70_codlan AS lancam FROM conlancam
                             WHERE c70_codlan IN
                                     (SELECT c80_codlan FROM conlancamdoc
                                      JOIN conlancamord ON c80_codlan = c71_codlan
                                      WHERE c71_coddoc IN
                                             (SELECT c53_coddoc FROM conhistdoc
                                              WHERE c53_tipo IN (30, 31, 11, 21, 414, 90, 92))
                                         AND c80_codlan NOT IN
                                             (SELECT e33_conlancam FROM pagordemdescontolanc
                                              WHERE e33_pagordemdesconto IN
                                                     (SELECT e34_sequencial FROM pagordemdesconto
                                                      WHERE e34_codord = {$pagordem}))
                                         AND c80_codord = {$pagordem});
                             
                             CREATE TEMPORARY TABLE w_chaves ON COMMIT DROP AS
                             SELECT k12_id AS id,
                                    k12_data AS DATA,
                                    k12_autent AS autent
                             FROM coremp
                             WHERE k12_codord = {$pagordem};
                             
                             INSERT INTO w_chaves
                             SELECT k105_id AS id,
                                    k105_data AS DATA,
                                    k105_autent AS autent
                             FROM corgrupocorrente
                             WHERE k105_sequencial IN
                                     (SELECT e47_corgrupocorrente FROM retencaocorgrupocorrente
                                      WHERE e47_retencaoreceita IN
                                             (SELECT e23_sequencial FROM retencaoreceitas
                                              WHERE e23_retencaopagordem IN
                                                     (SELECT e20_sequencial FROM retencaopagordem
                                                      WHERE e20_pagordem = {$pagordem})));
                             
                             DELETE FROM contacorrentedetalheconlancamval
                             USING w_lancamentos
                             WHERE c28_conlancamval IN
                                     (SELECT c69_sequen FROM conlancamval
                                      WHERE c69_codlan IN (SELECT lancam FROM w_lancamentos));
                             
                             DELETE FROM conlancamlr
                             USING w_lancamentos
                             WHERE c81_sequen IN
                                     (SELECT c69_sequen FROM conlancamval
                                      WHERE c69_codlan IN (SELECT lancam FROM w_lancamentos));
                             
                             DELETE FROM conlancamval
                             USING w_lancamentos
                             WHERE c69_codlan = lancam;
                             
                             DELETE FROM conlancamdoc
                             USING w_lancamentos
                             WHERE c71_codlan = lancam;
                             
                             DELETE FROM conlancamcgm
                             USING w_lancamentos
                             WHERE c76_codlan = lancam;
                             
                             DELETE FROM conlancamcorgrupocorrente
                             USING w_lancamentos
                             WHERE c23_conlancam = lancam ;
                             
                             DELETE FROM conlancamdot
                             USING w_lancamentos
                             WHERE c73_codlan = lancam;
                             
                             DELETE FROM conlancamele
                             USING w_lancamentos
                             WHERE c67_codlan = lancam;
                             
                             DELETE FROM conlancamord
                             USING w_lancamentos
                             WHERE c80_codlan = lancam;
                             
                             DELETE FROM conlancampag
                             USING w_lancamentos
                             WHERE c82_codlan = lancam;
                             
                             DELETE FROM conlancamemp
                             USING w_lancamentos
                             WHERE c75_codlan = lancam;
                             
                             DELETE FROM conlancamcompl
                             USING w_lancamentos
                             WHERE c72_codlan = lancam;
                             
                             DELETE FROM conlancamnota
                             USING w_lancamentos
                             WHERE c66_codlan = lancam;
                             
                             DELETE FROM pagordemdescontolanc
                             USING w_lancamentos
                             WHERE e33_conlancam = lancam;
                             
                             DELETE FROM conlancaminstit
                             USING w_lancamentos
                             WHERE c02_codlan = lancam;
                             
                             DELETE FROM conlancamordem
                             USING w_lancamentos
                             WHERE c03_codlan = lancam;
                             
                             DELETE FROM conlancam
                             USING w_lancamentos
                             WHERE c70_codlan = lancam;
                             
                             DELETE FROM retencaocorgrupocorrente
                             WHERE e47_retencaoreceita IN
                                     (SELECT e23_sequencial FROM retencaoreceitas
                                      WHERE e23_retencaopagordem IN
                                             (SELECT e20_sequencial FROM retencaopagordem
                                              WHERE e20_pagordem = {$pagordem}));
                             
                             DELETE FROM retencaoempagemov
                             WHERE e27_retencaoreceitas IN
                                     (SELECT e23_sequencial FROM retencaoreceitas
                                      WHERE e23_retencaopagordem IN
                                             (SELECT e20_sequencial FROM retencaopagordem
                                              WHERE e20_pagordem = {$pagordem}));
                             
                             DROP TABLE w_lancamentos;
                             
                             CREATE TEMPORARY TABLE w_lancamentos ON COMMIT DROP AS
                             SELECT c70_codlan AS lancam FROM conlancam
                             WHERE c70_codlan IN
                                     (SELECT c86_conlancam FROM conlancamcorrente
                                      JOIN w_chaves ON c86_id = id
                                      AND c86_data = DATA
                                      AND c86_autent = autent);
                             
                             DELETE FROM contacorrentedetalheconlancamval
                             USING w_lancamentos
                             WHERE c28_conlancamval IN
                                     (SELECT c69_sequen FROM conlancamval
                                      WHERE c69_codlan IN (SELECT lancam FROM w_lancamentos));
                             
                             DELETE FROM conlancamval
                             USING w_lancamentos
                             WHERE c69_codlan = lancam;
                             
                             DELETE FROM conlancamdoc
                             USING w_lancamentos
                             WHERE c71_codlan = lancam;
                             
                             DELETE FROM conlancamcgm
                             USING w_lancamentos
                             WHERE c76_codlan = lancam;
                             
                             DELETE FROM conlancamcorgrupocorrente
                             USING w_lancamentos
                             WHERE c23_conlancam = lancam ;
                             
                             DELETE FROM conlancamdot
                             USING w_lancamentos
                             WHERE c73_codlan = lancam;
                             
                             DELETE FROM conlancamele
                             USING w_lancamentos
                             WHERE c67_codlan = lancam;
                             
                             DELETE FROM conlancamord
                             USING w_lancamentos
                             WHERE c80_codlan = lancam;
                             
                             DELETE FROM conlancampag
                             USING w_lancamentos
                             WHERE c82_codlan = lancam;
                             
                             DELETE FROM conlancamemp
                             USING w_lancamentos
                             WHERE c75_codlan = lancam;
                             
                             DELETE FROM conlancamcompl
                             USING w_lancamentos
                             WHERE c72_codlan = lancam;
                             
                             DELETE FROM conlancamconcarpeculiar
                             USING w_lancamentos
                             WHERE c08_codlan = lancam;
                             
                             DELETE FROM conlancamcorrente
                             USING w_chaves
                             WHERE c86_id = id
                               AND c86_data = DATA
                               AND c86_autent = autent;
                             
                             DELETE FROM conlancamrec
                             USING w_lancamentos
                             WHERE c74_codlan = lancam;
                             
                             DELETE FROM conlancaminstit
                             USING w_lancamentos
                             WHERE c02_codlan = lancam;
                             
                             DELETE FROM conlancamordem
                             USING w_lancamentos
                             WHERE c03_codlan = lancam;
                             
                             DELETE FROM conlancam
                             USING w_lancamentos
                             WHERE c70_codlan = lancam;
                             
                             DELETE FROM corconf
                             USING w_chaves
                             WHERE k12_id = id
                               AND k12_data = DATA
                               AND k12_autent = autent;
                             
                             DELETE FROM corlanc
                             USING w_chaves
                             WHERE k12_id = id
                               AND k12_data = DATA
                               AND k12_autent = autent;
                             
                             DELETE FROM corempagemov 
                             USING w_chaves
                             WHERE k12_id = id
                               AND k12_data = DATA
                               AND k12_autent = autent;
                             
                             DELETE FROM coremp 
                             USING w_chaves
                             WHERE k12_id = id
                               AND k12_data = DATA
                               AND k12_autent = autent;
                             
                             DELETE FROM cornump 
                             USING w_chaves
                             WHERE k12_id = id
                               AND k12_data = DATA
                               AND k12_autent = autent;
                             
                             DELETE FROM retencaocorgrupocorrente
                             WHERE e47_corgrupocorrente IN
                                     (SELECT k105_sequencial FROM corgrupocorrente
                                      JOIN w_chaves ON k105_data = DATA AND k105_autent = autent AND k105_id = id);
                             
                             DELETE FROM corgrupocorrente 
                             USING w_chaves
                             WHERE k105_id = id
                               AND k105_data = DATA
                               AND k105_autent = autent;
                             
                             DELETE FROM corautent USING w_chaves
                             WHERE k12_id = id
                               AND k12_data = DATA
                               AND k12_autent = autent;
                             
                             DELETE FROM corhist USING w_chaves
                             WHERE k12_id = id
                               AND k12_data = DATA
                               AND k12_autent = autent;
                             
                             DELETE FROM corrente USING w_chaves
                             WHERE k12_id = id
                               AND k12_data = DATA
                               AND k12_autent = autent;
                             
                             CREATE TEMPORARY TABLE w_mov ON COMMIT DROP AS
                             SELECT e82_codmov FROM empord
                             WHERE e82_codord = {$pagordem};
                             
                             DELETE FROM corconf
                             WHERE k12_codmov IN
                                     (SELECT e91_codcheque FROM empageconfche
                                      WHERE e91_codmov IN (SELECT e82_codmov FROM w_mov));
                             
                             DELETE FROM empageconfche
                             WHERE e91_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM retencaoempagemov
                             WHERE e27_empagemov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empageconfgera
                             WHERE e90_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM corempagemov
                             WHERE k12_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empagenotasordem
                             WHERE e43_empagemov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empagemovslips
                             WHERE k107_empagemov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empagemovconta
                             WHERE e98_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM emppresta
                             WHERE e45_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empord
                             WHERE e82_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empageconf
                             WHERE e86_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empagemovforma
                             WHERE e97_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empagepag
                             WHERE e85_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empageconcarpeculiar
                             WHERE e79_empagemov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empagemovtipotransmissao
                             WHERE e25_empagemov IN (SELECT e82_codmov FROM w_mov);
                             
                             DELETE FROM empagemov
                             WHERE e81_codmov IN (SELECT e82_codmov FROM w_mov);
                             
                             DROP TABLE w_lancamentos;
                             DROP TABLE w_chaves;
                             DROP TABLE w_mov;";

                $rsExluirOp = db_query($sqlExcluirOp);


                if ($rsExluirOp == false) {
                    echo "<script> alert('Houve um erro ao excluir o pagamento!');</script>";
                    db_redireciona('m4_empenhos.php');
                }

            }
        }

        $result = db_query("CREATE TEMP TABLE data_final ON COMMIT DROP AS
                        SELECT * FROM condataconf
                        WHERE c99_anousu = $ano
                            AND c99_instit = $instit;

                        UPDATE condataconf
                        SET c99_data = '$anousu'
                        WHERE c99_anousu = $ano
                            AND c99_instit = $instit;


                        CREATE TEMP TABLE reduzidos_lanc ON COMMIT DROP AS
                        SELECT * FROM conplanoexesaldo
                        WHERE c68_anousu = $ano
                            AND c68_mes IN
                                (SELECT EXTRACT (MONTH FROM c69_data) AS c69_mes
                                FROM conlancamval
                                INNER JOIN conlancamemp ON c69_codlan = c75_codlan
                                AND c75_numemp = $seq_emp)
                            AND c68_reduz IN
                                (SELECT c69_credito FROM conlancamval
                                INNER JOIN conlancamemp ON c69_codlan = c75_codlan
                                AND c75_numemp = $seq_emp
                                UNION ALL
                                SELECT c69_debito FROM conlancamval
                                INNER JOIN conlancamemp ON c69_codlan = c75_codlan
                                AND c75_numemp = $seq_emp);

                        CREATE TEMP TABLE anula_emp ON COMMIT DROP AS
                        SELECT * FROM empempenho
                        WHERE e60_numemp = $seq_emp ;

                        CREATE TEMP TABLE empenhos ON COMMIT DROP AS
                        SELECT * FROM empempenho
                        WHERE e60_anousu = $ano
                        AND e60_numemp = $seq_emp;

                        CREATE TEMP TABLE autoriza ON COMMIT DROP AS
                            (SELECT * FROM empempaut
                            WHERE e61_numemp IN
                            (SELECT e60_numemp FROM empenhos));




                        CREATE TEMPORARY TABLE w_matordem ON COMMIT DROP AS
                        SELECT m52_codordem AS m51_codordem
                        FROM matordemitem
                        WHERE m52_numemp = $seq_emp;

                        DELETE FROM matordemanu
                        WHERE m53_codordem IN
                            (SELECT m51_codordem
                            FROM w_matordem);

                        DELETE FROM matordemitemanu
                        WHERE m36_matordemitem IN
                            (SELECT m52_codlanc
                            FROM matordemitem
                            WHERE m52_codordem IN
                                (SELECT m51_codordem
                                FROM w_matordem));

                        DELETE FROM matestoqueitemoc
                        WHERE m73_codmatordemitem IN
                            (SELECT m52_codlanc
                            FROM matordemitem
                            WHERE m52_codordem IN
                                (SELECT m51_codordem
                                FROM w_matordem));

                        DELETE FROM matordemitem
                        WHERE m52_codordem IN
                            (SELECT m51_codordem
                            FROM w_matordem);

                        DELETE FROM empnotaord
                        WHERE m72_codordem IN
                            (SELECT m51_codordem
                            FROM w_matordem);

                        DELETE FROM protmatordem
                        WHERE p104_codordem IN
                                (SELECT m51_codordem
                                FROM w_matordem);

                        DELETE FROM matordem
                        WHERE m51_codordem IN
                            (SELECT m51_codordem
                            FROM w_matordem);



                        CREATE TEMPORARY TABLE w_lancamentos ON COMMIT DROP AS
                        SELECT c70_codlan AS lancam
                        FROM conlancam
                        WHERE c70_codlan IN
                            (SELECT c75_codlan FROM conlancamemp
                            WHERE c75_numemp = $seq_emp);

                        DELETE FROM conlancamcgm
                        WHERE c76_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamemp
                        WHERE c75_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamdot
                        WHERE c73_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamcompl
                        WHERE c72_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamdoc
                        WHERE c71_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM contacorrentedetalheconlancamval
                        WHERE c28_conlancamval IN
                            (SELECT c69_sequen
                            FROM conlancamval
                            WHERE c69_codlan IN
                                (SELECT lancam
                                FROM w_lancamentos));

                        DELETE FROM conlancamval
                        WHERE c69_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamele
                        WHERE c67_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamnota
                        WHERE c66_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancampag
                        WHERE c82_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamord
                        WHERE c80_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamcorgrupocorrente
                        WHERE c23_conlancam IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM pagordemdescontolanc
                        WHERE e33_conlancam IN
                            (SELECT lancam
                            FROM w_lancamentos);


                        DELETE FROM conlancamsup
                        WHERE c79_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamconcarpeculiar
                        WHERE c08_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancaminstit
                        WHERE c02_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamordem
                        WHERE c03_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancamacordo
                        WHERE c87_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);

                        DELETE FROM conlancam
                        WHERE c70_codlan IN
                            (SELECT lancam
                            FROM w_lancamentos);



                        CREATE TEMP TABLE w_notas ON COMMIT DROP AS
                        SELECT * FROM empnota
                        WHERE e69_numemp = $seq_emp;

                        DELETE FROM empnotaele
                        WHERE e70_codnota IN
                            (SELECT e69_codnota
                            FROM w_notas);

                        DELETE
                        FROM empnotaitembenspendente
                        WHERE e137_empnotaitem IN
                            (SELECT e72_sequencial
                            FROM empnotaitem
                            WHERE e72_codnota IN
                                (SELECT e69_codnota
                                FROM w_notas));

                        DELETE FROM empnotaitem
                        WHERE e72_codnota IN
                            (SELECT e69_codnota
                            FROM w_notas);

                        DELETE FROM empnotaord
                        WHERE m72_codnota IN
                            (SELECT e69_codnota
                            FROM w_notas);

                        DELETE FROM matestoqueitemnota
                        WHERE m74_codempnota IN
                            (SELECT e69_codnota
                            FROM w_notas);



                        CREATE TEMP TABLE w_empenhos ON COMMIT DROP AS
                        SELECT * FROM empempenho
                        WHERE e60_numemp = $seq_emp;

                        DELETE FROM empelemento
                        WHERE e64_numemp IN
                            (SELECT e60_numemp
                            FROM w_empenhos);

                        DELETE FROM empempaut
                        WHERE e61_numemp IN
                            (SELECT e60_numemp
                            FROM w_empenhos);

                        DELETE FROM empempenhonl
                        WHERE e68_numemp IN
                            (SELECT e60_numemp
                            FROM w_empenhos);

                        DELETE FROM empemphist
                        WHERE e63_numemp IN
                            (SELECT e60_numemp
                            FROM w_empenhos);

                        DELETE FROM empanuladoitem
                        WHERE e37_empanulado IN
                            (SELECT e94_codanu
                            FROM empanulado
                            WHERE e94_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM empempitem
                        WHERE e62_numemp IN
                            (SELECT e60_numemp
                            FROM w_empenhos);

                        DELETE FROM empanuladoele
                        WHERE e95_codanu IN
                            (SELECT e94_codanu
                            FROM empanulado
                            WHERE e94_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM empanulado
                        WHERE e94_numemp IN
                            (SELECT e60_numemp
                            FROM w_empenhos);

                        DELETE FROM empord
                        WHERE e82_codord IN
                            (SELECT e50_codord
                            FROM pagordem
                            WHERE e50_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM pagordemnota
                        WHERE e71_codord IN
                            (SELECT e50_codord
                            FROM pagordem
                            WHERE e50_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM pagordemele
                        WHERE e53_codord IN
                            (SELECT e50_codord
                            FROM pagordem
                            WHERE e50_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM cairetordem
                        WHERE k32_ordpag IN
                            (SELECT e50_codord
                            FROM pagordem
                            WHERE e50_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM issplanitop
                        WHERE q96_pagordem IN
                            (SELECT e50_codord
                            FROM pagordem
                            WHERE e50_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM retencaocorgrupocorrente where e47_retencaoreceita in (SELECT e23_sequencial FROM retencaoreceitas
                        WHERE e23_retencaopagordem IN
                            (SELECT e20_sequencial
                            FROM retencaopagordem
                            WHERE e20_pagordem IN
                                (SELECT e50_codord
                                FROM pagordem
                                WHERE e50_numemp IN
                                    (SELECT e60_numemp
                                    FROM w_empenhos))));

                        DELETE FROM retencaoempagemov
                        WHERE e27_retencaoreceitas IN
                            (SELECT e23_sequencial
                            FROM retencaoreceitas
                            WHERE e23_retencaopagordem IN
                                (SELECT e20_sequencial
                                FROM retencaopagordem
                                WHERE e20_pagordem IN
                                    (SELECT e50_codord
                                    FROM pagordem
                                    WHERE e50_numemp IN
                                        (SELECT e60_numemp
                                            FROM w_empenhos))));

                        DELETE FROM retencaoreceitas
                        WHERE e23_retencaopagordem IN
                            (SELECT e20_sequencial
                            FROM retencaopagordem
                            WHERE e20_pagordem IN
                                (SELECT e50_codord
                                FROM pagordem
                                WHERE e50_numemp IN
                                    (SELECT e60_numemp
                                    FROM w_empenhos)));

                        DELETE FROM retencaopagordem
                        WHERE e20_pagordem IN
                            (SELECT e50_codord
                            FROM pagordem
                            WHERE e50_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM pagordemdesconto
                        WHERE e34_codord IN
                                (SELECT e50_codord
                                FROM pagordem
                                WHERE e50_numemp IN
                                        (SELECT e60_numemp
                                        FROM w_empenhos));

                        DELETE FROM protpagordem
                        WHERE p105_codord IN
                                (SELECT e50_codord
                                FROM pagordem
                                WHERE e50_numemp IN
                                        (SELECT e60_numemp
                                        FROM w_empenhos));

                        DELETE FROM autprotpagordem
                        WHERE p107_codord IN
                            (SELECT e50_codord
                            FROM pagordem
                            WHERE e50_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM pagordemconta
                        WHERE e49_codord IN
                            (SELECT e50_codord
                            FROM pagordem
                            WHERE e50_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));                 

                        DELETE FROM pagordem
                        WHERE e50_codord IN
                            (SELECT e50_codord
                            FROM pagordem
                            WHERE e50_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos));

                        DELETE FROM contacorrentesaldo
                            WHERE c29_contacorrentedetalhe IN
                                (SELECT c19_sequencial
                                FROM contacorrentedetalhe
                                WHERE c19_numemp IN
                                    (SELECT e60_numemp
                                        FROM w_empenhos));

                        DELETE FROM contacorrentedetalheconlancamval
                        WHERE c28_contacorrentedetalhe IN
                        (SELECT c19_sequencial FROM contacorrentedetalhe 
                            WHERE c19_numemp IN
                                (SELECT e60_numemp
                                    FROM w_empenhos));

                        DELETE FROM contacorrentedetalhe
                            WHERE c19_numemp IN
                                (SELECT e60_numemp
                                    FROM w_empenhos);

                        DELETE FROM rhempenhofolhaempenho
                            WHERE rh76_numemp IN
                                (SELECT e60_numemp
                                    FROM w_empenhos);

                        DELETE FROM empempenhofinalidadepagamentofundeb
                        WHERE e152_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos);

                        DELETE FROM empnota
                        WHERE e69_numemp = $seq_emp;

                        DELETE FROM empempenhocontrato
                        WHERE e100_numemp IN
                            (SELECT e60_numemp
                            FROM w_empenhos);

                        DELETE FROM coremp
                        WHERE k12_empen IN
                                (SELECT e60_numemp
                                FROM w_empenhos);

                        DELETE FROM protempenhos
                        WHERE p103_numemp IN
                                (SELECT e60_numemp
                                FROM w_empenhos);

                        DELETE FROM empenhocotamensal
                        WHERE e05_numemp IN
                            (SELECT e60_numemp
                            FROM w_empenhos);

                        DELETE FROM empempenho
                        WHERE e60_numemp IN
                            (SELECT e60_numemp
                            FROM w_empenhos);

                        DELETE FROM empprestarecibo
                        WHERE e170_emppresta IN
                                (SELECT e45_sequencial FROM emppresta
                                WHERE e45_numemp IN
                                        (SELECT e60_numemp FROM w_empenhos));

                        DELETE FROM empprestaitem
                        WHERE e46_emppresta IN
                                (SELECT e45_sequencial FROM emppresta
                                WHERE e45_numemp IN
                                        (SELECT e60_numemp FROM w_empenhos));

                        DELETE FROM emppresta
                        WHERE e45_numemp IN
                                (SELECT e60_numemp FROM w_empenhos);



                        DELETE FROM empautidot
                        WHERE e56_autori IN
                            (SELECT e61_autori
                            FROM autoriza);

                        DELETE FROM empautitempcprocitem
                        WHERE e73_autori IN
                            (SELECT e61_autori
                            FROM autoriza);

                        DELETE FROM acordoitemexecutadoempautitem
                        WHERE ac19_autori IN
                            (SELECT e61_autori
                            FROM autoriza);

                        DELETE FROM empautitem
                        WHERE e55_autori IN
                                (SELECT e61_autori
                                    FROM autoriza);

                        DELETE FROM empempaut
                        WHERE e61_autori IN
                            (SELECT e61_autori
                            FROM autoriza);

                        DELETE FROM empauthist
                        WHERE e57_autori IN
                            (SELECT e61_autori
                            FROM autoriza);

                        DELETE FROM acordoempautoriza
                        WHERE ac45_empautoriza IN
                            (SELECT e61_autori
                            FROM autoriza);

                        DELETE FROM protempautoriza
                        WHERE p102_autorizacao IN
                                (SELECT e61_autori
                                FROM autoriza);

                        DELETE FROM empautorizaprocesso
                        WHERE e150_empautoriza IN
                                (SELECT e61_autori
                                FROM autoriza);

                        DELETE FROM empautoriza
                        WHERE e54_autori IN
                                (SELECT e61_autori
                                    FROM autoriza);



                        DELETE FROM conplanoexesaldo
                        USING reduzidos_lanc
                        WHERE (reduzidos_lanc.c68_reduz, reduzidos_lanc.c68_anousu, reduzidos_lanc.c68_mes) = (conplanoexesaldo.c68_reduz, conplanoexesaldo.c68_anousu, conplanoexesaldo.c68_mes);

                        CREATE TEMP TABLE landeb ON COMMIT DROP AS
                        SELECT c69_anousu,
                            c69_debito,
                            to_char(c69_data,'MM')::integer,
                            sum(round(c69_valor,2)),0::float8
                        FROM conlancamval
                        JOIN reduzidos_lanc ON (c69_debito, c69_anousu, EXTRACT (MONTH FROM c69_data)) = (c68_reduz, c68_anousu, c68_mes)
                        GROUP BY c69_anousu, c69_debito, to_char(c69_data,'MM')::integer;

                        CREATE TEMP TABLE lancre ON COMMIT DROP AS
                        SELECT c69_anousu,
                            c69_credito,
                            to_char(c69_data,'MM')::integer,
                            0::float8,
                            sum(round(c69_valor,2))
                        FROM conlancamval
                        JOIN reduzidos_lanc ON (c69_credito, c69_anousu, EXTRACT (MONTH FROM c69_data)) = (c68_reduz, c68_anousu, c68_mes)
                        GROUP BY c69_anousu, c69_credito, to_char(c69_data,'MM')::integer;

                        INSERT INTO conplanoexesaldo
                        SELECT * FROM landeb;

                        UPDATE conplanoexesaldo
                        SET c68_credito = lancre.sum
                        FROM lancre
                        WHERE c68_anousu = lancre.c69_anousu
                            AND c68_reduz = lancre.c69_credito
                            AND c68_mes = lancre.to_char
                            AND c68_anousu = $ano;

                        DELETE FROM lancre
                        USING conplanoexesaldo
                        WHERE lancre.c69_anousu = conplanoexesaldo.c68_anousu
                            AND conplanoexesaldo.c68_reduz = lancre.c69_credito
                            AND conplanoexesaldo.c68_mes = lancre.to_char
                            AND c68_anousu = $ano;

                        INSERT INTO conplanoexesaldo
                        SELECT * FROM lancre
                        WHERE c69_anousu = $ano;

                        UPDATE condataconf
                        SET c99_data = (SELECT data_final.c99_data FROM data_final)
                        WHERE c99_anousu = $ano
                        AND c99_instit = $instit");


        if ($result === false) {
            echo @pg_last_error();
            exit;
        }

        $this->erro_msg = "";
        $this->erro_msg = "Exclusão efetuada com Sucesso\\n";

        $resmanut = db_query("select nextval('db_manut_log_manut_sequencial_seq') as seq");
        $seq = pg_result($resmanut, 0, 0);
        $result = db_query("insert into db_manut_log values($seq,'Empenho: " . $seq_emp . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");

        return true;

    }

    function alteraDataLancAnulEmp($iCodigoLancamento, $dtData) {

        require_once "db_conlancam_classe.php";
        require_once "db_conlancamval_classe.php";
        require_once "db_contacorrentedetalheconlancamval_classe.php";
        require_once "db_conlancamlr_classe.php";
        require_once "db_conlancamcgm_classe.php";
        require_once "db_conlancamdig_classe.php";
        require_once "db_conlancamdoc_classe.php";
        require_once "db_conlancamdot_classe.php";
        require_once "db_conlancamemp_classe.php";
        require_once "db_conlancamord_classe.php";
        require_once "db_conlancamrec_classe.php";
        require_once "db_conlancamretif_classe.php";
        require_once "db_conlancamsup_classe.php";
        require_once "db_empanulado_classe.php";

        $oConlancam       = new cl_conlancam();
        $oDaoConlancamVal = new cl_conlancamval();
        $oDaoConlancamlr  = new cl_conlancamlr();
        $oConlancamcgm    = new cl_conlancamcgm();
        $oConlancamdig    = new cl_conlancamdig();
        $oConlancamdoc    = new cl_conlancamdoc();
        $oConlancamdot    = new cl_conlancamdot();
        $oConlancamemp    = new cl_conlancamemp();
        $oConlancamord    = new cl_conlancamord();
        $oConlancamrec    = new cl_conlancamrec();
        $oConlancamretif  = new cl_conlancamretif();
        $oConlancamsup    = new cl_conlancamsup();
        $oEmpanulado      = new cl_empanulado();

        if (empty($iCodigoLancamento)) {
            throw new Exception("Informe o codigo do lançamento");
        }

        $oConlancam->c70_codlan = $iCodigoLancamento;
        $oConlancam->c70_data   = $dtData;
        $oConlancam->alterar($iCodigoLancamento);
        if ($oConlancam->erro_status == 0) {
            throw new Exception("Erro [1] - Erro ao alterar a data do lançamento contábil.");
        }

        $sSqlLancamentos  = $oDaoConlancamVal->sql_query_file(null,"*", null, "c69_codlan = {$iCodigoLancamento}");
        $rsLancamentos    = $oDaoConlancamVal->sql_record($sSqlLancamentos);
        if (pg_num_rows($rsLancamentos) > 0) {

            $aLancamentos = db_utils::getCollectionByRecord($rsLancamentos);
            foreach ($aLancamentos as $oLancamento) {

                $oDaoContaCorrenteDetalheValor = new cl_contacorrentedetalheconlancamval();

                /**
                 * Tratamento para os dados da ContaCorrente
                 */
                $sWhereExcluirContaCorrenteDetalhe = "c28_conlancamval = {$oLancamento->c69_sequen}";
                $sSqlBuscaContaCorrente = $oDaoContaCorrenteDetalheValor->sql_query_file(null, "*", null, $sWhereExcluirContaCorrenteDetalhe);
                $rsBuscaContaCorrente   = $oDaoContaCorrenteDetalheValor->sql_record($sSqlBuscaContaCorrente);
                if (pg_num_rows($rsBuscaContaCorrente) == 0) {
                    throw new Exception("Não foi possível buscar dados do Conta Corrente.");
                }
                $aDadosContaCorrente = array();
                if (pg_num_rows($rsBuscaContaCorrente) > 0) {
                    $aDadosContaCorrente = db_utils::getCollectionByRecord($rsBuscaContaCorrente);
                }
                $oDaoContaCorrenteDetalheValor->excluir(null, $sWhereExcluirContaCorrenteDetalhe);
                if ($oDaoContaCorrenteDetalheValor->erro_status == 0) {
                    throw new Exception("Não foi possível excluir dados do Conta Corrente.");
                }

                $sSqlLancamentoConfig = $oDaoConlancamlr->sql_query_file($oLancamento->c69_sequen);
                $rsLancamentosConfig  = $oDaoConlancamlr->sql_record($sSqlLancamentoConfig);
                $aLancamentosConfig   = db_utils::getCollectionByRecord($rsLancamentosConfig);
                $oDaoConlancamlr->excluir($oLancamento->c69_sequen);
                if ($oDaoConlancamlr->erro_status == 0) {
                    throw new Exception("Erro [2] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.");
                }

                $oDaoConlancamVal->excluir($oLancamento->c69_sequen);
                if ($oDaoConlancamVal->erro_status == 0) {

                    $sErroMensagem  = "Erro [3] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.\n";
                    $sErroMensagem .= $oDaoConlancamVal->erro_banco;
                    throw new Exception($sErroMensagem);
                }

                $oDaoConlancamVal->c69_sequen  = $oLancamento->c69_sequen;
                $oDaoConlancamVal->c69_anousu  = $oLancamento->c69_anousu;
                $oDaoConlancamVal->c69_codhist = $oLancamento->c69_codhist;
                $oDaoConlancamVal->c69_codlan  = $oLancamento->c69_codlan;
                $oDaoConlancamVal->c69_credito = $oLancamento->c69_credito;
                $oDaoConlancamVal->c69_data    = $dtData;
                $oDaoConlancamVal->c69_debito  = $oLancamento->c69_debito;
                $oDaoConlancamVal->c69_valor   = $oLancamento->c69_valor;
                $oDaoConlancamVal->incluir($oLancamento->c69_sequen);
                if ($oDaoConlancamVal->erro_status == 0) {

                    $sErroMsg  = "Erro [4] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.";
                    $sErroMsg .= "\nErro Técnico:{$oDaoConlancamVal->erro_msg}";
                    throw new Exception($sErroMsg);
                }

                /**
                 * Configuramos a conta corrente novamente.
                 */
                foreach ($aDadosContaCorrente as $oStdContaCorrente) {

                    $oDaoContaCorrenteDetalheValor = new cl_contacorrentedetalheconlancamval();

                    $oDaoContaCorrenteDetalheValor->c28_sequencial           = $oStdContaCorrente->c28_sequencial;
                    $oDaoContaCorrenteDetalheValor->c28_contacorrentedetalhe = $oStdContaCorrente->c28_contacorrentedetalhe;
                    $oDaoContaCorrenteDetalheValor->c28_conlancamval         = $oStdContaCorrente->c28_conlancamval;
                    $oDaoContaCorrenteDetalheValor->c28_tipo                 = $oStdContaCorrente->c28_tipo;
                    $oDaoContaCorrenteDetalheValor->incluir($oStdContaCorrente->c28_sequencial);
                    if ($oDaoContaCorrenteDetalheValor->erro_status == 0) {
                        throw new Exception("Não foi possível inserir os dados para a conta corrente {$oStdContaCorrente->c28_contacorrentedetalhe}. Contate o Suporte.");
                    }
                }

                foreach ($aLancamentosConfig as $oLancamentoConfig) {

                    $oDaoConlancamlr->c81_sequen     = $oDaoConlancamVal->c69_sequen;
                    $oDaoConlancamlr->c81_seqtranslr = $oLancamentoConfig->c81_seqtranslr;
                    $oDaoConlancamlr->incluir($oDaoConlancamVal->c69_sequen, $oLancamentoConfig->c81_seqtranslr);
                    if ($oDaoConlancamVal->erro_status == 0) {
                        throw new Exception("Erro [5] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.");
                    }
                }
            }
        }

        $oConlancamcgm->c76_codlan = $iCodigoLancamento;
        $oConlancamcgm->c76_data   = $dtData;
        $oConlancamcgm->alterar($iCodigoLancamento);
        if ($oConlancamcgm->erro_status == 0) {
            throw new Exception("Erro [6] - Erro ao alterar a data do lançamento contábil.");
        }

        $oConlancamdig->c78_codlan = $iCodigoLancamento;
        $oConlancamdig->c78_data   = $dtData;
        $oConlancamdig->alterar($iCodigoLancamento);
        if ($oConlancamdig->erro_status == 0) {
            throw new Exception("Erro [7] - Erro ao alterar a data do lançamento contábil.");
        }

        $oConlancamdoc->c71_codlan = $iCodigoLancamento;
        $oConlancamdoc->c71_data   = $dtData;
        $oConlancamdoc->alterar($iCodigoLancamento);
        if ($oConlancamdoc->erro_status == 0) {
            throw new Exception("Erro [8] - Erro ao alterar a data do lançamento contábil.");
        }

        $oConlancamdot->c73_codlan = $iCodigoLancamento;
        $oConlancamdot->c73_data   = $dtData;
        $oConlancamdot->alterar($iCodigoLancamento);
        if ($oConlancamdot->erro_status == 0) {
            throw new Exception("Erro [9] - Erro ao alterar a data do lançamento contábil.");
        }

        $sqlBuscaEmpenho  = $oConlancamemp->sql_query_dadoslancamento($iCodigoLancamento, "conlancamemp.*, conlancam.*", null, "");
        $rsBuscaEmpenho   = $oConlancamemp->sql_record($sqlBuscaEmpenho);
        if (pg_num_rows($rsBuscaEmpenho) == 0) {
            throw new Exception("Não foi possível buscar os dados para alterar anulação do empenho.");
        }
        $oConlancamemp->c75_codlan = $iCodigoLancamento;
        $oConlancamemp->c75_data   = $dtData;
        $oConlancamemp->alterar($iCodigoLancamento);
        if ($oConlancamemp->erro_status == 0) {
            throw new Exception("Erro [10] - Erro ao alterar a data do lançamento contábil.");
        }

        $oConlancamord->c80_codlan = $iCodigoLancamento;
        $oConlancamord->c80_data   = $dtData;
        $oConlancamord->alterar($iCodigoLancamento);
        if ($oConlancamord->erro_status == 0) {
            throw new Exception("Erro [11] - Erro ao alterar a data do lançamento contábil.");
        }

        $oConlancamrec->c74_codlan = $iCodigoLancamento;
        $oConlancamrec->c74_data   = $dtData;
        $oConlancamrec->alterar($iCodigoLancamento);
        if ($oConlancamrec->erro_status == 0) {
            throw new Exception("Erro [12] - Erro ao alterar a data do lançamento contábil.");
        }

        $oConlancamretif->c79_codlan = $iCodigoLancamento;
        $oConlancamretif->c79_data   = $dtData;
        $oConlancamretif->alterar($iCodigoLancamento);
        if ($oConlancamretif->erro_status == 0) {
            throw new Exception("Erro [13] - Erro ao alterar a data do lançamento contábil.");
        }

        $oConlancamsup->c79_codlan = $iCodigoLancamento;
        $oConlancamsup->c79_data   = $dtData;
        $oConlancamsup->alterar($iCodigoLancamento);
        if ($oConlancamsup->erro_status == 0) {
            throw new Exception("Erro [14] - Erro ao alterar a data do lançamento contábil.");
        }

        if (pg_num_rows($rsBuscaEmpenho) > 0) {
            $aDadosBuscaEmpenho = db_utils::getCollectionByRecord($rsBuscaEmpenho);

            foreach ($aDadosBuscaEmpenho as $oEmpenho) {

                $whereAnulacaoEmpenho = "(e94_numemp, e95_valor, e94_data) = ($oEmpenho->c75_numemp, $oEmpenho->c70_valor, '$oEmpenho->c75_data')";
                $campos = "DISTINCT empanulado.*";
                $rsBuscaAnulacaoEmp = db_query($oEmpanulado->sql_query_anulacaoEmp(null, $campos, null, $whereAnulacaoEmpenho));
                $aDadosAnulacaoEmp = db_utils::getCollectionByRecord($rsBuscaAnulacaoEmp);

                foreach ($aDadosAnulacaoEmp as $oDadosAnulacaoEmp):

                    $oEmpanulado->e94_data   = $dtData;
                    $oEmpanulado->e94_codanu = $oDadosAnulacaoEmp->e94_codanu;
                    $oEmpanulado->alterar($oDadosAnulacaoEmp->e94_codanu);
                    if ($oEmpanulado->erro_status == 0) {
                        throw new Exception("Erro [15] - Erro ao alterar a data da anulação do empenho.");
                    }
                endforeach;

                $aDadosLancamentos = db_utils::getCollectionByRecord($rsLancamentos);
                $dtAnterior = date('d/m/Y', strtotime($oEmpenho->c75_data));
                $dtAtual = date('d/m/Y', strtotime($dtData));
                $descricao = "Alt. Dt Anul Emp: $oEmpenho->c75_numemp - Lanc $oEmpenho->c75_codlan | Dt Anterior: $dtAnterior - Dt Atual: $dtAtual.";
                $this->ajustaSaldoContasLancamento($aDadosLancamentos, $dtData, $descricao);

            }

            $aSaldoEmp = $this->verificaSaldoEmp($aDadosBuscaEmpenho[0]->c75_numemp, $dtData);
            if ($aSaldoEmp[0]->saldo_empenho < 0){
                throw new Exception("Não será possível alterar para essa data, pois o saldo do empenho ficaria negativo. Revise!");
            }
        }
    }

    function excluiAnulacaoEmp($iCodigoLancamento)
    {
        require_once "db_conlancamval_classe.php";
        require_once "db_conlancamemp_classe.php";
        $oDaoConlancamVal = new cl_conlancamval();
        $oConlancamemp = new cl_conlancamemp();


        $sSqlLancamentos  = $oDaoConlancamVal->sql_query_file(null,"*", null, "c69_codlan = {$iCodigoLancamento}");
        $rsLancamentos    = $oDaoConlancamVal->sql_record($sSqlLancamentos);

        $sqlBuscaEmpenho  = $oConlancamemp->sql_query_dadoslancamento($iCodigoLancamento, "conlancamemp.*, conlancam.*, e60_coddot", null, "");
        $rsBuscaEmpenho   = $oConlancamemp->sql_record($sqlBuscaEmpenho);
        if (pg_num_rows($rsBuscaEmpenho) == 0) {
            throw new Exception("Não foi possível buscar os dados para excluir anulação do empenho.");
        }

        $docValidos = array(2, 32);
        $dbwhere = "c75_numemp in (SELECT c75_numemp FROM conlancamemp WHERE c75_codlan = $iCodigoLancamento)";
        $dbwhere .= " and c53_tipo = 11";
        $sqlDocAnulEmp = $oConlancamemp->sql_query_documentos(null, "c71_coddoc", "1", $dbwhere);
        $rsDocAnulEmp = $oConlancamemp->sql_record($sqlDocAnulEmp);
        $aDocAnulEmp = db_utils::getCollectionByRecord($rsDocAnulEmp);

        foreach ($aDocAnulEmp as $oDocAnulEmp) {
            if (!in_array($oDocAnulEmp->c71_coddoc, $docValidos)){
                throw new Exception("Não é possível excluir anulações desse tipo de empenho.");
            }
        }

        $aDadosLancamentos = db_utils::getCollectionByRecord($rsLancamentos);
        $aDadosLancEmpenho = db_utils::getCollectionByRecord($rsBuscaEmpenho);

        $vlrAnulacao = number_format($aDadosLancamentos[0]->c69_valor, 2);

        $rsDotacaoSaldo = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot = {$aDadosLancEmpenho[0]->e60_coddot}", db_getsession("DB_anousu"));
        $oDotacaoSaldo  = db_utils::fieldsMemory($rsDotacaoSaldo, 0);
        $saldoAtual = number_format($oDotacaoSaldo->atual_menos_reservado, 2);

        $dtAtual = date('d/m/Y', strtotime($aDadosLancamentos[0]->c69_data));
        $numemp = $aDadosLancEmpenho[0]->c75_numemp;
        $descricao = "Exclusao Anul Emp: $numemp - Lanc: $iCodigoLancamento | Dt anul: $dtAtual | Vlr anul: $vlrAnulacao.";

        $validaRp = $aDadosLancEmpenho[0]->e60_anousu < db_getsession("DB_anousu");
        $validaSaldo = $saldoAtual - $vlrAnulacao;

        if (($validaSaldo < 0) && !$validaRp){
            throw new Exception("O lançamento não pode ser removido pois a dotação ficará com saldo negativo.\nProcedimento abortado.");
        }

        $sqlExcluiAnulacoesEmp = "
                    /* Defina os valores da variável */
                    DO $$
                    DECLARE
                        valor FLOAT;
                        seq_numemp INTEGER;
                        cod_lancamento INTEGER;
                    BEGIN
                        -- 'Informacoes temporarias de valor e numemp do lancamento.
                    
                        SELECT c70_valor, c75_numemp, c70_codlan
                        INTO valor, seq_numemp, cod_lancamento
                        FROM conlancam
                        JOIN conlancamemp ON c75_codlan = c70_codlan
                        WHERE c70_codlan = $iCodigoLancamento;
                    
                        /* 'Removendo os lancamentos.' */
                    
                        DELETE FROM conlancamemp WHERE c75_codlan = cod_lancamento;
                        DELETE FROM conlancambol WHERE c77_codlan = cod_lancamento;
                        DELETE FROM conlancamcgm WHERE c76_codlan = cod_lancamento;
                        DELETE FROM conlancamdig WHERE c78_codlan = cod_lancamento;
                        DELETE FROM conlancamdoc WHERE c71_codlan = cod_lancamento;
                        DELETE FROM conlancamdot WHERE c73_codlan = cod_lancamento;
                        DELETE FROM conlancamord WHERE c80_codlan = cod_lancamento;
                        DELETE FROM conlancamrec WHERE c74_codlan = cod_lancamento;
                        DELETE FROM conlancamlr WHERE c81_sequen IN (SELECT c69_sequen FROM conlancamval WHERE c69_codlan = cod_lancamento);
                        DELETE FROM contacorrentedetalheconlancamval WHERE c28_conlancamval IN (SELECT c69_sequen FROM conlancamval WHERE c69_codlan = cod_lancamento);
                        DELETE FROM conlancamval WHERE c69_codlan = cod_lancamento;
                        DELETE FROM conlancamcompl WHERE c72_codlan = cod_lancamento;
                        DELETE FROM conlancamele WHERE c67_codlan = cod_lancamento;
                        DELETE FROM conlancamnota WHERE c66_codlan = cod_lancamento;
                        DELETE FROM conlancampag WHERE c82_codlan = cod_lancamento;
                        DELETE FROM conlancamcorrente WHERE c86_conlancam = cod_lancamento;
                        DELETE FROM conlancamconcarpeculiar WHERE c08_codlan = cod_lancamento;
                        DELETE FROM conlancamcorgrupocorrente WHERE c23_conlancam = cod_lancamento;
                        DELETE FROM pagordemdescontolanc WHERE e33_conlancam = cod_lancamento;
                        DELETE FROM conlancammatestoqueinimei WHERE c103_conlancam = cod_lancamento;
                        DELETE FROM conlancaminstit WHERE c02_codlan = cod_lancamento;
                        DELETE FROM conlancamordem WHERE c03_codlan = cod_lancamento;
                        DELETE FROM conlancambem WHERE c110_codlan = cod_lancamento;
                        DELETE FROM conlancamaberturaexercicioorcamento WHERE c105_codlan = cod_lancamento;
                        DELETE FROM conlancamslip WHERE c84_conlancam = cod_lancamento;
                        DELETE FROM conlancamacordo WHERE c87_codlan = cod_lancamento;
                        DELETE FROM conlancaminscrestosapagaremliquidacao WHERE c210_codlan = cod_lancamento;
                        DELETE FROM conlancaminscrestosapagarnaoprocessados WHERE c108_codlan = cod_lancamento;
                        DELETE FROM conlancamacordo WHERE c87_codlan = cod_lancamento;
                        DELETE FROM conlancam WHERE c70_codlan = cod_lancamento;
                    
                        /* 'Atualizado informacoes nos empenhos'; */
                    
                        DELETE FROM empanuladoitem WHERE e37_empanulado IN (SELECT e94_codanu FROM empanulado WHERE (e94_numemp, e94_valor) = (seq_numemp, valor));
                        DELETE FROM empanuladoele WHERE e95_codanu IN (SELECT e94_codanu FROM empanulado WHERE (e94_numemp, e94_valor) = (seq_numemp, valor));
                        DELETE FROM empanulado WHERE e94_numemp = seq_numemp AND e94_valor = valor;
                    
                        UPDATE empelemento SET e64_vlranu = e64_vlranu - valor WHERE e64_numemp = seq_numemp;
                        UPDATE empempenho SET e60_vlranu = e60_vlranu - valor WHERE e60_numemp = seq_numemp;
                    
                        
                    END $$";

        $result = db_query($sqlExcluiAnulacoesEmp);

        if ($result === false) {
            $this->erro_msg = @pg_last_error();
            $this->erro = true;
            return false;
        }

        $this->ajustaSaldoContasLancamento($aDadosLancamentos, $dtAtual, $descricao);

        return true;

    }

    function ajustaSaldoContasLancamento($aDadosLancamentos, $dtData, $descrLog): bool
    {
        $ano = db_getsession('DB_anousu');
        $resmanut = db_query("select nextval('db_manut_log_manut_sequencial_seq') as seq");
        $seq = pg_result($resmanut, 0, 0);

        $sessionDataUsu = db_getsession('DB_datausu');
        $sessionUserId = db_getsession('DB_id_usuario');

        if (!$aDadosLancamentos){
            $dataAcerto = $dtData;
            $mesDataAcerto = intval(date('m', strtotime($dataAcerto)));
            $sqlAcertaContas = "DO $$
                                DECLARE
                                    mes_lancamento INTEGER := $mesDataAcerto;
                                    ano_lancamento INTEGER := $ano;
                                BEGIN
                                
                                  DELETE FROM conplanoexesaldo
                                  WHERE c68_anousu = ano_lancamento
                                    AND c68_mes >= mes_lancamento;
                                
                                  CREATE TEMP TABLE landeb ON COMMIT DROP AS
                                  SELECT c69_anousu,
                                         c69_debito,
                                         to_char(c69_data, 'MM')::integer,
                                         sum(round(c69_valor,2)),
                                         0::float8
                                  FROM conlancamval
                                  WHERE c69_anousu = ano_lancamento
                                    AND to_char(c69_data, 'MM')::integer >= mes_lancamento
                                  GROUP BY c69_anousu,
                                           c69_debito,
                                           to_char(c69_data, 'MM')::integer;
                                
                                  CREATE TEMP TABLE lancre ON COMMIT DROP AS
                                  SELECT c69_anousu,
                                         c69_credito,
                                         to_char(c69_data,'MM')::integer,
                                         0::float8,
                                         sum(round(c69_valor,2))
                                  FROM conlancamval
                                  WHERE c69_anousu = ano_lancamento
                                    AND to_char(c69_data, 'MM')::integer >= mes_lancamento
                                  GROUP BY c69_anousu,
                                           c69_credito,
                                           to_char(c69_data,'MM')::integer;
                                
                                  INSERT INTO conplanoexesaldo
                                  SELECT * FROM landeb;
                                
                                  UPDATE conplanoexesaldo
                                  SET c68_credito = lancre.sum
                                  FROM lancre
                                  WHERE c68_anousu = lancre.c69_anousu
                                    AND c68_reduz = lancre.c69_credito
                                    AND c68_mes = lancre.to_char
                                    AND c68_anousu = ano_lancamento;
                                
                                  DELETE FROM lancre USING conplanoexesaldo
                                  WHERE lancre.c69_anousu = conplanoexesaldo.c68_anousu
                                    AND conplanoexesaldo.c68_reduz = lancre.c69_credito
                                    AND conplanoexesaldo.c68_mes = lancre.to_char
                                    AND c68_anousu = ano_lancamento;
                                
                                  INSERT INTO conplanoexesaldo
                                  SELECT * FROM lancre
                                  WHERE c69_anousu = ano_lancamento;
                                
                                
                                END $$";

            $result = db_query($sqlAcertaContas);

            if ($result === false) {
                $this->erro_msg = @pg_last_error();
                $this->erro = true;
                return false;
            }

            $sqlInsert = "INSERT INTO db_manut_log VALUES ($seq, '$descrLog', $sessionDataUsu, $sessionUserId)";
            $rsInsert = db_query($sqlInsert);
            if ($rsInsert === false) {
                $this->erro_msg = @pg_last_error();
                $this->erro = true;
                return false;
            }

            return true;
        }

        foreach ($aDadosLancamentos as $oDadosLancamentos) {

            $dataAcerto = strtotime($oDadosLancamentos->c69_data) > strtotime($dtData) ? $dtData : $oDadosLancamentos->c69_data;
            $mesDataAcerto = intval(date('m', strtotime($dataAcerto)));

            $sqlAcertaContas = "DELETE FROM conplanoexesaldo
                                WHERE (conplanoexesaldo.c68_reduz, conplanoexesaldo.c68_anousu) = ($oDadosLancamentos->c69_credito, $ano)
                                AND conplanoexesaldo.c68_mes >= $mesDataAcerto;
                                
                                DELETE FROM conplanoexesaldo
                                WHERE (conplanoexesaldo.c68_reduz, conplanoexesaldo.c68_anousu) = ($oDadosLancamentos->c69_debito, $ano)
                                AND conplanoexesaldo.c68_mes >= $mesDataAcerto;
                                
                                CREATE TEMP TABLE landeb ON COMMIT DROP AS
                                SELECT c69_anousu,
                                       c69_debito,
                                       to_char(conlancamval.c69_data,'MM')::integer,
                                       sum(round(c69_valor,2)),0::float8
                                FROM conlancamval
                                WHERE (conlancamval.c69_debito, conlancamval.c69_anousu) = ($oDadosLancamentos->c69_debito, $ano)
                                  AND date_part('month',conlancamval.c69_data)::int4 >= $mesDataAcerto
                                GROUP BY conlancamval.c69_anousu, conlancamval.c69_debito, to_char(conlancamval.c69_data,'MM')::integer;
                                
                                CREATE TEMP TABLE lancre ON COMMIT DROP AS
                                SELECT c69_anousu,
                                       c69_credito,
                                       to_char(conlancamval.c69_data,'MM')::integer,
                                       0::float8,
                                       sum(round(c69_valor,2))
                                FROM conlancamval
                                WHERE (conlancamval.c69_credito, conlancamval.c69_anousu) = ($oDadosLancamentos->c69_credito, $ano)
                                  AND date_part('month',conlancamval.c69_data)::int4 >= $mesDataAcerto
                                GROUP BY conlancamval.c69_anousu, conlancamval.c69_credito, to_char(conlancamval.c69_data,'MM')::integer;
                                
                                INSERT INTO conplanoexesaldo
                                SELECT * FROM landeb
                                WHERE c69_anousu = $ano;
                                
                                UPDATE conplanoexesaldo
                                SET c68_credito = lancre.sum
                                FROM lancre
                                WHERE c68_anousu = lancre.c69_anousu
                                  AND c68_reduz = lancre.c69_credito
                                  AND c68_mes = lancre.to_char
                                  AND c68_anousu = $ano;
                                
                                DELETE FROM lancre
                                USING conplanoexesaldo
                                WHERE lancre.c69_anousu = conplanoexesaldo.c68_anousu
                                  AND conplanoexesaldo.c68_reduz = lancre.c69_credito
                                  AND conplanoexesaldo.c68_mes = lancre.to_char
                                  AND c68_anousu = $ano;
                                
                                INSERT INTO conplanoexesaldo
                                SELECT * FROM lancre
                                WHERE c69_anousu = $ano;
                
                                DROP TABLE IF EXISTS landeb;
                                DROP TABLE IF EXISTS lancre;";

            $result = db_query($sqlAcertaContas);

            if ($result === false) {
                $this->erro_msg = @pg_last_error();
                $this->erro = true;
                return false;
            }
        }


        $sqlInsert = "INSERT INTO db_manut_log VALUES ($seq, '$descrLog', $sessionDataUsu, $sessionUserId)";
        $rsInsert = db_query($sqlInsert);
        if ($rsInsert === false) {
            $this->erro_msg = @pg_last_error();
            $this->erro = true;
            return false;
        }

        return true;
    }

    function verificaSaldoEmp($seqEmp, $dtData)
    {
        $sql = "WITH total_liquidado AS
                        (SELECT CASE 
                                    WHEN count(*) = 0 THEN 0 
                                    ELSE sum(c70_valor) 
                                END AS total_liquidado
                         FROM conlancam
                          JOIN conlancamdoc ON c70_codlan = c71_codlan
                          JOIN conhistdoc ON c71_coddoc = c53_coddoc
                          JOIN conlancamemp  ON c70_codlan = c75_codlan
                         WHERE c75_numemp  = {$seqEmp} AND c53_tipo = 20 AND c70_data <= '$dtData'),
                        
                     total_estornado AS
                        (SELECT CASE 
                                    WHEN count(*) = 0 THEN 0 
                                    ELSE sum(c70_valor) 
                                END AS total_estornado
                         FROM conlancam
                          JOIN conlancamdoc ON c70_codlan = c71_codlan
                          JOIN conhistdoc ON c71_coddoc = c53_coddoc
                          JOIN conlancamemp  ON c70_codlan = c75_codlan
                         WHERE c75_numemp  = {$seqEmp} AND c53_tipo = 21 AND c70_data <= '$dtData'),
                        
                     total_anulado AS
                         (SELECT CASE 
                                    WHEN count(*) = 0 THEN 0 
                                    ELSE sum(c70_valor) 
                                END AS total_anulado
                          FROM conlancam
                           JOIN conlancamdoc ON c70_codlan = c71_codlan
                           JOIN conhistdoc ON c71_coddoc = c53_coddoc
                           JOIN conlancamemp  ON c70_codlan = c75_codlan
                          WHERE c75_numemp  = {$seqEmp} AND c53_tipo = 11 AND c70_data <= '$dtData'),
                        
                     valor_empenho AS
                        (SELECT e60_vlremp AS valor_empenho FROM empempenho WHERE e60_numemp = {$seqEmp})
                        
                     SELECT sum(valor_empenho - total_anulado - total_liquidado + total_estornado) AS saldo_empenho
                     FROM total_liquidado, total_estornado, total_anulado, valor_empenho";

        $result = db_query($sql);

        if ($result === false) {
            $this->erro_msg = @pg_last_error();
            $this->erro = true;
            return false;
        }
        return db_utils::getCollectionByRecord($result);
    }

}
