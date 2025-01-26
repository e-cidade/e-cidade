<?php

use Phinx\Migration\AbstractMigration;
use App\Support\Database\InsertMenu;

class Oc22983 extends AbstractMigration
{

    use InsertMenu;

    public function up()
    {
        $descrMenuPai   = 'D�vida Consolidada';
        $descrModulo    = 'Contabilidade';
        $helperItemPai  = 'D�vida Consolidada';
        $desctecItemPai = '';
        $descrNovoMenu  = 'Movimenta��es';
        $helperNovoMenu = 'Gerencia movimenta��es de dividas consolidadas.';
        $arquivoMenu    = 'sic1_dividaconsolidada004.php';
        $this->criaMenuNovo($descrMenuPai, $descrModulo, $descrNovoMenu, $arquivoMenu, $helperNovoMenu, $helperItemPai, $desctecItemPai);
        $this->criaTabelaMovimentacaotipo();
        $this->insereControleMovimentacaodetalhe();
        $this->dadoIniciais();
        $this->criaTabelaMovimentacoes();
        $this->insereControleMovimentacaodedivida();
    }

    private function criaMenuNovo($descrMenuPai, $descrModulo, $descrNovoMenu, $arquivoMenu, $helperNovoMenu, $helperItemPai, $desctecItemPai)
    {
        $this->insertItemMenu($descrNovoMenu, $arquivoMenu, $helperNovoMenu);

        $this->insertMenu($descrMenuPai, $descrModulo, $helperItemPai, $desctecItemPai);
    }

    private function criaTabelaMovimentacaotipo()
    {
        $sql="
            BEGIN;

            CREATE TABLE IF NOT EXISTS movimentacaodetalhe
            (
                op03_sequencial SERIAL PRIMARY KEY,
                op03_descr varchar(50) NOT NULL
            );

            COMMIT;
        ";
        $this->execute($sql);
    }

    private function insereControleMovimentacaodetalhe(){
        $sql="
            BEGIN;            
            --Cria registros da tabela movimentacaodetalhe e de seus campos
            INSERT INTO db_sysarquivo 
            VALUES ((SELECT max(codarq)+1 FROM db_sysarquivo), 'movimentacaodetalhe', 'Detalhe das Movimenta��es de divida consolidada', 'op03', CURRENT_DATE, 'Movimenta��es Detalhe', 0, 'f', 'f', 'f', 'f' );
            
            INSERT INTO db_sysarqmod 
            VALUES (32,(SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodetalhe'));

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'op03_sequencial' ,'int8' ,'Sequencial' ,'0' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );
            
            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodetalhe'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'op03_sequencial'),1 ,0 );

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'op03_descr' ,'varchar(50)' ,'Descri��o' ,'0' ,'Descri��o' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Descri��o' );
            
            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodetalhe'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'op03_descr'),1 ,0 );

            INSERT INTO db_syssequencia VALUES((SELECT max(codsequencia) + 1 FROM db_syssequencia), 'movimentacaodetalhe_op03_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = (SELECT codsequencia FROM db_syssequencia WHERE nomesequencia = 'movimentacaodetalhe_op03_sequencial_seq') WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodetalhe') and codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'op03_sequencial');
            COMMIT;";
        $this->execute($sql);
    }

    private function dadoIniciais()
    {
        $sql="
            BEGIN;

            INSERT INTO movimentacaodetalhe (op03_sequencial, op03_descr) VALUES 
            ('1', 'Contrata��o'),
            ('2', 'Amortiza��o'),
            ('3', 'Cancelamento'),
            ('4', 'Encapa��o'),
            ('5', 'Atualiza��o');

            COMMIT;
        ";
        $this->execute($sql);
    }

    private function criaTabelaMovimentacoes(){
        $sql="
            BEGIN;

            CREATE TABLE IF NOT EXISTS movimentacaodedivida
            (
                op02_sequencial SERIAL PRIMARY KEY,
                op02_operacaodecredito int8 NOT NULL,
                op02_movimentacao int8 NOT NULL,
                op02_tipo int8 DEFAULT 0,
                op02_data DATE NOT NULL,
                op02_justificativa VARCHAR(500),
                op02_valor FLOAT8 DEFAULT 0,
                CONSTRAINT fk_operacaodecredito FOREIGN KEY (op02_operacaodecredito) REFERENCES db_operacaodecredito(op01_sequencial),
                CONSTRAINT fk_movimentacao FOREIGN KEY (op02_movimentacao) REFERENCES movimentacaodetalhe(op03_sequencial)
            );

            COMMIT;
        ";
        $this->execute($sql);
    }

    private function insereControleMovimentacaodedivida(){
        $sql="
            BEGIN;
            
            --Cria registros da tabela movimentacaodedivida e de seus campos
            INSERT INTO db_sysarquivo 
            VALUES ((SELECT max(codarq)+1 FROM db_sysarquivo), 'movimentacaodedivida', 'Movimenta��es de divida consolidada', 'op02', CURRENT_DATE, 'Movimenta��es', 0, 'f', 'f', 'f', 'f' );
            
            INSERT INTO db_sysarqmod 
            VALUES (32,(SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodedivida'));

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'op02_sequencial' ,'int8' ,'Sequencial' ,'0' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );
            
            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodedivida'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'op02_sequencial'),1 ,0 );

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'op02_operacaodecredito' ,'int8' ,'Opera��o de Credito' ,'0' ,'Opera��o de Credito' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Opera��o de Credito' );
            
            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodedivida'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'op02_operacaodecredito'),1 ,0 );

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'op02_movimentacao' ,'int8' ,'Movimenta��o' ,'0' ,'Movimenta��o' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Movimenta��o' );
            
            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodedivida'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'op02_movimentacao'),1 ,0 );

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'op02_tipo' ,'int8' ,'Tipo' ,'0' ,'Tipo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo' );
            
            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodedivida'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'op02_tipo'),1 ,0 );

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'op02_data' ,'date' ,'Data da Movimenta��o' ,'0' ,'Data da Movimenta��o' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Data da Movimenta��o' );
            
            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodedivida'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'op02_data'),1 ,0 );

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'op02_justificativa' ,'varchar(500)' ,'Justificativa' ,'0' ,'Justificativa' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Justificativa' );
            
            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodedivida'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'op02_justificativa'),1 ,0 );            
            
            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'op02_valor' ,'float8' ,'Valor' ,'' ,'Valor' ,15 ,'false' ,'false' ,'false' ,4 ,'text' ,'Valor' );
            
            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodedivida'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'op02_valor'),4 ,0 );

            INSERT INTO db_syssequencia VALUES((SELECT max(codsequencia) + 1 FROM db_syssequencia), 'movimentacaodedivida_op02_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = (SELECT codsequencia FROM db_syssequencia WHERE nomesequencia = 'movimentacaodedivida_op02_sequencial_seq') WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'movimentacaodedivida') and codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'op02_sequencial');

            COMMIT;
        ";
        $this->execute($sql);
    }
}
