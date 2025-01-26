<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16737Titulo extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            begin;
                update conplano	set c60_descr = 'BANCOS CONTA MOVIMENTO ? FUNDO EM REPARTI��O      ' where c60_estrut = '111110602000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'BANCOS CONTA MOVIMENTO ? FUNDO EM CAPITALIZA��O   ' where c60_estrut = '111110603000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'EMPR�STIMOS A RECEBER RPPS FUNDO EM CAPITALIZA��O ' where c60_estrut = '112410701000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FINANCIAMENTOS A RECEBER  RPPS FUNDO CAPITALIZA��O' where c60_estrut = '112410702000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'JUROS ENC. SOBRE EMPR�ST. A RECEBER RPPS FUNDO CAP' where c60_estrut = '112410703000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'JUROS ENC FINANCIAMENTOS A RECEBER RPPS FUNDO CAPI' where c60_estrut = '112410704000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'DEP. RESTITU�VEIS E VALORES VINCULADOS RECEBER    ' where c60_estrut = '113500000000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'EMPR�STIMOS A RECEBER RPPS PLANO EM CAPITALIZA��O ' where c60_estrut = '121140305000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'JUROS ENC SOBRE EMPR�ST RECEBER RPPS PLANO CAPITAL' where c60_estrut = '121140306000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FINANCIAMENTOS A RECEBER RPPS PLANO EM CAPITALIZA�' where c60_estrut = '121140307000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'JUROS ENC FINANCIA. RECEBER RPPS PLANO CAPITALIZA�' where c60_estrut = '121140308000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'TITULOS VALORES MOBILI�RIOS RPPS PLANO CAPITALIZA�' where c60_estrut = '122310100000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'APLICA��ES SEGMENTO IM�VEIS RPPS PLANO CAPITALIZA�' where c60_estrut = '122310200000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) REDUZ. VALOR RECUP INVEST RPPS FUNDO CAPITALIZ' where c60_estrut = '122910300000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'RESSARCIMENTOS E RESTITUI��ES                     ' where c60_estrut = '218810105000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FUNDO REPARTI��O PROVIS�ES BENEF�CIOS CONCEDIDOS  ' where c60_estrut = '227210100000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'APOSENT/PENS/OUTROS BENEF CONCED FUNDO REPART RPPS' where c60_estrut = '227210101000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIBUI��ES ENTE PARA FUNDO REPARTI��O RPPS ' where c60_estrut = '227210102000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIB APOSENTADO PARA FUNDO REPARTI��O RPPS ' where c60_estrut = '227210103000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIB PENSIONISTA PARA FUNDO REPARTI��O RPPS' where c60_estrut = '227210104000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) COMPENS PREVIDENCI�RIA FUNDO REPARTI��O RPPS  ' where c60_estrut = '227210105000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FUNDO REPARTI��O PROVISOES DE BENEFICIOS CONCEDER ' where c60_estrut = '227210200000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'APOSENT/PENS/OUTROS BENEF CONCED FUNDO REPART RPPS' where c60_estrut = '227210201000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIB ENTE PARA FUNDO EM REPARTI��O DO RPPS ' where c60_estrut = '227210202000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIB ATIVO PARA FUNDO EM REPARTI��O DO RPPS' where c60_estrut = '227210203000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) COMPENS PREVIDENCI�RIA FUNDO REPARTI��O RPPS  ' where c60_estrut = '227210204000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FUNDO DE CAPITALIZ PROVISOES DE BENEFICIOS CONCED ' where c60_estrut = '227210300000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'APOSENT/PENS/OUTROS BENEF CONC FUNDO CAPILA RPPS  ' where c60_estrut = '227210301000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIB ENTE PARA O FUNDO DE CAPITALIZ DO RPPS' where c60_estrut = '227210302000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIB APOSENTADO PARA FUNDO CAPITALIZ  RPPS ' where c60_estrut = '227210303000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIBUI��ES PENSIO PARA FUNDO CAPITALIZ RPPS' where c60_estrut = '227210304000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) COMPENSA��O PREVIDENC FUNDO CAPITALIZ DO RPPS ' where c60_estrut = '227210305000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FUNDO CAPITALIZ PROVISOES DE BENEFICIOS A CONCEDER' where c60_estrut = '227210400000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'APOSENT/PENS/OUT BENEF CONCEDER FUNDO CAPITAL RPPS' where c60_estrut = '227210401000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIBUI��ES ENTE PARA O FUNDO EM CAPIT RPPS ' where c60_estrut = '227210402000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) CONTRIBUI��ES ATIVO PARA FUNDO CAPITALIZ RPPS ' where c60_estrut = '227210403000000' and c60_anousu > 2021;
                update conplano	set c60_descr = '(-) COMPENSA��O PREVID FUNDO EM CAPITALIZ DO RPPS ' where c60_estrut = '227210404000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FUNDO EM CAPITALIZ PLANO DE AMORTIZACAO           ' where c60_estrut = '227210500000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'PROVIS�ES ATUARIAIS PARA AJUSTES FUNDO REPARTI��O ' where c60_estrut = '227210600000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'PROVIS�ES ATUARIAIS PARA AJUSTES FUNDO CAPITALIZ  ' where c60_estrut = '227210700000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'OBRIGA��ES DECOR CONTRATOS PPP CONSOL LONGO PRAZO ' where c60_estrut = '228610000000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'OBRIGA��ES DECOR ATIVOS CONSTRU�  SPE LONGO PRAZO ' where c60_estrut = '228610100000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'RESSARCIMENTOS E RESTITUI��ES                     ' where c60_estrut = '228810105000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'TRANSFER�NCIAS CONCEDIDAS DE BENS IM�VEIS         ' where c60_estrut = '351220202000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'TRANSFER�NCIAS CONCEDIDAS DE BENS M�VEIS          ' where c60_estrut = '351220204000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FUNDO EM REPARTI��O                               ' where c60_estrut = '351320100000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FUNDO EM CAPITALIZ                                ' where c60_estrut = '351320200000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'TRANSFER�NCIAS RECEBIDAS DE BENS IM�VEIS          ' where c60_estrut = '451220202000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'TRANSFER�NCIAS RECEBIDAS DE BENS M�VEIS           ' where c60_estrut = '451220204000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FUNDO EM REPARTI��O                               ' where c60_estrut = '451320100000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'FUNDO EM CAPITALIZ                                ' where c60_estrut = '451320200000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'OUTRAS GARANTIAS CONCEDIDAS NO EXTERIOR EXECUTADAS' where c60_estrut = '812120221000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'EXECU��O DE GARANTIAS CONCEDIDAS NO EXTERIOR      ' where c60_estrut = '812130200000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'OUTRAS GARANTIAS CONCEDIDAS NO EXTERIOR EXECUTADAS' where c60_estrut = '812130221000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'EXECU��O DE GARANTIAS CONCEDIDAS NO EXTERIOR      ' where c60_estrut = '812140200000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'OUTRAS GARANTIAS CONCEDIDAS NO EXTERIOR EXECUTADAS' where c60_estrut = '812140221000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'EXECU��O DE GARANTIAS CONCEDIDAS NO EXTERIOR      ' where c60_estrut = '812150200000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'OUTRAS GARANTIAS CONCEDIDAS NO EXTERIOR EXECUTADAS' where c60_estrut = '812150221000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'UTILIZADA COM EXECU��O OR�AMENT�RIA               ' where c60_estrut = '821140100000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'OUTROS TRIBUTOS FEDERAIS                          ' where c60_estrut = '228830106000000' and c60_anousu > 2021;
                update conplano	set c60_descr = 'OUTROS TRIBUTOS FEDERAIS                          ' where c60_estrut = '218830106000000' and c60_anousu > 2021;
            commit;
SQL;
        $this->execute($sql);
    }
}
