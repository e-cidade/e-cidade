<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class InserirMenusNaoPeriodicos extends PostgresMigration
{

    private $idVersaoFormulario = 29;
    private $idFormularioTipo = 35;
    private $idModEsocial = 10216;

    public function up()
    {

        $arrMenus = array(
            's2190' => 'S-2190 - Registro Preliminar de Trabalhador',
            's2200' => 'S-2200 - Cadastramento Inicial do V�nculo e Admiss�o/Ingresso de Trabalhador',
            's2205' => 'S-2205 - Altera��o de Dados Cadastrais do Trabalhador',
            's2206' => 'S-2206 - Altera��o de Contrato de Trabalho/Rela��o Estatut�ria',
            's2230' => 'S-2230 - Afastamento Tempor�rio',
            's2231' => 'S-2231 - Cess�o/Exerc�cio em Outro �rg�o',
            's2298' => 'S-2298 - Reintegra��o/Outros Provimentos',
            's2299' => 'S-2299 - Desligamento',
            's2300' => 'S-2300 - Trabalhador Sem V�nculo de Emprego/Estatut�rio - In�cio',
            's2306' => 'S-2306 - Trabalhador Sem V�nculo de Emprego/Estatut�rio - Altera��o Contratual',
            's2399' => 'S-2399 - Trabalhador Sem V�nculo de Emprego/Estatut�rio - T�rmino',
            's2400' => 'S-2400 - Cadastro de Benefici�rio - Entes P�blicos - In�cio',
            's2405' => 'S-2405 - Cadastro de Benefici�rio - Entes P�blicos - Altera��o',
            's2410' => 'S-2410 - Cadastro de Benef�cio - Entes P�blicos - In�cio',
            's2416' => 'S-2416 - Cadastro de Benef�cio - Entes P�blicos - Altera��o',
            's2418' => 'S-2418 - Reativa��o de Benef�cio - Entes P�blicos',
            's2420' => 'S-2420 - Cadastro de Benef�cio - Entes P�blicos - T�rmino',
            's3000' => 'S-3000 - Exclus�o de Eventos',
            's5001' => 'S-5001 - Informa��es das Contribui��es Sociais por Trabalhador',
            's5002' => 'S-5002 - Imposto de Renda Retido na Fonte por Trabalhador',
            's5003' => 'S-5003 - Informa��es do FGTS por Trabalhador',
            's5011' => 'S-5011 - Informa��es das Contribui��es Sociais Consolidadas por Contribuinte',
            's5013' => 'S-5013 - Informa��es do FGTS Consolidadas por Contribuinte'
            //'s8299' => 'S-8299 - Baixa Judicial do V�nculo',
        );

        $sSql = "";
        $this->idVersaoFormulario++;
        $this->idFormularioTipo++;
        foreach ($arrMenus as $keyMenu => $descMenu) {
            $sSql .= $this->getSqlAddMenu($keyMenu, $descMenu);
            $sSql .= $this->getSqlAddFormTipo($keyMenu, $descMenu);
            $this->idVersaoFormulario++;
            $this->idFormularioTipo++;
        }
        $sSql .= $this->getSqlSetSequence();

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "
        DELETE FROM esocialversaoformulario WHERE rh211_sequencial > {$this->idVersaoFormulario};
        DELETE FROM esocialformulariotipo WHERE rh209_sequencial > {$this->idFormularioTipo};
        DELETE FROM db_itensmenu WHERE id_item IN (SELECT id_item_filho FROM db_itensmenu JOIN db_menu ON db_itensmenu.id_item = db_menu.id_item WHERE descricao ILIKE 'N%o Peri%dicos');
        DELETE FROM db_menu WHERE id_item = (SELECT id_item FROM db_itensmenu WHERE descricao ILIKE 'N%o Peri%dicos') AND modulo = {$this->idModEsocial};
        ";
        $this->execute($sSql);
    }

    private function getSqlSetSequence()
    {
        return "
        SELECT setval('esocialformulariotipo_rh209_sequencial_seq', (SELECT max(rh209_sequencial) FROM esocialformulariotipo));
        SELECT setval('esocialversaoformulario_rh211_sequencial_seq', (SELECT max(rh211_sequencial) FROM esocialversaoformulario));
        ";
    }

    private function getSqlAddMenu($keyMenu, $descMenu)
    {
        return "
        INSERT INTO db_itensmenu
        VALUES ((SELECT MAX(id_item)+1 FROM db_itensmenu),
            '{$descMenu}',
            '{$descMenu}',
            (SELECT 'con4_manutencaoformulario001.php?esocial='||max(rh209_sequencial)::varchar FROM esocialformulariotipo),
            1,
            1,
            '{$descMenu}',
            't');

        INSERT INTO db_menu
        VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao ILIKE 'N%o Peri%dicos'),
            (SELECT MAX(id_item) FROM db_itensmenu),
            (SELECT coalesce(MAX(menusequencia),0) FROM db_menu WHERE id_item = (SELECT id_item FROM db_itensmenu WHERE descricao ILIKE 'N%o Peri%dicos')),
            {$this->idModEsocial});
        ";
    }

    private function getSqlAddFormTipo($keyMenu, $descMenu)
    {
        if ($this->checkFormularioTipo()) {
            return "
            INSERT INTO esocialformulariotipo
            (rh209_sequencial,rh209_descricao) VALUES ({$this->idFormularioTipo},'{$descMenu}');
            INSERT INTO esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES ({$this->idVersaoFormulario}, '1.0', (SELECT db101_sequencial FROM avaliacao WHERE db101_identificador='{$keyMenu}-vs1'), (SELECT max(rh209_sequencial) FROM esocialformulariotipo));
            ";
        }
        return "";
    }

    private function checkFormularioTipo()
    {
        $result = $this->fetchRow("SELECT * FROM esocialformulariotipo WHERE rh209_sequencial = {$this->idFormularioTipo}");
        if (empty($result)) {
            return true;
        }
        return false;
    }
}
