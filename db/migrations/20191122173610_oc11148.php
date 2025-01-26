<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11148 extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-PostgresMigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $sql = <<<SQL

        BEGIN;

          SELECT fc_startsession();

            INSERT INTO tiposnotificacao
            VALUES (nextval('tiposnotificacao_p110_sequencial_seq'),
                    'Abertura de processo de pagamento',
                    'Senhor fornecedor,<br><br>
            O processo de pagamento ref. a nota fiscal {$nfe} foi aberto e registrado sob o protocolo {$protocolo}.<br>
            Anote este n�mero para realizar eventual pedido de informa��o a respeito do pagamento.<br>
            A nota fiscal ser� enviada ao gestor da contrata��o para confer�ncia e liquida��o da despesa.<br>
            Lembramos que o prazo contratual para pagamento de 10 dias �teis conta-se a partir da data da liquida��o, pois � este ato que atesta que o faturamento est� de acordo com o contrato e a execu��o.<br>
            Informamos que na data da liquida��o, encaminharemos um novo comunicado informando a data prevista para o pagamento.<br>
            Atenciosamente,<br><br>
            <strong>Divis�o de Gest�o Financeira</strong><br>
            C�mara Municipal de Belo Horizonte<br>
            Atendimento ao fornecedor: Email: <a href="#">divgef.notafiscal@cmbh.mg.gov.br</a> ou telefone: (31)3555-1138<br><br>
            <strong>Aten��o:</strong><br>
            As informa��es contidas neste e-mail, e nos arquivos anexos, s�o para uso exclusivo do destinat�rio aqui indicado, n�o se<br>
            autorizando o acesso por qualquer outra pessoa. Caso n�o seja o destinat�rio correto, esteja notificado pelo presente que qualquer<br>
            revis�o, leitura, c�pia e/ou divulga��o do conte�do deste "e-mail" s�o estritamente proibidas e n�o autorizadas. Por favor, apague o<br>
            conte�do do "e-mail" e notifique a remetente imediatamente. Agradecemos a colabora��o.',
                    1);

            INSERT INTO tiposnotificacao
            VALUES (nextval('tiposnotificacao_p110_sequencial_seq'),
                    'Realiza��o do pagamento',
                    'Senhor fornecedor,<br><br>
            Informamos a realiza��o do pagamento ref. a nota fiscal {$nfe}, processo de pagamento registrado sob o n� {$numpagamento}.<br>
            Agradecemos a parceria na rela��o contratual.<br>
            Atenciosamente,<br><br>
            <strong>Divis�o de Gest�o Financeira</strong><br>
            C�mara Municipal de Belo Horizonte<br>
            Atendimento ao fornecedor: Email: <a href="#">divgef.notafiscal@cmbh.mg.gov.br</a> ou telefone: (31)3555-1138<br><br>
            <strong>Aten��o:</strong><br>
            As informa��es contidas neste e-mail, e nos arquivos anexos, s�o para uso exclusivo do destinat�rio aqui indicado, n�o se<br>
            autorizando o acesso por qualquer outra pessoa. Caso n�o seja o destinat�rio correto, esteja notificado pelo presente que qualquer<br>
            revis�o, leitura, c�pia e/ou divulga��o do conte�do deste "e-mail" s�o estritamente proibidas e n�o autorizadas. Por favor, apague o<br>
            conte�do do "e-mail" e notifique a remetente imediatamente. Agradecemos a colabora��o.',
                    3);

            INSERT INTO tiposnotificacao
            VALUES (nextval('tiposnotificacao_p110_sequencial_seq'),
                    'Previs�o de pagamento',
                    'Senhor fornecedor,<br><br>
            Informamos que a nota fiscal  {$nfe}, ref. ao processo registrado sob o protocolo {$protocolo} foi liquidada pelo gestor do contrato em {$dataliquidacao}.<br>
            O prazo contratual para pagamento � de 10 dias �teis conta-se a partir desta data.
            Dessa forma, a previs�o de pagamento � {$dataprevisao}.<br>
            Atenciosamente,<br><br>
            <strong>Divis�o de Gest�o Financeira</strong><br>
            C�mara Municipal de Belo Horizonte<br>
            Atendimento ao fornecedor: Email: <a href="#">divgef.notafiscal@cmbh.mg.gov.br</a> ou telefone: (31)3555-1138<br><br>
            <strong>Aten��o:</strong><br>
            As informa��es contidas neste e-mail, e nos arquivos anexos, s�o para uso exclusivo do destinat�rio aqui indicado, n�o se<br>
            autorizando o acesso por qualquer outra pessoa. Caso n�o seja o destinat�rio correto, esteja notificado pelo presente que qualquer<br>
            revis�o, leitura, c�pia e/ou divulga��o do conte�do deste "e-mail" s�o estritamente proibidas e n�o autorizadas. Por favor, apague o<br>
            conte�do do "e-mail" e notifique a remetente imediatamente. Agradecemos a colabora��o.',
                    2);

            INSERT INTO tiposnotificacao
            VALUES (nextval('tiposnotificacao_p110_sequencial_seq'),
                    'Atraso de Pagamento',
                    'Senhor fornecedor, Comunicamos o atraso do pagamento',
                    0);
        COMMIT;
SQL;
        $this->execute($sql);
    }
}
