<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Oc23172 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->criaParametro();
        $this->criaFuncoes();
    }

    protected function criaParametro()
    {
        Schema::table('empparametro', function (Blueprint $table) {
            $table->boolean('e30_anularpprocessado')->default(false); 
        });

        $iNextCodcam = DB::table('db_syscampo')->max('codcam') + 1;

        DB::table('db_syscampo')->insert([
            'codcam'       => $iNextCodcam,
            'nomecam'      => 'e30_anularpprocessado',
            'conteudo'     => 'bool',
            'descricao'    => 'Anular RP Processado com movimentação de estoque',
            'valorinicial' => '',
            'rotulo'       => 'Anular RP Processado com movimentação de estoque',
            'tamanho'      => 9999,
            'nulo'         => 'false',
            'maiusculo'    => 'false',
            'autocompl'    => 'false',
            'aceitatipo'   => 0,
            'tipoobj'      => 'text',
            'rotulorel'    => 'Anular RP Processado',
        ]);

        $iCodarq = DB::table('db_sysarquivo')
            ->where('nomearq', 'empparametro')
            ->value('codarq');

        $iCodcam = DB::table('db_syscampo')
            ->where('nomecam', 'e30_anularpprocessado')
            ->value('codcam');

        DB::table('db_sysarqcamp')->insert([
            'codarq'       => $iCodarq,
            'codcam'       => $iCodcam,
            'seqarq'       => 2,
            'codsequencia' => 0,
        ]);
    }

    protected function criaFuncoes()
    {
        // Funções novas, não existentes no banco 
        DB::statement("
        CREATE OR REPLACE FUNCTION public.fc_saldoitensempenho_anulacao(integer)
        RETURNS SETOF tp_saldoitensempenho
        LANGUAGE plpgsql
        AS \$function\$
        declare
          iNumEmp Alias for \$1;
          rtp_saldoitensempenho record;
        begin
          for rtp_saldoitensempenho in select * from fc_saldoitens_anulacao(iNumEmp,false,null,1) loop
              return next rtp_saldoitensempenho;
          end loop;
          return;
        end;
        \$function\$");

        DB::statement("
        CREATE OR REPLACE FUNCTION public.fc_saldoitens_anulacao(integer, boolean, integer, integer)
        RETURNS SETOF tp_saldoitensempenho
        LANGUAGE plpgsql
        AS \$function\$
        declare
            iCodigo             alias for $1;
            lRaiseFunc          alias for $2;
            iTipo               alias for $4;
            iCodItem            alias for $3;
            nQuantIni           numeric  default 0; -- saldo Inicial do Item
            nSaldoItem          numeric  default 0; -- saldo atual do Item;
            nValorIni           numeric  default 0; -- valor inicial do item (valor total dos itens (qtd*valor unitario)
            nSaldoValor         numeric  default 0; -- Saldo do valor do item
            nSaldoEstoque       numeric  default 0;
            nValorEstoque       numeric  default 0;
            nSaldoOrdem         numeric  default 0;
            nValorOrdem         numeric  default 0;
            lControlaQuantidade boolean;
            bRaise              boolean;
            sSQL                varchar;
            sSQLOrdem           varchar;
            sSQLanul            varchar;
            sErro               varchar; -- mensagem de erro
            iInstit             integer;
            iAnoUsu             integer;
            sWhere              varchar default null;
            sJoin               varchar default null;
            iItemAnt            integer default null; -- codigo anterior do item de empenho (usando para controlar contagem dos itens;)
            rtp_saldoitensempenho tp_saldoitensempenho%ROWTYPE;
            rSaldoItens   record;
            rSaldoOrdem   record;
            rSaldoestoque record;
            rNotas        record;
            rAnulado      record;
        begin
            iAnousu   = fc_getsession('DB_anousu');
            iInstit   = fc_getsession('DB_instit');
            bRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end);
            if iAnousu is null then
                raise exception 'variavel de sessao DB_anousu nao inicializada';
            end if ;

            if iInstit is null then
                raise exception  'variavel de sessao DB_instit nao inicializada';
            end if ;

            sWhere = ' e60_instit = '||iInstit;
            if iTipo  = 1 then

                if iCodigo is not null then
                sWhere := sWhere || ' and e62_numemp   = '||iCodigo;
                else
                sWhere := sWhere || ' and e60_anousu   = '||iAnousu;
                end if;

                sJoin  := '';
            else
                if iCodigo is not null then
                sWhere := sWhere || ' and m52_codordem = '||iCodigo;
                end if;
                sJoin  := ' left join matordemitem on m52_numemp = e62_numemp
                                                and m52_sequen = e62_sequen';
            end if;
            if iCodItem is not null then
                sWhere := sWhere ||' and e62_sequencial = '||iCodItem;
            end if;
            sSQL = 'select pc01_descrmater,
                            pc01_servico,
                            e62_numemp,
                            e62_item,
                            e60_anousu,
                            e60_codemp,
                            e62_sequencial,
                            e62_sequen,
                            e62_quant,
                            e62_vltot,
                            e62_vlrun,
                            e62_descr,
                            e62_servicoquantidade
                        From empempitem
                            inner join empempenho   on e62_numemp   = e60_numemp
                            inner join pcmater      on e62_item     = pc01_codmater
                            '||sJoin||'
                    where '||sWhere||'
                    order by e62_sequen';

                if bRaise then
                    raise notice 'Inicializando variaveis - sql %', sSQL;
                end if;
                
                rtp_saldoitensempenho.riNumEmp              := 0;
                rtp_saldoitensempenho.riAnoEmp              := 0;
                rtp_saldoitensempenho.riCodOrdem            := 0;
                rtp_saldoitensempenho.riCodmater            := 0;
                rtp_saldoitensempenho.riCodItem             := 0;
                rtp_saldoitensempenho.riCodEmp              := 0;
                rtp_saldoitensempenho.rsDescr               := 0;
                rtp_saldoitensempenho.rsDescrEmp            := '';
                rtp_saldoitensempenho.riSeqItem             := 0;
                rtp_saldoitensempenho.rnQuantIni            := 0;
                rtp_saldoitensempenho.rnSaldoItem           := 0;
                rtp_saldoitensempenho.rnValorIni            := 0;
                rtp_saldoitensempenho.rnSaldoValor          := 0;
                rtp_saldoitensempenho.rnValorUni            := 0;
                rtp_saldoitensempenho.rnSaldoEstoque        := 0;
                rtp_saldoitensempenho.rnValorEstoque        := 0;
                rtp_saldoitensempenho.rnSaldoOrdem          := 0;
                rtp_saldoitensempenho.rnValorOrdem          := 0;
                rtp_saldoitensempenho.ricoditemordem        := 0;
                rtp_saldoitensempenho.rnSaldoEntradaEmpenho := 0;
                rtp_saldoitensempenho.rlControlaQuantidade  := false;

                if bRaise then
                    raise notice 'Iniciando soma dos saldos';
                end if;

                for rSaldoItens in execute sSQL loop

                    nSaldoValor   := 0;
                    nSaldoItem    := 0;
                    nSaldoEstoque := 0;
                    nValorEstoque := 0;
                    nSaldoOrdem   := 0;
                    nValorOrdem   := 0;
                    --selecionamos os totais da ordem.
                    sSQLOrdem := 'select m52_codordem,
                                        m52_codlanc,
                                        coalesce(sum(m52_quant - (select coalesce(sum(m36_qtd),0)
                                                                from matordemitemanu
                                                                where m36_matordemitem = m52_codlanc)),0) as quantidadeordens,
                                        coalesce(sum(m52_valor - (select coalesce(sum(m36_vrlanu),0)
                                                                from matordemitemanu
                                                                where m36_matordemitem = m52_codlanc)),0) as valorordens
                                    from matordemitem
                                        -- inner join matordem on matordem.m51_codordem = m52_codordem
                                                            -- and m51_tipo                = 1
                                        inner join empempitem           on m52_numemp        = e62_numemp
                                                                        and m52_sequen        = e62_sequen
                                        inner join empempenho           on e62_numemp        = e60_numemp
                                        left join matestoqueitemoc on m52_codlanc = m73_codmatordemitem
                                where e62_sequencial = '||rSaldoItens.e62_sequencial||'
                                and '||sWhere||' and m73_codmatestoqueitem is not null
                                group by m52_codordem,m52_codlanc';

                    for rSaldoOrdem in execute sSQLOrdem loop

                    nSaldoItem                           = nSaldoItem    + coalesce((rSaldoOrdem.quantidadeordens), 0);
                    nSaldoValor                          = nSaldoValor   + (rSaldoOrdem.valorordens);
                    nSaldoOrdem                          = nSaldoOrdem   + rSaldoOrdem.quantidadeordens;
                    nValorOrdem                          = nValorOrdem   + rSaldoOrdem.valorordens;
                    rtp_saldoitensempenho.riCodOrdem     = rSaldoOrdem.m52_codordem;
                    rtp_saldoitensempenho.ricoditemordem = rSaldoOrdem.m52_codlanc;

                    end loop;
                    --selecionamos o totais do estoque
                    sSQLOrdem := 'select (coalesce(sum(m75_quant), 0) / m75_quantmult) as m71_quant,
                                        coalesce(sum(m71_valor),0)  as m71_valor
                                    from matordemitem
                                        inner join empempitem           on m52_numemp            = e62_numemp
                                                                        and m52_sequen            = e62_sequen
                                        inner join empempenho           on e62_numemp            = e60_numemp
                                        inner join matestoqueitemoc     on m73_codmatordemitem   = m52_codlanc
                                                                        and m73_cancelado  is false
                                        inner join matestoqueitem       on m73_codmatestoqueitem = m71_codlanc
                                        inner  join matestoqueitemunid   on m75_codmatestoqueitem = m71_codlanc
                                    where e62_sequencial = '||rSaldoItens.e62_sequencial||'
                                    and '||sWhere||'
                                    group by m52_codordem,m52_codlanc,m75_quantmult';


                    for rSaldoestoque in execute sSQLOrdem loop

                        nSaldoEstoque                        := nSaldoEstoque + rSaldoEstoque.m71_quant;
                        nValorEstoque                        := nValorEstoque + rSaldoEstoque.m71_valor;

                    end loop;

                    nSaldoOrdem := (nSaldoOrdem - nSaldoEstoque);
                    nValorOrdem := (nValorOrdem - nValorEstoque);
                    -- selecionamos os totais liquidados do item - encontramos na empnotaitem
                    select coalesce(sum(e72_vlrliq) - sum(e72_vlranu),0) as e37_vlrliq
                        into rNotas
                        from empempitem
                            left join empnotaitem    on e62_sequencial = e72_empempitem
                        where e62_sequencial  = rSaldoItens.e62_sequencial;

                    -- selecionamos os valores anulados do item  - encontramos na empanuladoitem
                    select coalesce(sum(e37_vlranu),0) as e37_vlranu,
                            coalesce(sum(e37_qtd),0)    as e37_qtd
                        into rAnulado
                        from empempitem
                            left join empanuladoitem  on e62_sequencial = e37_empempitem
                        where e62_sequencial  = rSaldoItens.e62_sequencial;

                    ------------------- Criamos as linhas do recordset
                    rtp_saldoitensempenho.riNumEmp             := rSaldoItens.e62_numemp;
                    rtp_saldoitensempenho.riSeqItem            := rSaldoItens.e62_sequen;
                    rtp_saldoitensempenho.rnQuantIni           := rSaldoItens.e62_quant;
                    rtp_saldoitensempenho.riCodItem            := rSaldoItens.e62_sequencial;
                    rtp_saldoitensempenho.rsDescr              := rSaldoItens.pc01_descrmater;
                    rtp_saldoitensempenho.rsDescrEmp           := rSaldoItens.e62_descr;
                    rtp_saldoitensempenho.rnSaldoItem          := coalesce(rSaldoItens.e62_quant  - nSaldoItem  - rAnulado.e37_qtd,0);
                    rtp_saldoitensempenho.rnValorIni           := rSaldoItens.e62_vltot;
                    rtp_saldoitensempenho.rnSaldoValor         := coalesce((rSaldoItens.e62_vltot - nSaldoValor - rAnulado.e37_vlranu)::numeric,0);
                    rtp_saldoitensempenho.rnValorUni           := rSaldoItens.e62_vlrun;
                    rtp_saldoitensempenho.rnSaldoEstoque       := coalesce(nSaldoEstoque,0);
                    rtp_saldoitensempenho.rnValorEstoque       := coalesce(nValorEstoque,0);
                    rtp_saldoitensempenho.rnSaldoOrdem         := coalesce(nSaldoOrdem,0);
                    rtp_saldoitensempenho.rnValorOrdem         := coalesce(nValorOrdem,0);
                    rtp_saldoitensempenho.riCodMater           := rSaldoItens.e62_item;
                    rtp_saldoitensempenho.riCodEmp             := rSaldoItens.e60_codemp;
                    rtp_saldoitensempenho.riAnoEmp             := rSaldoItens.e60_anousu;
                    rtp_saldoitensempenho.rnValorLiq           := rNotas.e37_vlrliq;
                    rtp_saldoitensempenho.rnValorAnul          := rAnulado.e37_vlranu;
                    rtp_saldoitensempenho.rlControlaQuantidade := rSaldoItens.e62_servicoquantidade;
                    -- se o item for servico, e o quantidade do empenho for 1 sempre teremos  1 item  de saldo do item. apenas alteramos valor.
                    if rSaldoItens.pc01_servico is true then

                        /**
                        * Caso for servico e este nao for controlado por quantidade, setamos o saldo para 1 sempre
                        */
                        if rSaldoItens.e62_servicoquantidade is false then

                        rtp_saldoitensempenho.rnSaldoItem    := 1;
                        rtp_saldoitensempenho.rnSaldoOrdem   := 1;

                        end if;

                        rtp_saldoitensempenho.rnSaldoEstoque := 1;

                    end if;

                    if bRaise then
                        raise notice 'Calculando item % quant: % saldo:%', rSaldoItens.pc01_descrmater,rSaldoItens.e62_quant, coalesce(nSaldoItem,0);
                        raise notice '    Saldo Estoque: % Valor Estoque: % saldo Ordem:% valor ordem: %',nSaldoEstoque, nValorEstoque,nSaldoEstoque, nValorEstoque;
                    end if;

                    if rtp_saldoitensempenho.rnSaldoEstoque = (rtp_saldoitensempenho.rnQuantIni -rAnulado.e37_qtd) then

                        rtp_saldoitensempenho.rnSaldoEntradaEmpenho := round(rtp_saldoitensempenho.rnValorIni,2) -- round(rtp_saldoitensempenho.rnValorEstoque, 2)
                                                                    - round(rAnulado.e37_vlranu, 2);
                        /**
                        * Calculamos o valor a abater do item. caso o saldo do valor for negativo, indica que tivemos uma anulacao
                        * do item do sem solicitacao de anulacao. esse caso so deveriam ocorrer quando a o empenho estiver entrado
                        * totalmente no estoque, e sobrou alguns centavos para anulacao do empenho e o usuario realizou a anulacao
                        * diretamente.
                        */
                        rtp_saldoitensempenho.rnSaldoEntradaEmpenho :=  rtp_saldoitensempenho.rnSaldoEntradaEmpenho - round(abs(rtp_saldoitensempenho.rnSaldoValor),2);
                        if rtp_saldoitensempenho.rnSaldoEntradaEmpenho < 0  or rtp_saldoitensempenho.rnValorOrdem = 0 then
                        rtp_saldoitensempenho.rnSaldoEntradaEmpenho = 0;
                        end if;
                        if rtp_saldoitensempenho.rnSaldoValor < 0 then
                        rtp_saldoitensempenho.rnSaldoValor = 0;
                        end if;
                        else
                        rtp_saldoitensempenho.rnSaldoEntradaEmpenho := 0;--rtp_saldoitensempenho.rnSaldoOrdem;
                        end if;
                    return next rtp_saldoitensempenho;
                end loop;
            return ;
        end;
        \$function\$;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->excluiParametro();
        $this->excluiFuncoes();
    }

    protected function excluiParametro()
    {
        Schema::table('empparametro', function (Blueprint $table) {
            $table->dropColumn('e30_anularpprocessado');
        });

        DB::table('db_sysarqcamp')
        ->where('codarq', DB::table('db_sysarquivo')->where('nomearq', 'empparametro')->value('codarq'))
        ->where('codcam', DB::table('db_syscampo')->where('nomecam', 'e30_anularpprocessado')->value('codcam'))
        ->delete();

        DB::table('db_syscampo')
        ->where('nomecam', 'e30_anularpprocessado')
        ->delete();
    }

    protected function excluiFuncoes()
    {
        DB::statement("DROP FUNCTION IF EXISTS public.fc_saldoitensempenho_anulacao(integer)");
        DB::statement("DROP FUNCTION IF EXISTS public.fc_saldoitens_anulacao(integer)");
    }
}
