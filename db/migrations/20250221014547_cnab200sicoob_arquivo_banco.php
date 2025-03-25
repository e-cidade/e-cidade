<?php

use ECidade\Suporte\Phinx\PostgresMigration;

final class Cnab200sicoobArquivoBanco extends PostgresMigration
{
    public function up()
    {
        $this->insertMenu();
        if ($this->checkLayout()) {
            $this->insertLayout();
        }
    }

    public function down()
    {
        $this->removeMenu();
    }

    private function insertMenu()
    {
        $sql = <<<SQL

        INSERT INTO configuracoes.db_itensmenu
        VALUES ((SELECT MAX(id_item)+1 FROM configuracoes.db_itensmenu),
            'Geração de Arquivo SICOOB 200',
            'Geração de Arquivo SICOOB 200',
            'pes2_sicoob200cnab001.php',
            1,
            1,
            'Geração de Arquivo SICOOB 200',
            't');

        INSERT INTO configuracoes.db_menu 
        VALUES (5629,
            (SELECT MAX(id_item) FROM configuracoes.db_itensmenu),
            5,
            952);

SQL;
        $this->execute($sql);
    }

    private function removeMenu()
    {
        $sql = <<<SQL

        DELETE FROM configuracoes.db_menu WHERE id_item_filho = (SELECT id_item FROM configuracoes.db_itensmenu WHERE funcao = 'pes2_sicoob200cnab001.php');
        DELETE FROM configuracoes.db_itensmenu WHERE funcao = 'pes2_sicoob200cnab001.php';
SQL;
        $this->execute($sql);
    }

    private function insertLayout()
    {
        $sql = <<<SQL

        INSERT INTO configuracoes.db_layouttxt (db50_codigo, db50_descr, db50_quantlinhas, db50_obs, db50_layouttxtgrupo) VALUES ((SELECT max(db50_codigo)+1 FROM configuracoes.db_layouttxt), 'CNAB200 BANCO SICOOB', 0, '', 1);

        INSERT INTO configuracoes.db_layoutlinha (db51_codigo, db51_layouttxt, db51_descr, db51_tipolinha, db51_tamlinha, db51_linhasantes, db51_linhasdepois, db51_obs, db51_separador, db51_compacta) VALUES ((SELECT max(db51_codigo)+1 FROM configuracoes.db_layoutlinha), (SELECT max(db50_codigo) FROM configuracoes.db_layouttxt), 'HEADER DE ARQUIVO', 1, 200, 0, 0, '', '', false);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'tiporegistro', 'TIPO DE REGISTRO', 2, 1, '01', 2, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'banco', 'BANCO', 2, 3, '756', 3, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'codcooperativa', 'CÓDIGO DA COOPERATIVA', 2, 6, '', 4, false, true, 'e', 'AGENCIA SEM DIGITO', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'numeroconvenio', 'NUMERO CONVENIO', 2, 10, '', 7, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'brancos', 'BRANCOS', 1, 17, '', 10, false, true, 'd', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'sequencial', 'SEQUENCIAL', 2, 27, '', 2, true, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'produto', 'PRODUTO', 2, 29, '03', 2, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'datageracao', 'DATA DE GERAÇÃO DO ARQUIVO', 10, 31, '', 8, false, true, 'd', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'brancos2', 'BRANCOS2', 1, 39, '', 162, false, true, 'd', '', 0);
        
        INSERT INTO configuracoes.db_layoutlinha (db51_codigo, db51_layouttxt, db51_descr, db51_tipolinha, db51_tamlinha, db51_linhasantes, db51_linhasdepois, db51_obs, db51_separador, db51_compacta) VALUES ((SELECT max(db51_codigo)+1 FROM configuracoes.db_layoutlinha), (SELECT max(db50_codigo) FROM configuracoes.db_layouttxt), 'DETALHES', 3, 200, 0, 0, '', '', false);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'tiporegistro', 'TIPO DE REGISTRO', 2, 1, '1', 1, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'tipooperacao', 'TIPO DE OPERACAO', 1, 2, 'C', 1, false, true, 'd', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'zeros', 'ZEROS', 2, 3, '0000', 4, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'numeroconta', 'NUMERO DA CONTA', 2, 7, '', 6, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'z01_nome', 'NOME DO BENEFICIARIO', 1, 13, '', 40, false, true, 'd', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'z01_cgccpf', 'CPF/CNPJ BENEFICIARIO', 7, 53, '', 14, false, true, 'd', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'brancos', 'BRANCOS', 1, 67, '', 12, false, true, 'd', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'zeros', 'ZEROS', 2, 79, '000', 3, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'matricula', 'MATRICULA', 2, 82, '', 20, true, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'brancos2', 'BRANCOS2', 1, 102, '', 10, false, true, 'd', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'valorpagamento', 'VALOR DO PAGAMENTO', 3, 112, '', 17, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'brancos3', 'BRANCOS3', 1, 129, '', 10, false, true, 'd', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'padrao', 'VALOR PADRAO', 1, 139, '000N', 4, false, true, 'd', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'brancos4', 'BRANCOS4', 1, 143, '', 58, false, true, 'd', '', 0);

        INSERT INTO configuracoes.db_layoutlinha (db51_codigo, db51_layouttxt, db51_descr, db51_tipolinha, db51_tamlinha, db51_linhasantes, db51_linhasdepois, db51_obs, db51_separador, db51_compacta) VALUES ((SELECT max(db51_codigo)+1 FROM configuracoes.db_layoutlinha), (SELECT max(db50_codigo) FROM configuracoes.db_layouttxt), 'TRAILLER DE ARQUIVO', 5, 200, 0, 0, '', '', false);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'tiporegistro', 'TIPO DE REGISTRO', 2, 1, '9', 1, true, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'reservado', 'RESERVADO', 2, 2, '0', 18, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'quantidaderegistarq', 'QUANTIDADE DE REGISTROS DO ARQUIVO', 2, 20, '', 9, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'somavalores', 'SOMA VALORES PAGOS', 3, 29, '', 17, false, true, 'e', '', 0);
        INSERT INTO configuracoes.db_layoutcampos (db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos) VALUES ((SELECT max(db52_codigo)+1 FROM configuracoes.db_layoutcampos), (SELECT max(db51_codigo) FROM configuracoes.db_layoutlinha), 'brancos', 'BRANCOS', 1, 46, '', 155, false, true, 'd', '', 0);
SQL;
        $this->execute($sql);
    }

    private function checkLayout()
    {
        $result = $this->fetchRow("SELECT * FROM configuracoes.db_layouttxt WHERE db50_descr = 'CNAB200 BANCO SICOOB'");
        if (empty($result)) {
            return true;
        }
        return false;
    }
}
