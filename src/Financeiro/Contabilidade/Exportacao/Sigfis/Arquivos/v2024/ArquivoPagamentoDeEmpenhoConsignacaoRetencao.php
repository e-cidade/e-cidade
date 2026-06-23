<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use BusinessException;
use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Common\Helper;
use Illuminate\Database\Capsule\Manager as DB;
use stdClass;

class ArquivoPagamentoDeEmpenhoConsignacaoRetencao extends ArquivoBase
{
    protected $sNomeArquivo = 'PagamentoDeEmpenhoConsignacaoRetencao';

    public function testa($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public function buscaLiquidacoes($seqempenho, $codord){
        $inst = $this->instit;
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;
        $ano = $this->iAnoUsu;

        $sql = pg_query("SELECT e23_sequencial as IdentificadorRetencao, empempenho.e60_codemp as NumeroEmpenho, empempenho.e60_anousu as AnoEmpenho, orcdotacao.o58_orgao as CodigoOrgao, orcdotacao.o58_unidade as CodigoUnidadeOrcamentaria, empnota.e69_numero as NumeroNota, k12_data as DataPagamento, e32_sequencial as Tipo, e23_valorretencao as ValorPago, cgm.z01_nome as NomeCredor, cgm.z01_cgccpf as CNPJCredor, cgmusu.z01_cgccpf as CPFResponsavel, empnota.e69_codnota as IdentificadorLiquidacao, empnota.e69_anousu as AnoLiquidacaoEmpenho, db89_db_bancos as Banco, db89_codagencia as Agencia, db83_conta as ContaBancaria, e50_codord, e60_numemp, e69_codnota from retencaoreceitas inner join retencaotiporec on e21_sequencial = e23_retencaotiporec inner join retencaotipocalc on e32_sequencial = e21_retencaotipocalc inner join retencaotiporeccgm on e48_retencaotiporec = e21_sequencial inner join retencaopagordem on e23_retencaopagordem = e20_sequencial inner join retencaoempagemov on e27_retencaoreceitas = e23_sequencial inner join empagemov on e81_codmov = e27_empagemov inner join pagordem on e50_codord = e20_pagordem inner join pagordemnota on e71_codord = e50_codord inner join empempenho on e60_numemp = e50_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join empnota on e69_codnota = e71_codnota inner join db_usuacgm on db_usuacgm.id_usuario = e50_id_usuario inner join cgm as cgmusu on cgmlogin = cgmusu.z01_numcgm inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm left join empagemovslips on k107_retencao = e23_sequencial left join slipempagemovslips on k108_empagemovslips = k107_sequencial left join slip on k17_codigo = k108_slip left join empageformacgm on e28_numcgm = empempenho.e60_numcgm left join empagetipo on e83_codtipo = empageformacgm.e28_empagetipo inner join saltes on saltes.k13_conta = coalesce(slip.k17_credito, e83_conta) inner join conplanocontabancaria on c56_reduz = saltes.k13_reduz and c56_anousu = e60_anousu inner join contabancaria on contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria inner join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia inner join retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial inner join corgrupocorrente on k105_sequencial = e47_corgrupocorrente inner join corrente on k105_sequencial = e47_corgrupocorrente and k105_id = k12_id and k105_autent = k12_autent and k105_data = k12_data where e23_ativo is true and e60_instit = {$inst} and e60_anousu = {$ano} and k12_estorn is false and k12_data between '{$di}' and '{$df}' AND e60_numemp = {$seqempenho} AND e50_codord = {$codord} order by e60_numemp asc");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function xbuscaLiquidacoes($seqempenho, $codord, $tipo){
        $inst = $this->instit;
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;
        $ano = $this->iAnoUsu;

        $sql = pg_query("SELECT e23_sequencial as IdentificadorRetencao, empempenho.e60_codemp as NumeroEmpenho, empempenho.e60_anousu as AnoEmpenho, orcdotacao.o58_orgao as CodigoOrgao, orcdotacao.o58_unidade as CodigoUnidadeOrcamentaria, empnota.e69_numero as NumeroNota, k12_data as DataPagamento, e32_sequencial as Tipo, e23_valorretencao as ValorPago, cgm.z01_nome as NomeCredor, cgm.z01_cgccpf as CNPJCredor, cgmusu.z01_cgccpf as CPFResponsavel, empnota.e69_codnota as IdentificadorLiquidacao, empnota.e69_anousu as AnoLiquidacaoEmpenho, db89_db_bancos as Banco, db89_codagencia as Agencia, db83_conta as ContaBancaria, e50_codord, e60_numemp, e69_codnota from retencaoreceitas inner join retencaotiporec on e21_sequencial = e23_retencaotiporec inner join retencaotipocalc on e32_sequencial = e21_retencaotipocalc inner join retencaotiporeccgm on e48_retencaotiporec = e21_sequencial inner join retencaopagordem on e23_retencaopagordem = e20_sequencial inner join retencaoempagemov on e27_retencaoreceitas = e23_sequencial inner join empagemov on e81_codmov = e27_empagemov inner join pagordem on e50_codord = e20_pagordem inner join pagordemnota on e71_codord = e50_codord inner join empempenho on e60_numemp = e50_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join empnota on e69_codnota = e71_codnota inner join db_usuacgm on db_usuacgm.id_usuario = e50_id_usuario inner join cgm as cgmusu on cgmlogin = cgmusu.z01_numcgm inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm left join empagemovslips on k107_retencao = e23_sequencial left join slipempagemovslips on k108_empagemovslips = k107_sequencial left join slip on k17_codigo = k108_slip left join empageformacgm on e28_numcgm = empempenho.e60_numcgm left join empagetipo on e83_codtipo = empageformacgm.e28_empagetipo inner join saltes on saltes.k13_conta = coalesce(slip.k17_credito, e83_conta) inner join conplanocontabancaria on c56_reduz = saltes.k13_reduz and c56_anousu = e60_anousu inner join contabancaria on contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria inner join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia inner join retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial inner join corgrupocorrente on k105_sequencial = e47_corgrupocorrente inner join corrente on k105_sequencial = e47_corgrupocorrente and k105_id = k12_id and k105_autent = k12_autent and k105_data = k12_data where e23_ativo is true and e60_instit = {$inst} and e60_anousu = {$ano} and k12_estorn is false and k12_data between '{$di}' and '{$df}' AND e60_numemp = {$seqempenho} AND e50_codord = {$codord} AND e21_tiporet = {$tipo} order by e60_numemp asc");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function buscaaconta($codnota){
    $sql = pg_query("SELECT e60_numemp, e69_codnota, e69_numero, c70_codlan, c70_data, c70_valor, db90_codban, db89_codagencia, db89_digito, db83_conta, db83_dvconta from empempenho inner join empnota on e69_numemp = e60_numemp inner join pagordemnota on e71_codnota = e69_codnota inner join conlancamemp on c75_numemp = e60_numemp inner join conlancam on c75_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conlancamcompl on c72_codlan = c70_codlan inner join conlancamord on c80_codlan = c70_codlan and c80_codord = e71_codord inner join lancamentoscontabeislog as log on codlan = c70_codlan and tipo_movimento = 1 inner join db_usuacgm on db_usuacgm.id_usuario = log.id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin inner join conlancampag on c82_codlan = c70_codlan inner join contabilidade.conplanoreduz on c61_reduz = c82_reduz and c61_anousu = c82_anousu inner join contabilidade.conplanocontabancaria on c56_reduz = c61_reduz and c56_anousu = c61_anousu inner join configuracoes.contabancaria on c56_contabancaria = db83_sequencial inner join configuracoes.bancoagencia on db89_sequencial = db83_bancoagencia inner join configuracoes.db_bancos on db90_codban = db89_db_bancos where e69_codnota = {$codnota}");
    $resultado = pg_fetch_all($sql);
    return $resultado[0];
}

public function buscaDadosBancarios4($seqempenho, $codord){
    $sql = pg_query("SELECT c70_codlan, c70_data, c53_descr, case when c127_conlancam is not null then 'Sim' else 'Não' end as dl_Lançamento_Retenção, c70_valor, c82_reduz, c60_descr, c72_complem, e69_numero as dl_Nota_Fiscal, e50_codord, e50_data, nomeinstabrev as dl_Ente from conlancamemp inner join conlancam on c70_codlan = c75_codlan inner join empempenho on c75_numemp = e60_numemp inner join conlancamordem on conlancamordem.c03_codlan = conlancam.c70_codlan left outer join conlancampag on c82_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc left join conlancamcompl on c72_codlan =c70_codlan left join conlancamnota on c66_codlan =c70_codlan left join conlancamord on c80_codlan =c70_codlan left join empnota on c66_codnota = e69_codnota left join conplanoreduz on c61_reduz = conlancampag.c82_reduz and c61_anousu=c70_anousu left join conplano on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=c61_anousu left join pagordem on e50_codord = c80_codord inner join conlancaminstit on c02_codlan = c70_codlan inner join db_config on c02_instit = db_config.codigo left join conlancamretencao on c127_conlancam = c70_codlan where c75_numemp = {$seqempenho} AND e50_codord = {$codord} AND c82_reduz is not null order by c75_data, c03_ordem, c75_codlan");
        $resultado = pg_fetch_all($sql);
        $codlan = $resultado[0]["c70_codlan"];

    $sql2 = pg_query("SELECT db90_codban, db89_codagencia, db89_digito, db83_conta, db83_dvconta from empempenho inner join empnota on e69_numemp = e60_numemp inner join pagordemnota on e71_codnota = e69_codnota inner join conlancamemp on c75_numemp = e60_numemp inner join conlancam on c75_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conlancamcompl on c72_codlan = c70_codlan inner join conlancamord on c80_codlan = c70_codlan and c80_codord = e71_codord inner join lancamentoscontabeislog as log on codlan = c70_codlan and tipo_movimento = 1 inner join db_usuacgm on db_usuacgm.id_usuario = log.id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin inner join conlancampag on c82_codlan = c70_codlan inner join contabilidade.conplanoreduz on c61_reduz = c82_reduz and c61_anousu = c82_anousu inner join contabilidade.conplanocontabancaria on c56_reduz = c61_reduz and c56_anousu = c61_anousu inner join configuracoes.contabancaria on c56_contabancaria = db83_sequencial inner join configuracoes.bancoagencia on db89_sequencial = db83_bancoagencia inner join configuracoes.db_bancos on db90_codban = db89_db_bancos where e60_numemp = {$seqempenho} AND c70_codlan = {$codlan}");
        $resultado2 = pg_fetch_all($sql2);
    return $resultado2[0];
}
//====================================================================================================

//Busca só os empenhos
    public function retornaEmpenhosPagos(){
        $inst = $this->instit;
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;
        $ano = $this->iAnoUsu;

        $sql = pg_query("SELECT DISTINCT e60_numemp, e60_codemp, e50_codord FROM coremp inner join empempenho on e60_numemp = k12_empen and e60_instit = {$inst} inner join orcdotacao on e60_coddot = o58_coddot and e60_anousu = o58_anousu inner join pagordem on e50_codord = k12_codord left join pagordemconta on e50_codord = e49_codord inner join corrente on corrente.k12_id = coremp.k12_id and corrente.k12_data = coremp.k12_data and corrente.k12_autent = coremp.k12_autent inner join cgm on cgm.z01_numcgm = e60_numcgm left join cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm inner join saltes on saltes.k13_conta = corrente.k12_conta left join corgrupocorrente on k105_id = corrente.k12_id and k105_data = corrente.k12_data and k105_autent = corrente.k12_autent left join corgrupotipo on k106_sequencial = k105_corgrupotipo where coremp.k12_data between '{$di}' and '{$df}' AND e60_anousu = {$ano} order by e60_numemp");

        $resultado = array();

        while ($linha = pg_fetch_object($sql)) {
            $resultado[] = $linha;
        }
        return $resultado;
    }

    //Depois joga o empenho aqui
    public function retornaDadosDoEmpenhoPago($seqempenho){
        $inst = $this->instit;
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;
        $ano = $this->iAnoUsu;

        $sql = pg_query("SELECT coremp.k12_empen, e60_numemp, e60_codemp, case when e49_numcgm is null then e60_numcgm else e49_numcgm end as e60_numcgm, k12_codord as e50_codord, case when e49_numcgm is null then cgm.z01_nome else cgmordem.z01_nome end as z01_nome, k12_valor, k12_cheque, e60_anousu, coremp.k12_autent, coremp.k12_data, k13_conta, k13_descr, k106_sequencial, e60_coddot from coremp inner join empempenho on e60_numemp = k12_empen and e60_instit = {$inst} inner join orcdotacao on e60_coddot = o58_coddot and e60_anousu = o58_anousu inner join pagordem on e50_codord = k12_codord left join pagordemconta on e50_codord = e49_codord inner join corrente on corrente.k12_id = coremp.k12_id and corrente.k12_data = coremp.k12_data and corrente.k12_autent = coremp.k12_autent inner join cgm on cgm.z01_numcgm = e60_numcgm left join cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm inner join saltes on saltes.k13_conta = corrente.k12_conta left join corgrupocorrente on k105_id = corrente.k12_id and k105_data = corrente.k12_data and k105_autent = corrente.k12_autent left join corgrupotipo on k106_sequencial = k105_corgrupotipo where e60_numemp = {$seqempenho} and coremp.k12_data between '{$di}' and '{$df}' AND e60_anousu = {$ano} AND k12_valor > 0 order by e60_codemp, k12_data, k13_conta");

        $resultado = array();

        while($linha = pg_fetch_object($sql)){
            $resultado[] = $linha;
        }
        return $resultado;
    }

    public function retornaNF($codord){
        $sql = pg_query("SELECT e69_numero, e69_codnota from pagordemnota inner join pagordem on pagordem.e50_codord = pagordemnota.e71_codord inner join empnota on empnota.e69_codnota = pagordemnota.e71_codnota inner join empempenho on empempenho.e60_numemp = pagordem.e50_numemp inner join db_usuarios on db_usuarios.id_usuario = empnota.e69_id_usuario inner join empempenho as a on a.e60_numemp = empnota.e69_numemp where pagordemnota.e71_codord = {$codord}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function retornaDadosBancarios($codconta){
        $sql = pg_query("SELECT distinct c63_banco, c63_agencia, c63_conta from saltes join conplanoreduz on conplanoreduz.c61_reduz = saltes.k13_reduz and c61_anousu= 2025 join conplanoexe on conplanoexe.c62_reduz = conplanoreduz.c61_reduz and c61_anousu=c62_anousu join conplano on conplanoreduz.c61_codcon = conplano.c60_codcon and c61_anousu=c60_anousu left join conplanoconta on conplanoconta.c63_codcon = conplanoreduz.c61_codcon and conplanoconta.c63_anousu = conplanoreduz.c61_anousu and conplanoconta.c63_reduz = conplanoreduz.c61_reduz left join empagetipo on empagetipo.e83_conta = saltes.k13_conta join orctiporec on o15_codigo = c61_codigo join fonterecurso on orctiporec_id = o15_codigo and exercicio = c61_anousu WHERE k13_conta = {$codconta}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function retornaDadosDotacao($ano, $coddot){
        $inst = $this->instit;        
        $sql = pg_query("SELECT o58_orgao, o58_unidade FROM orcdotacao WHERE o58_anousu = {$ano} AND o58_coddot = {$coddot} AND o58_instit = {$inst}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function retornaRetencao($codord){
        $sql = pg_query("SELECT e23_sequencial, e23_valorretencao, e23_dtcalculo, e20_data, e21_tiporet FROM retencaoreceitas INNER JOIN retencaopagordem ON e23_retencaopagordem = e20_sequencial INNER JOIN retencaotiporec ON e23_retencaotiporec = e21_sequencial WHERE e20_pagordem = {$codord}");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }


//====================================================================================================
//====================================================================================================
//====================================================================================================
//====================================================================================================


public function buscaEmpenhosRP(){
    $inst = $this->instit;
    $di = $this->dtDataInicial;
    $df = $this->dtDataFinal;
    $ano = $this->iAnoUsu;
    /*
    $sql = pg_query("SELECT empempenho.e60_numemp::integer as e60_numemp, e60_resumo, e60_destin, e60_codemp, e60_emiss, e60_numcgm, z01_nome, z01_cgccpf, z01_munic, e60_vlremp, e60_vlranu, e60_vlrliq, e63_codhist, e40_descr, e60_vlrpag, e60_anousu, e60_coddot, o58_coddot, o58_orgao, o40_orgao, o40_descr, o58_unidade, o41_descr, o15_codigo, o15_descr, fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural, e60_codcom, pc50_descr, sum(c70_valor) as c70_valor, c70_data, c70_codlan, c53_tipo, c53_descr, (select o56_elemento||'-'||o56_descr from orcelemento where o56_codele = c67_codele and o56_anousu = c70_anousu) as desdobramento, c72_complem, z01_incest, z01_cgccpf, c66_codnota, e91_numemp, c70_anousu from empempenho inner join conlancamemp on c75_numemp = empempenho.e60_numemp inner join conlancam on c70_codlan = c75_codlan left join conlancamnota on c66_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm inner join db_config on db_config.codigo = empempenho.e60_instit inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu and orcdotacao.o58_coddot = empempenho.e60_coddot and orcdotacao.o58_instit = empempenho.e60_instit inner join emptipo on emptipo.e41_codtipo = empempenho.e60_codtipo inner join db_config as a on a.codigo = orcdotacao.o58_instit INNER JOIN origemcomplementorecurso ON origemcomplementorecurso.o206_numero = empempenho.e60_numemp AND origemcomplementorecurso.o206_origem = 1 INNER JOIN orctiporec ON orctiporec.o15_codigo = origemcomplementorecurso.o206_recurso inner join orcfuncao on orcfuncao.o52_funcao = orcdotacao.o58_funcao inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao inner join orcprograma on orcprograma.o54_anousu = orcdotacao.o58_anousu and orcprograma.o54_programa = orcdotacao.o58_programa inner join orcelemento on orcelemento.o56_codele = orcdotacao.o58_codele and orcdotacao.o58_anousu = orcelemento.o56_anousu inner join orcprojativ on orcprojativ.o55_anousu = orcdotacao.o58_anousu and orcprojativ.o55_projativ = orcdotacao.o58_projativ inner join orcorgao on orcorgao.o40_anousu = orcdotacao.o58_anousu and orcorgao.o40_orgao = orcdotacao.o58_orgao inner join orcunidade on orcunidade.o41_anousu = orcdotacao.o58_anousu and orcunidade.o41_orgao = orcdotacao.o58_orgao and orcunidade.o41_unidade = orcdotacao.o58_unidade left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp left join emphist on emphist.e40_codhist = empemphist.e63_codhist inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom left join empresto on e60_numemp = e91_numemp and e60_anousu = e91_anousu left join conlancamcompl on c72_codlan = c70_codlan inner join conlancamele on c67_codlan = c70_codlan where c53_tipo in (30) and e60_anousu < {$ano} and c70_data between '{$di}' and '{$df}' and 1=1 and e60_instit in ({$inst}) group by e60_numemp, e60_resumo, e60_destin, e60_codemp, e60_emiss, e60_numcgm, z01_nome, z01_cgccpf, z01_munic, e60_vlremp, e60_vlranu, e60_vlrliq, e63_codhist, e40_descr, e60_vlrpag, e60_anousu, e60_coddot, o58_coddot, o58_orgao, o40_orgao, o40_descr, o58_unidade, o41_descr, o15_codigo, o15_descr, e60_codcom, pc50_descr, c70_data, c70_codlan, c53_tipo, c53_descr, desdobramento, c72_complem, z01_incest, z01_cgccpf, c66_codnota, e91_numemp, c70_anousu order by e60_numemp, c70_codlan");
    */
    //$sql = pg_query("SELECT empempenho.e60_numemp::integer as e60_numemp, e60_resumo, e60_destin, e60_codemp, e60_emiss, e60_numcgm, z01_nome, z01_cgccpf, z01_munic, e60_vlremp, e60_vlranu, e60_vlrliq, e63_codhist, e40_descr, e60_vlrpag, e60_anousu, e60_coddot, o58_coddot, o58_orgao, o40_orgao, o40_descr, o58_unidade, o41_descr, o15_codigo, o15_descr, fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural, e60_codcom, pc50_descr, sum(c70_valor) as c70_valor, c70_data, c70_codlan, c53_tipo, c53_descr, (select o56_elemento||'-'||o56_descr from orcelemento where o56_codele = c67_codele and o56_anousu = c70_anousu) as desdobramento, c72_complem, z01_incest, z01_cgccpf, c66_codnota, e91_numemp, c70_anousu, c53_coddoc from empempenho inner join conlancamemp on c75_numemp = empempenho.e60_numemp inner join conlancam on c70_codlan = c75_codlan left join conlancamnota on c66_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm inner join db_config on db_config.codigo = empempenho.e60_instit inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu and orcdotacao.o58_coddot = empempenho.e60_coddot and orcdotacao.o58_instit = empempenho.e60_instit inner join emptipo on emptipo.e41_codtipo = empempenho.e60_codtipo inner join db_config as a on a.codigo = orcdotacao.o58_instit INNER JOIN origemcomplementorecurso ON origemcomplementorecurso.o206_numero = empempenho.e60_numemp AND origemcomplementorecurso.o206_origem = 1 INNER JOIN orctiporec ON orctiporec.o15_codigo = origemcomplementorecurso.o206_recurso inner join orcfuncao on orcfuncao.o52_funcao = orcdotacao.o58_funcao inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao inner join orcprograma on orcprograma.o54_anousu = orcdotacao.o58_anousu and orcprograma.o54_programa = orcdotacao.o58_programa inner join orcelemento on orcelemento.o56_codele = orcdotacao.o58_codele and orcdotacao.o58_anousu = orcelemento.o56_anousu inner join orcprojativ on orcprojativ.o55_anousu = orcdotacao.o58_anousu and orcprojativ.o55_projativ = orcdotacao.o58_projativ inner join orcorgao on orcorgao.o40_anousu = orcdotacao.o58_anousu and orcorgao.o40_orgao = orcdotacao.o58_orgao inner join orcunidade on orcunidade.o41_anousu = orcdotacao.o58_anousu and orcunidade.o41_orgao = orcdotacao.o58_orgao and orcunidade.o41_unidade = orcdotacao.o58_unidade left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp left join emphist on emphist.e40_codhist = empemphist.e63_codhist inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom left join empresto on e60_numemp = e91_numemp and e60_anousu = e91_anousu left join conlancamcompl on c72_codlan = c70_codlan inner join conlancamele on c67_codlan = c70_codlan where c53_tipo in (30,31) and c53_coddoc = 6002 AND e60_numemp not in (select e91_numemp from empresto where e91_anousu = {$ano}) and c53_coddoc not in(31,32,33,34,35,36,37,38,1007) and c70_data between '{$di}' and '{$df}' and 1=1 and e60_instit = {$inst} group by e60_numemp, e60_resumo, e60_destin, e60_codemp, e60_emiss, e60_numcgm, z01_nome, z01_cgccpf, z01_munic, e60_vlremp, e60_vlranu, e60_vlrliq, e63_codhist, e40_descr, e60_vlrpag, e60_anousu, e60_coddot, o58_coddot, o58_orgao, o40_orgao, o40_descr, o58_unidade, o41_descr, o15_codigo, o15_descr, e60_codcom, pc50_descr, c70_data, c70_codlan, c53_tipo, c53_descr, desdobramento, c72_complem, z01_incest, z01_cgccpf, c66_codnota, e91_numemp, c70_anousu, c53_coddoc order by e60_numemp, c70_codlan");
    
        $sql = pg_query("SELECT empempenho.e60_numemp::integer as e60_numemp, e60_resumo, e60_destin, e60_codemp, e60_emiss, e60_numcgm, z01_nome, z01_cgccpf, z01_munic, e60_vlremp, e60_vlranu, e60_vlrliq, e63_codhist, e40_descr, e60_vlrpag, e60_anousu, e60_coddot, o58_coddot, o58_orgao, o40_orgao, o40_descr, o58_unidade, o41_descr, o15_codigo, o15_descr, fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural, e60_codcom, pc50_descr, sum(c70_valor) as c70_valor, c70_data, c70_codlan, c53_tipo, c53_descr, (select o56_elemento||'-'||o56_descr from orcelemento where o56_codele = c67_codele and o56_anousu = c70_anousu) as desdobramento, c72_complem, z01_incest, z01_cgccpf, c66_codnota, e91_numemp, c70_anousu, c53_coddoc from empempenho inner join conlancamemp on c75_numemp = empempenho.e60_numemp inner join conlancam on c70_codlan = c75_codlan left join conlancamnota on c66_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm inner join db_config on db_config.codigo = empempenho.e60_instit inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu and orcdotacao.o58_coddot = empempenho.e60_coddot and orcdotacao.o58_instit = empempenho.e60_instit inner join emptipo on emptipo.e41_codtipo = empempenho.e60_codtipo inner join db_config as a on a.codigo = orcdotacao.o58_instit INNER JOIN origemcomplementorecurso ON origemcomplementorecurso.o206_numero = empempenho.e60_numemp AND origemcomplementorecurso.o206_origem = 1 INNER JOIN orctiporec ON orctiporec.o15_codigo = origemcomplementorecurso.o206_recurso inner join orcfuncao on orcfuncao.o52_funcao = orcdotacao.o58_funcao inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao inner join orcprograma on orcprograma.o54_anousu = orcdotacao.o58_anousu and orcprograma.o54_programa = orcdotacao.o58_programa inner join orcelemento on orcelemento.o56_codele = orcdotacao.o58_codele and orcdotacao.o58_anousu = orcelemento.o56_anousu inner join orcprojativ on orcprojativ.o55_anousu = orcdotacao.o58_anousu and orcprojativ.o55_projativ = orcdotacao.o58_projativ inner join orcorgao on orcorgao.o40_anousu = orcdotacao.o58_anousu and orcorgao.o40_orgao = orcdotacao.o58_orgao inner join orcunidade on orcunidade.o41_anousu = orcdotacao.o58_anousu and orcunidade.o41_orgao = orcdotacao.o58_orgao and orcunidade.o41_unidade = orcdotacao.o58_unidade left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp left join emphist on emphist.e40_codhist = empemphist.e63_codhist inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom left join empresto on e60_numemp = e91_numemp and e60_anousu = e91_anousu left join conlancamcompl on c72_codlan = c70_codlan inner join conlancamele on c67_codlan = c70_codlan where e60_numemp not in (select e91_numemp from empresto where e91_anousu = {$ano}) and c53_coddoc in(161, 6004) and c70_data between '{$di}' and '{$df}' and 1=1 and e60_instit = {$inst} group by e60_numemp, e60_resumo, e60_destin, e60_codemp, e60_emiss, e60_numcgm, z01_nome, z01_cgccpf, z01_munic, e60_vlremp, e60_vlranu, e60_vlrliq, e63_codhist, e40_descr, e60_vlrpag, e60_anousu, e60_coddot, o58_coddot, o58_orgao, o40_orgao, o40_descr, o58_unidade, o41_descr, o15_codigo, o15_descr, e60_codcom, pc50_descr, c70_data, c70_codlan, c53_tipo, c53_descr, desdobramento, c72_complem, z01_incest, z01_cgccpf, c66_codnota, e91_numemp, c70_anousu, c53_coddoc order by e60_numemp, c70_codlan");


    $resultado = array();
    while ($linha = pg_fetch_object($sql)) {
        $resultado[] = $linha;
    }
    return $resultado;
}





public function buscaCodord2($codlan){
    $di = $this->dtDataInicial;
    $df = $this->dtDataFinal;

    $sql = pg_query("SELECT c70_codlan, c70_data, c70_valor, c71_coddoc, c53_descr, c80_codord, c75_numemp, c76_numcgm, z01_nome, e69_numero, e69_codnota, c72_complem, c73_coddot, c74_codrec, c70_anousu, c53_tipo, c67_codele from conlancam inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c71_coddoc = c53_coddoc left join conlancamord on c70_codlan = c80_codlan left join conlancamemp on c75_codlan = c70_codlan left join conlancamcgm on c70_codlan = c76_codlan left join cgm on z01_numcgm = c76_numcgm left join conlancamnota on c70_codlan = c66_codlan left join empnota on c66_codnota = e69_codnota left join conlancamcompl on c70_codlan = c72_codlan left join conlancamdot on c73_codlan = c70_codlan and c73_anousu = c70_anousu left join conlancamele on c67_codlan = c70_codlan left join conlancamrec on c74_codlan = c70_codlan and c74_anousu = c70_anousu where c70_codlan = {$codlan}");
    $resultado = pg_fetch_all($sql);
    
    return $resultado[0]["c80_codord"];
}

public function todosNotaFiscal($seqempenho, $codlan, $data, $valor){
    $inst = $this->instit;
    $sql = pg_query("SELECT c71_coddoc, o58_orgao, o58_unidade, e60_numemp, e60_codemp, e60_anousu, e69_codnota, e69_anousu, e69_numero, c70_codlan, c70_data, c70_valor, c72_complem, z01_nome, z01_cgccpf as cpf from empempenho inner join empnota on e69_numemp = e60_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join pagordemnota on e71_codnota = e69_codnota inner join pagordem on e50_codord = e71_codord inner join conlancamord on c80_codord = e71_codord inner join conlancam on c70_codlan = c80_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conlancamcompl on c72_codlan = c70_codlan inner join lancamentoscontabeislog as log on codlan = c70_codlan and tipo_movimento = 1 inner join db_usuacgm on db_usuacgm.id_usuario = log.id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin where e60_numemp = {$seqempenho} AND e60_instit = {$inst} and c70_data = '{$data}' AND c70_valor = {$valor} order by e60_numemp asc");
    $resultado = pg_fetch_all($sql);
    return $resultado[0];
}


public function buscaTipoRet($data, $seqempenho, $valor){
    //, $codord
    $inst = $this->instit;
    $sql = pg_query("SELECT e21_tiporet, e23_sequencial, empempenho.e60_codemp, empempenho.e60_anousu, orcdotacao.o58_orgao, orcdotacao.o58_unidade, empnota.e69_numero, k12_data, e32_sequencial, e23_valorretencao, cgm.z01_nome, cgm.z01_cgccpf as cnpjcredor, cgmusu.z01_cgccpf, empnota.e69_codnota, empnota.e69_anousu, db89_db_bancos, db89_codagencia, db83_conta, e50_codord, e60_numemp, e69_codnota from retencaoreceitas inner join retencaotiporec on e21_sequencial = e23_retencaotiporec inner join retencaotipocalc on e32_sequencial = e21_retencaotipocalc inner join retencaotiporeccgm on e48_retencaotiporec = e21_sequencial inner join retencaopagordem on e23_retencaopagordem = e20_sequencial inner join retencaoempagemov on e27_retencaoreceitas = e23_sequencial inner join empagemov on e81_codmov = e27_empagemov inner join pagordem on e50_codord = e20_pagordem inner join pagordemnota on e71_codord = e50_codord inner join empempenho on e60_numemp = e50_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join empnota on e69_codnota = e71_codnota inner join db_usuacgm on db_usuacgm.id_usuario = e50_id_usuario inner join cgm as cgmusu on cgmlogin = cgmusu.z01_numcgm inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm left join empagemovslips on k107_retencao = e23_sequencial left join slipempagemovslips on k108_empagemovslips = k107_sequencial left join slip on k17_codigo = k108_slip left join empageformacgm on e28_numcgm = empempenho.e60_numcgm left join empagetipo on e83_codtipo = empageformacgm.e28_empagetipo inner join saltes on saltes.k13_conta = coalesce(slip.k17_credito, e83_conta) inner join conplanocontabancaria on c56_reduz = saltes.k13_reduz and c56_anousu = e60_anousu inner join contabancaria on contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria inner join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia inner join retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial inner join corgrupocorrente on k105_sequencial = e47_corgrupocorrente inner join corrente on k105_sequencial = e47_corgrupocorrente and k105_id = k12_id and k105_autent = k12_autent and k105_data = k12_data where e23_ativo is true and e60_instit = {$inst} and k12_estorn is false and k12_data = '{$data}' AND e60_numemp = {$seqempenho} AND e23_valorretencao = {$valor} order by e60_numemp asc");
    //AND e50_codord = {$codord} 
    $resultado = pg_fetch_all($sql);
    return $resultado[0];
}

public function buscaTipoRet2($seqempenho, $valor){
    //, $codord
    $inst = $this->instit;
    $sql = pg_query("SELECT e21_tiporet, e23_sequencial, empempenho.e60_codemp, empempenho.e60_anousu, orcdotacao.o58_orgao, orcdotacao.o58_unidade, empnota.e69_numero, k12_data, e32_sequencial, e23_valorretencao, cgm.z01_nome, cgm.z01_cgccpf as cnpjcredor, cgmusu.z01_cgccpf, empnota.e69_codnota, empnota.e69_anousu, db89_db_bancos, db89_codagencia, db83_conta, e50_codord, e60_numemp, e69_codnota from retencaoreceitas inner join retencaotiporec on e21_sequencial = e23_retencaotiporec inner join retencaotipocalc on e32_sequencial = e21_retencaotipocalc inner join retencaotiporeccgm on e48_retencaotiporec = e21_sequencial inner join retencaopagordem on e23_retencaopagordem = e20_sequencial inner join retencaoempagemov on e27_retencaoreceitas = e23_sequencial inner join empagemov on e81_codmov = e27_empagemov inner join pagordem on e50_codord = e20_pagordem inner join pagordemnota on e71_codord = e50_codord inner join empempenho on e60_numemp = e50_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join empnota on e69_codnota = e71_codnota inner join db_usuacgm on db_usuacgm.id_usuario = e50_id_usuario inner join cgm as cgmusu on cgmlogin = cgmusu.z01_numcgm inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm left join empagemovslips on k107_retencao = e23_sequencial left join slipempagemovslips on k108_empagemovslips = k107_sequencial left join slip on k17_codigo = k108_slip left join empageformacgm on e28_numcgm = empempenho.e60_numcgm left join empagetipo on e83_codtipo = empageformacgm.e28_empagetipo inner join saltes on saltes.k13_conta = coalesce(slip.k17_credito, e83_conta) inner join conplanocontabancaria on c56_reduz = saltes.k13_reduz and c56_anousu = e60_anousu inner join contabancaria on contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria inner join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia inner join retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial inner join corgrupocorrente on k105_sequencial = e47_corgrupocorrente inner join corrente on k105_sequencial = e47_corgrupocorrente and k105_id = k12_id and k105_autent = k12_autent and k105_data = k12_data where e23_ativo is true and e60_instit = {$inst} and k12_estorn is false AND e60_numemp = {$seqempenho} AND e23_valorretencao = {$valor} order by e60_numemp asc");
    //AND e50_codord = {$codord} 
    $resultado = pg_fetch_all($sql);
    return $resultado[0];
}

public function buscaEmpenhosRP2(){
    $inst = $this->instit;
    $di = $this->dtDataInicial;
    $df = $this->dtDataFinal;
    $ano = $this->iAnoUsu;

    $sql = pg_query("SELECT empempenho.e60_numemp::integer as e60_numemp, e60_resumo, e60_destin, e60_codemp, e60_emiss, e60_numcgm, z01_nome, z01_cgccpf, z01_munic, e60_vlremp, e60_vlranu, e60_vlrliq, e63_codhist, e40_descr, e60_vlrpag, e60_anousu, e60_coddot, o58_coddot, o58_orgao, o40_orgao, o40_descr, o58_unidade, o41_descr, o15_codigo, o15_descr, fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural, e60_codcom, pc50_descr, sum(c70_valor) as c70_valor, c70_data, c70_codlan, c53_tipo, c53_descr, (select o56_elemento||'-'||o56_descr from orcelemento where o56_codele = c67_codele and o56_anousu = c70_anousu) as desdobramento, c72_complem, z01_incest, z01_cgccpf, c66_codnota, e91_numemp, c70_anousu, c53_coddoc from empempenho inner join conlancamemp on c75_numemp = empempenho.e60_numemp inner join conlancam on c70_codlan = c75_codlan left join conlancamnota on c66_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm inner join db_config on db_config.codigo = empempenho.e60_instit inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu and orcdotacao.o58_coddot = empempenho.e60_coddot and orcdotacao.o58_instit = empempenho.e60_instit inner join emptipo on emptipo.e41_codtipo = empempenho.e60_codtipo inner join db_config as a on a.codigo = orcdotacao.o58_instit INNER JOIN origemcomplementorecurso ON origemcomplementorecurso.o206_numero = empempenho.e60_numemp AND origemcomplementorecurso.o206_origem = 1 INNER JOIN orctiporec ON orctiporec.o15_codigo = origemcomplementorecurso.o206_recurso inner join orcfuncao on orcfuncao.o52_funcao = orcdotacao.o58_funcao inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao inner join orcprograma on orcprograma.o54_anousu = orcdotacao.o58_anousu and orcprograma.o54_programa = orcdotacao.o58_programa inner join orcelemento on orcelemento.o56_codele = orcdotacao.o58_codele and orcdotacao.o58_anousu = orcelemento.o56_anousu inner join orcprojativ on orcprojativ.o55_anousu = orcdotacao.o58_anousu and orcprojativ.o55_projativ = orcdotacao.o58_projativ inner join orcorgao on orcorgao.o40_anousu = orcdotacao.o58_anousu and orcorgao.o40_orgao = orcdotacao.o58_orgao inner join orcunidade on orcunidade.o41_anousu = orcdotacao.o58_anousu and orcunidade.o41_orgao = orcdotacao.o58_orgao and orcunidade.o41_unidade = orcdotacao.o58_unidade left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp left join emphist on emphist.e40_codhist = empemphist.e63_codhist inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom left join empresto on e60_numemp = e91_numemp and e60_anousu = e91_anousu left join conlancamcompl on c72_codlan = c70_codlan inner join conlancamele on c67_codlan = c70_codlan where c53_tipo in (30) and e60_numemp not in (select e91_numemp from empresto where e91_anousu = 2025) and c53_coddoc in(6002) and c70_data between '2025-08-01' and '2025-08-31' and 1=1 and e60_instit in (1) group by e60_numemp, e60_resumo, e60_destin, e60_codemp, e60_emiss, e60_numcgm, z01_nome, z01_cgccpf, z01_munic, e60_vlremp, e60_vlranu, e60_vlrliq, e63_codhist, e40_descr, e60_vlrpag, e60_anousu, e60_coddot, o58_coddot, o58_orgao, o40_orgao, o40_descr, o58_unidade, o41_descr, o15_codigo, o15_descr, e60_codcom, pc50_descr, c70_data, c70_codlan, c53_tipo, c53_descr, desdobramento, c72_complem, z01_incest, z01_cgccpf, c66_codnota, e91_numemp, c70_anousu, c53_coddoc order by e60_numemp, c70_codlan");
    //c70_anousu, c53_coddoc
    $resultado = array();
    while ($linha = pg_fetch_object($sql)) {
        $resultado[] = $linha;
    }
    return $resultado;
}

public function buscaEmpenhosRP3(){
    $inst = $this->instit;
    $di = $this->dtDataInicial;
    $df = $this->dtDataFinal;
    $ano = $this->iAnoUsu;

    //$sql = pg_query("SELECT DISTINCT c70_codlan, e60_codemp, e60_numemp, c84_slip, c70_data, c70_valor, o58_orgao, o58_unidade, c80_codord, e60_anousu, retencaotipocalc.e32_sequencial, cgmretencao.z01_nome as nomecgmretencao, cgmretencao.z01_cgccpf as cnpjcgmretencao, cgmusu.z01_numcgm AS cgmusu, cgmusu.z01_cgccpf AS cpfusu, c82_reduz, db89_db_bancos, db89_codagencia, db89_digito, concat(db83_conta, '-', db83_dvconta) as db83_conta, db83_sequencial, e23_sequencial, e21_sequencial FROM conlancam JOIN conlancaminstit ON conlancam.c70_codlan = conlancaminstit.c02_codlan JOIN conlancamdoc ON conlancam.c70_codlan = conlancamdoc.c71_codlan JOIN conhistdoc ON conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc JOIN conlancampag on conlancampag.c82_codlan = conlancam.c70_codlan JOIN conplanocontabancaria on c56_reduz = c82_reduz and c56_anousu = c82_anousu JOIN contabancaria ON contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria JOIN bancoagencia ON bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia JOIN conlancamslip ON conlancamslip.c84_conlancam = conlancam.c70_codlan JOIN slipretencaoreceitas ON k206_slip = c84_slip JOIN retencaoreceitas ON retencaoreceitas.e23_sequencial = slipretencaoreceitas.k206_retencaoreceitas JOIN retencaotiporec ON retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec JOIN retencaotipocalc ON retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc JOIN retencaotiporeccgm ON retencaotiporeccgm.e48_retencaotiporec = retencaotiporec.e21_sequencial JOIN conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan JOIN empempenho ON e60_numemp = c75_numemp JOIN orcdotacao on empempenho.e60_anousu = orcdotacao.o58_anousu and empempenho.e60_coddot = orcdotacao.o58_coddot JOIN cgm as cgmretencao ON z01_numcgm = e48_cgm JOIN lancamentoscontabeislog ON lancamentoscontabeislog.codlan = conlancam.c70_codlan AND lancamentoscontabeislog.tipo_movimento = 1 JOIN db_usuacgm ON db_usuacgm.id_usuario = lancamentoscontabeislog.id_usuario JOIN cgm cgmusu ON cgmusu.z01_numcgm = db_usuacgm.cgmlogin JOIN conlancamord ON conlancam.c70_codlan = conlancamord.c80_codlan WHERE e60_anousu = {$ano} AND c02_instit in ({$inst}) AND c53_tipo = 161 AND c70_data BETWEEN '{$di}' and '{$df}' UNION select distinct 0 as c70_codlan, e60_codemp, e60_numemp, COALESCE(k206_slip, e71_codord) as c84_slip, k12_data, e23_valorretencao, o58_orgao, o58_unidade, e71_codord, e60_anousu, retencaotipocalc.e32_sequencial, cgmretencao.z01_nome as nomecgmretencao, cgmretencao.z01_cgccpf as cnpjcgmretencao, cgmusu.z01_numcgm AS cgmusu, cgmusu.z01_cgccpf AS cpfusu, 0, null, null, null, null, 0, e23_sequencial, e21_sequencial FROM retencaoreceitas JOIN retencaopagordem on retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem JOIN pagordem on pagordem.e50_codord = retencaopagordem.e20_pagordem JOIN pagordemele on pagordemele.e53_codord = pagordem.e50_codord JOIN empempenho on e60_numemp = pagordem.e50_numemp JOIN pagordemnota ON pagordemnota.e71_codord = pagordem.e50_codord and e71_anulado is false JOIN empnota on empnota.e69_numemp = empempenho.e60_numemp JOIN retencaotiporec on retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec JOIN retencaotipocalc on retencaotiporec.e21_retencaotipocalc = retencaotipocalc.e32_sequencial JOIN tabrec ON tabrec.k02_codigo = retencaotiporec.e21_receita JOIN orcdotacao on empempenho.e60_anousu = orcdotacao.o58_anousu and empempenho.e60_coddot = orcdotacao.o58_coddot JOIN retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial JOIN corgrupocorrente on k105_sequencial = e47_corgrupocorrente jOIN corrente on k105_id = corrente.k12_id and k105_autent = corrente.k12_autent and k105_data = corrente.k12_data and corrente.k12_instit = 1 JOIN retencaotiporeccgm ON retencaotiporeccgm.e48_retencaotiporec = retencaotiporec.e21_sequencial JOIN cgm as cgmretencao ON z01_numcgm = e48_cgm JOIN db_usuacgm ON id_usuario = e50_id_usuario JOIN cgm as cgmusu ON cgmlogin = cgmusu.z01_numcgm LEFT JOIN slipretencaoreceitas ON retencaoreceitas.e23_sequencial = slipretencaoreceitas.k206_retencaoreceitas WHERE e60_anousu = {$ano} AND e60_instit in ({$inst}) AND k12_estorn is FALSE AND k12_data BETWEEN '{$di}' and '{$df}' AND e23_ativo IS TRUE AND k02_tipo = 'O'");

    $sql = pg_query("SELECT DISTINCT c70_codlan, e60_codemp, e60_numemp, c84_slip, c70_data, c70_valor, o58_orgao, o58_unidade, c80_codord, e60_anousu, retencaotipocalc.e32_sequencial, cgmretencao.z01_nome as nomecgmretencao, cgmretencao.z01_cgccpf as cnpjcgmretencao, cgmusu.z01_numcgm AS cgmusu, cgmusu.z01_cgccpf AS cpfusu, c82_reduz, db89_db_bancos, db89_codagencia, db89_digito, concat(db83_conta, '-', db83_dvconta) as db83_conta, db83_sequencial, e23_sequencial, e21_sequencial FROM conlancam JOIN conlancaminstit ON conlancam.c70_codlan = conlancaminstit.c02_codlan JOIN conlancamdoc ON conlancam.c70_codlan = conlancamdoc.c71_codlan JOIN conhistdoc ON conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc JOIN conlancampag on conlancampag.c82_codlan = conlancam.c70_codlan JOIN conplanocontabancaria on c56_reduz = c82_reduz and c56_anousu = c82_anousu JOIN contabancaria ON contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria JOIN bancoagencia ON bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia JOIN conlancamslip ON conlancamslip.c84_conlancam = conlancam.c70_codlan JOIN slipretencaoreceitas ON k206_slip = c84_slip JOIN retencaoreceitas ON retencaoreceitas.e23_sequencial = slipretencaoreceitas.k206_retencaoreceitas AND retencaoreceitas.e23_valorretencao = conlancam.c70_valor JOIN retencaotiporec ON retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec JOIN retencaotipocalc ON retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc JOIN retencaotiporeccgm ON retencaotiporeccgm.e48_retencaotiporec = retencaotiporec.e21_sequencial JOIN conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan JOIN empempenho ON e60_numemp = c75_numemp JOIN orcdotacao on empempenho.e60_anousu = orcdotacao.o58_anousu and empempenho.e60_coddot = orcdotacao.o58_coddot JOIN cgm as cgmretencao ON z01_numcgm = e48_cgm JOIN lancamentoscontabeislog ON lancamentoscontabeislog.codlan = conlancam.c70_codlan AND lancamentoscontabeislog.tipo_movimento = 1 JOIN db_usuacgm ON db_usuacgm.id_usuario = lancamentoscontabeislog.id_usuario JOIN cgm cgmusu ON cgmusu.z01_numcgm = db_usuacgm.cgmlogin JOIN conlancamord ON conlancam.c70_codlan = conlancamord.c80_codlan WHERE e60_anousu = {$ano} AND c02_instit in ({$inst}) AND c53_tipo = 161 AND c70_data BETWEEN '{$di}' and '{$df}' UNION select distinct 0 as c70_codlan, e60_codemp, e60_numemp, COALESCE(k206_slip, e71_codord) as c84_slip, k12_data, e23_valorretencao, o58_orgao, o58_unidade, e71_codord, e60_anousu, retencaotipocalc.e32_sequencial, cgmretencao.z01_nome as nomecgmretencao, cgmretencao.z01_cgccpf as cnpjcgmretencao, cgmusu.z01_numcgm AS cgmusu, cgmusu.z01_cgccpf AS cpfusu, 0, null, null, null, null, 0, e23_sequencial, e21_sequencial FROM retencaoreceitas JOIN retencaopagordem on retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem JOIN pagordem on pagordem.e50_codord = retencaopagordem.e20_pagordem JOIN pagordemele on pagordemele.e53_codord = pagordem.e50_codord JOIN empempenho on e60_numemp = pagordem.e50_numemp JOIN pagordemnota ON pagordemnota.e71_codord = pagordem.e50_codord and e71_anulado is false JOIN empnota on empnota.e69_numemp = empempenho.e60_numemp JOIN retencaotiporec on retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec JOIN retencaotipocalc on retencaotiporec.e21_retencaotipocalc = retencaotipocalc.e32_sequencial JOIN tabrec ON tabrec.k02_codigo = retencaotiporec.e21_receita JOIN orcdotacao on empempenho.e60_anousu = orcdotacao.o58_anousu and empempenho.e60_coddot = orcdotacao.o58_coddot JOIN retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial JOIN corgrupocorrente on k105_sequencial = e47_corgrupocorrente jOIN corrente on k105_id = corrente.k12_id and k105_autent = corrente.k12_autent and k105_data = corrente.k12_data and corrente.k12_instit = 1 JOIN retencaotiporeccgm ON retencaotiporeccgm.e48_retencaotiporec = retencaotiporec.e21_sequencial JOIN cgm as cgmretencao ON z01_numcgm = e48_cgm JOIN db_usuacgm ON id_usuario = e50_id_usuario JOIN cgm as cgmusu ON cgmlogin = cgmusu.z01_numcgm LEFT JOIN slipretencaoreceitas ON retencaoreceitas.e23_sequencial = slipretencaoreceitas.k206_retencaoreceitas WHERE e60_anousu = {$ano} AND e60_instit in ({$inst}) AND k12_estorn is FALSE AND k12_data BETWEEN '{$di}' and '{$df}' AND e23_ativo IS TRUE AND k02_tipo = 'O'");



    $resultado = array();
    while ($linha = pg_fetch_object($sql)) {
        $resultado[] = $linha;
    }
    return $resultado;
}


public function getContaBancaria($sequencialRetencao){
    $contaBancaria = new stdClass();
    $contaBancaria->reduzido = null;
    $contaBancaria->db83_sequencial = null;
    $contaBancaria->Banco = null;
    $contaBancaria->Agencia = null;
        
    $reduzido = $this->getContaSlip($sequencialRetencao);        
    if (empty($reduzido)) {
        $reduzido = $this->getContaOP($sequencialRetencao);
    }
    if (empty($reduzido)) {
        $reduzido = $this->getContaEmpenho($sequencialRetencao);
    }
    if (empty($reduzido)) {
        $reduzido = $this->getContaCredor($sequencialRetencao);
    }
    if (!empty($reduzido)) {
        $dadosContaBancaria = $this->getDadosContaBancaria($reduzido);
        
        if (!empty($dadosContaBancaria)) {
            return $dadosContaBancaria;
        }
    }

    return $contaBancaria;
}

private function getContaSlip($sequencialRetencao)
    {
        return DB::table("empagemovslips")
            ->select("slip.k17_credito as reduzido")
            ->join(
                "slipempagemovslips",
                "k108_empagemovslips",
                "k107_sequencial"
            )
            ->join(
                "slip",
                "slip.k17_codigo",
                "k108_slip"
            )
            ->where(
                "k107_retencao",
                $sequencialRetencao
            )
            ->first()->reduzido;
    }

    private function getContaOP($sequencialRetencao)
    {
        return DB::table("retencaoreceitas")
            ->select("c82_reduz as reduzido")
            ->join(
                "retencaopagordem",
                "e20_sequencial",
                "e23_retencaopagordem"
            )
            ->join(
                "conlancamord",
                "c80_codord",
                "e20_pagordem"
            )
            ->join(
                "conlancampag",
                "c82_codlan",
                "c80_codlan"
            )
            ->join(
                "conlancamdoc",
                "c71_codlan",
                "c82_codlan"
            )
            ->join(
                "conhistdoc",
                "c53_coddoc",
                "c71_coddoc"
            )
            ->where(
                "c53_tipo",
                30
            )
            ->where(
                "e23_sequencial",
                $sequencialRetencao
            )
            ->orderBy(
                "c80_data",
                "desc"
            )
            ->first()->reduzido;
    }

    private function getContaEmpenho($sequencialRetencao)
    {
        return DB::table("retencaoreceitas")
            ->select("c82_reduz as reduzido")
            ->join(
                "retencaopagordem",
                "e20_sequencial",
                "e23_retencaopagordem"
            )
            ->join(
                "pagordem",
                "e50_codord",
                "e20_pagordem"
            )
            ->join(
                "conlancamemp",
                "c75_numemp",
                "e50_numemp"
            )
            ->join(
                "conlancampag",
                "c82_codlan",
                "c75_codlan"
            )
            ->join(
                "conlancamdoc",
                "c71_codlan",
                "c82_codlan"
            )
            ->join(
                "conhistdoc",
                "c53_coddoc",
                "c71_coddoc"
            )
            ->where(
                "c53_tipo",
                30
            )
            ->where(
                "e23_sequencial",
                $sequencialRetencao
            )
            ->orderBy(
                "c75_data",
                "desc"
            )
            ->first()->reduzido;
    }

    private function getContaCredor($sequencialRetencao)
    {
        return DB::table("retencaoreceitas")
            ->select("e83_conta as reduzido")
            ->join(
                "retencaopagordem",
                "e20_sequencial",
                "e23_retencaopagordem"
            )
            ->join(
                "pagordem",
                "e50_codord",
                "e20_pagordem"
            )
            ->join(
                "empempenho",
                "e60_numemp",
                "e50_numemp"
            )
            ->join(
                'empageformacgm',
                'e28_numcgm',
                'empempenho.e60_numcgm'
            )
            ->join(
                'empagetipo',
                'e83_codtipo',
                'empageformacgm.e28_empagetipo'
            )
            ->where(
                "e23_sequencial",
                $sequencialRetencao
            )
            ->first()->reduzido;
    }

    private function getDadosContaBancaria($reduzido)
    {
        $contaBancaria = DB::table("conplanocontabancaria")
            ->select(
                'c56_reduz as reduzido',
                'db83_sequencial',
                'db89_db_bancos as Banco',
                'db89_codagencia as Agencia',
                'db83_conta',
                'db83_dvconta'
            )
            ->join(
                'contabancaria',
                'contabancaria.db83_sequencial',
                'conplanocontabancaria.c56_contabancaria'
            )
            ->join(
                'bancoagencia',
                'bancoagencia.db89_sequencial',
                'contabancaria.db83_bancoagencia'
            )
            ->where(
                "c56_reduz",
                $reduzido
            )
            ->where("c56_anousu", $this->iAnoUsu)
            ->first();

        return $contaBancaria;
    }






//====================================================================================================
//====================================================================================================
//====================================================================================================
//====================================================================================================

    public function gerarDados(){
        


        
        //$retencoes = $this->buscaEmpenhosRP();
        $retencoes = $this->buscaEmpenhosRP3();
        //$this->testa($retencoes); die("Confere");

        if(count($retencoes) == 0){throw new \Exception(sprintf('Não foi encontrado nenhum registro para o arquivo da Remessa de %s', 'Pagamento de Empenho - Consig e Retenção'));}
        

        $EmpenhoConsignacaoRetencao = new stdClass();
        $EmpenhoConsignacaoRetencao->PagamentosDeEmpenhoConsignacoesRetencoes = [];

        
        $ix = 1;        
        foreach ($retencoes as $retencao){
            //if($retencao->e60_codemp != 47){continue;}
            //if($retencao->c84_slip != 234290){continue;}
            
            $codord = $retencao->c80_codord;
            
            $nfdados = $this->todosNotaFiscal($retencao->e60_numemp, $retencao->c70_codlan, $retencao->c70_data, $retencao->c70_valor);
            
            $notafiscal = $nfdados["e69_codnota"];
            $numeronota = $nfdados["e69_numero"];
            if(empty($numeronota)){
                $numeronota = "S/N";
            }
            $contaBancaria = $this->getContaBancaria($retencao->e23_sequencial);            
            $tiporetencao = $this->convertTipoRetencao($retencao->e32_sequencial);
            $nomecredor = trim($retencao->nomecgmretencao);
            $cnpjcredor = trim($retencao->cnpjcgmretencao);
            
            
            $ConsigRet = new stdClass();
            $ConsigRet->Identificador = $ix;
            $ConsigRet->CodigoUnidadeGestora = $this->sCodigoTribunal;
            $ConsigRet->Competencia = $this->competencia;
            $ConsigRet->NumeroEmpenho = $retencao->e60_codemp;
            $ConsigRet->AnoEmpenho = $retencao->e60_anousu;
            $ConsigRet->CodigoOrgao = $retencao->o58_orgao;
            $ConsigRet->CodigoUnidadeOrcamentaria = $retencao->o58_unidade;
            $ConsigRet->NumeroNotaPagamentoDeEmpenhoConsignacaoRetencao = $retencao->c84_slip; //$numeronota;
            $ConsigRet->DataPagamento = $retencao->c70_data;
            $ConsigRet->TipoConsignacaoRetencaoPaga = $tiporetencao;
            $ConsigRet->ValorPago = $retencao->c70_valor;
            $ConsigRet->NomeCredor = Helper::convertAndLimit($nomecredor, 255);
            $ConsigRet->CpfCnpjCredor = $cnpjcredor;
            $ConsigRet->NaturezaCredor = (strlen($cnpjcredor)) == 11 ? 2 : 1;
            $ConsigRet->CPF = $retencao->cpfusu;

            $ConsigRet->LiquidacoesDePagamento = [];
            $Liquidacao = new stdClass();
            $Liquidacao->Identificador = $retencao->c70_codlan;
            $Liquidacao->NumeroLiquidacaoEmpenho = $retencao->c80_codord;
            $Liquidacao->AnoLiquidacaoEmpenho = $retencao->e60_anousu;
            $Liquidacao->ValorConsignadoRetidoLiquidacao = $retencao->c70_valor;

            $Liquidacao->ContasPagadoras = [];
            $Conta = new stdClass();
            $Conta->Identificador = $contaBancaria->db83_sequencial;
            $Conta->ValorContaPagadora = $retencao->c70_valor;
            $Conta->Banco = $contaBancaria->Banco;
            $Conta->Agencia = $contaBancaria->Agencia;
            $Conta->ContaBancaria = $contaBancaria->db83_conta;


            $ConsigRet->LiquidacoesDePagamento[] = (object) [
            'PagamentoDeEmpenhoConsignacaoRetencaoLiquidacaoPagamento' => $Liquidacao
            ];

            $Liquidacao->ContasPagadoras[] = (object) [
            'PagamentoDeEmpenhoConsignacaoRetencaoLiquidacaoPagamentoContaPagadora' => $Conta
            ];

            $EmpenhoConsignacaoRetencao->PagamentosDeEmpenhoConsignacoesRetencoes[] = (object) [
            'PagamentoDeEmpenhoConsignacaoRetencao' => $ConsigRet
            ];

            $ix++;
        }
        
        $this->aDados = $EmpenhoConsignacaoRetencao;
    }

    private function getRetencoes()
    {
        $query = DB::table('retencaoreceitas')
            ->select([
                'e23_sequencial as IdentificadorRetencao',
                'empempenho.e60_codemp as NumeroEmpenho',
                'empempenho.e60_anousu as AnoEmpenho',
                'orcdotacao.o58_orgao as CodigoOrgao',
                'orcdotacao.o58_unidade as CodigoUnidadeOrcamentaria',
                'empnota.e69_numero as NumeroNota',
                'k12_data as DataPagamento',
                'e32_sequencial as Tipo',
                'e23_valorretencao as ValorPago',
                'cgm.z01_nome as NomeCredor',
                'cgm.z01_cgccpf as CNPJCredor',
                'cgmusu.z01_cgccpf as CPFResponsavel',
                'empnota.e69_codnota as IdentificadorLiquidacao',
                'empnota.e69_anousu as AnoLiquidacaoEmpenho',
                'db89_db_bancos as Banco',
                'db89_codagencia as Agencia',
                'db83_conta as ContaBancaria',
                'e50_codord',
                'e60_numemp',
                'e21_tiporet',
            ])
            ->join('retencaotiporec', 'e21_sequencial', 'e23_retencaotiporec')
            ->join('retencaotipocalc', 'e32_sequencial', 'e21_retencaotipocalc')
            ->join('retencaotiporeccgm', 'e48_retencaotiporec', 'e21_sequencial')
            ->join('retencaopagordem', 'e23_retencaopagordem', 'e20_sequencial')
            ->join('retencaoempagemov', 'e27_retencaoreceitas', 'e23_sequencial')
            ->join('empagemov', 'e81_codmov', 'e27_empagemov')
            ->join('pagordem', 'e50_codord', 'e20_pagordem')
            ->join('pagordemnota', 'e71_codord', 'e50_codord')
            ->join('empempenho', 'e60_numemp', 'e50_numemp')
            ->join('orcdotacao', function ($join) {
                $join->on('o58_anousu', 'e60_anousu')->on('o58_coddot', 'e60_coddot');
            })
            ->join('empnota', 'e69_codnota', 'e71_codnota')
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'e50_id_usuario')
            ->join('cgm as cgmusu', 'cgmlogin', 'cgmusu.z01_numcgm')
            ->join('cgm', 'cgm.z01_numcgm', 'empempenho.e60_numcgm')
            ->leftJoin('empagemovslips', 'k107_retencao', 'e23_sequencial')
            ->leftJoin('slipempagemovslips', 'k108_empagemovslips', 'k107_sequencial')
            ->leftJoin('slip', 'k17_codigo', 'k108_slip')
            ->leftJoin('empageformacgm', 'e28_numcgm', 'empempenho.e60_numcgm')
            ->leftJoin('empagetipo', 'e83_codtipo', 'empageformacgm.e28_empagetipo')
            ->join('saltes', 'saltes.k13_conta', '=', DB::raw("coalesce(slip.k17_credito, e83_conta)"))
            ->join('conplanocontabancaria', function ($join) {
                $join->on('c56_reduz', 'saltes.k13_reduz')->on('c56_anousu', 'e60_anousu');
            })
            ->join('contabancaria', 'contabancaria.db83_sequencial', 'conplanocontabancaria.c56_contabancaria')
            ->join('bancoagencia', 'bancoagencia.db89_sequencial', 'contabancaria.db83_bancoagencia')
            ->join('retencaocorgrupocorrente', 'e47_retencaoreceita', 'e23_sequencial')
            ->join('corgrupocorrente', 'k105_sequencial', 'e47_corgrupocorrente')
            ->join('corrente', function ($join) {
                $join->on('k105_sequencial', 'e47_corgrupocorrente')
                    ->on('k105_id', 'k12_id')
                    ->on('k105_autent', 'k12_autent')
                    ->on('k105_data', 'k12_data');
            })
            ->where('e23_ativo', true)
            ->where('e60_instit', $this->instit)
            ->where('e60_anousu', $this->iAnoUsu)
            ->where('k12_estorn', false)
            ->whereBetween('k12_data', [$this->dtDataInicial, $this->dtDataFinal])
            //->whereNotNull('db89_db_bancos')
            ->orderBy('e60_numemp');

            //$sql_with_bindings = str_replace_array('?', $query->getBindings(), $query->toSql());
            //$sql_with_bindings = str_replace("\"", "", $sql_with_bindings);
            //var_dump($sql_with_bindings); die("confere");

        return $query->get();
    }

    private function convertTipoRetencao($tipo)
    {
        switch ($tipo) {
            // Imposto de Renda Retido na Fonte (IRRF)
            case '1':
            case '2':
                return 6;
                // Contribuições Previdenciárias
            case '3':
            case '4':
            case '7':
                return 3;
                // Imposto Sobre Serviços
            case '5':
                return 7;
                // Outras Retenções
            default:
                return 8;
        }
    }
}