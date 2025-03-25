<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Oc23776 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $o42_codparrel = DB::table('orcparamrel')->insertGetId([
            'o42_codparrel' => 1522024,
            'o42_descrrel' => 'BALANCO FINANCEIRO DCASP 2024',
            'o42_orcparamrelgrupo' => 4
        ], 'o42_codparrel');

        DB::table('orcparamrelperiodos')->insert([
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 17,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 18,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 19,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 20,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 21,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 22,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 23,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 24,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 25,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 26,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 27,
                'o113_orcparamrel' => $o42_codparrel
            ],
            [
                'o113_sequencial' => (DB::raw("nextval('orcparamrelperiodos_o113_sequencial_seq')")),
                'o113_periodo' => 28,
                'o113_orcparamrel' => $o42_codparrel
            ]
        ]);

        DB::table('orcparamseq')->insert([
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 1, 'o69_descr' => 'Receita Orcamentaria (I)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1, 'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Receita Orcamentaria (I)', 'o69_manual' => false, 'o69_totalizador' => true, 'o69_ordem' => 1, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 2, 'o69_descr' => 'Recursos Nao Vinculados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos nao Vinculados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 2, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 3, 'o69_descr' => 'Recursos Vinculados (EXCETO ao RPPS)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados (EXCETO RPPS)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 3, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 4, 'o69_descr' => 'Recursos Vinculados a Educacao', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados a Educacao', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 4, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 5, 'o69_descr' => 'Recursos Vinculados a Saude', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados a Saude', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 5, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 6, 'o69_descr' => 'Recursos Vinculados a Assistencia Social', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados a Assistencia Social', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 6, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 7, 'o69_descr' => 'Demais Vinculacoes Decorrentes de Transferencias', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Demais Vinculacoes Decorrentes de Transferencias', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 7, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 8, 'o69_descr' => 'Demais Vinculacoes Legais', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Demais Vinculacoes Legais', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 8, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 9, 'o69_descr' => 'Outras Vinculacoes', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Outras Vinculacoes', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 9, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 10, 'o69_descr' => 'Recursos Vinculados ao RPPS', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados ao RPPS', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 10, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 11, 'o69_descr' => 'Recursos Vinculados ao RPPS  - Fundo em Capitalizacao (PLANO PREVIDENCIARIO)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados ao RPPS  - Fundo em Capitalizacao (PLANO PREVIDENCIARIO)', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 11, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 12, 'o69_descr' => 'Recursos Vinculados ao RPPS  - Fundo em Reparticao (PLANO FINANCEIRO)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados ao RPPS  - Fundo em Reparticao (PLANO FINANCEIRO)', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 12, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 13, 'o69_descr' => 'Recursos Vinculados ao RPPS  - Taxa de Administracao', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados ao RPPS  - Taxa de Administracao', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 13, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 1 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 14, 'o69_descr' => 'Transferencias Financeiras Recebidas (II)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Financeiras Recebidas (II)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 14, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 15, 'o69_descr' => 'Transferencias Recebidas para Execucao Orcamentaria', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Recebidas para Execucao Orcamentaria', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 15, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 16, 'o69_descr' => 'Transferencias Financeiras Recebidas Independente de Execucao Orcamentaria', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Financeiras Recebidas Independente de Execucao Orcamentaria', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 16, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 17, 'o69_descr' => 'Transferencias Recebidas para Aportes de Recursos para o RPPS', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Recebidas para Aportes de Recursos para o RPPS', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 17, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 18, 'o69_descr' => 'Transferencias Recebidas para Aportes de Recursos para o RGPS', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Recebidas para Aportes de Recursos para o RGPS', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 18, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 19, 'o69_descr' => 'Transferencias Recebidas para o Sistema de Protecao Social dos Militares', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Recebidas para o Sistema de Protecao Social dos Militares', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 19, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 20, 'o69_descr' => 'Outras Movimentacoes Financeiras Recebidas (III)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Outras Movimentacoes Financeiras Recebidas (III)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 20, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 21, 'o69_descr' => 'Resgate de Investimentos e Aplicacoes Financeiras', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Resgate de Investimentos e Aplicacoes Financeiras', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 21, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 22, 'o69_descr' => 'Desbloqueios de Valores em Caixa', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Desbloqueios de Valores em Caixa', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 22, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 23, 'o69_descr' => 'Recebimentos Extraorcamentarios (IV)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recebimentos Extraorcamentarios (IV)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 23, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 24, 'o69_descr' => 'Inscricao de Restos a Pagar Nao Processados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Inscricao de Restos a Pagar Nao Processados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 24, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 25, 'o69_descr' => 'Inscricao de Restos a Pagar Processados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Inscricao de Restos a Pagar Processados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 25, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 26, 'o69_descr' => 'Depositos Restituiveis e Valores Vinculados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Depositos Restituiveis e Valores Vinculados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 26, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 27, 'o69_descr' => 'Outros Recebimentos Extraorcamentarios', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Outros Recebimentos Extraorcamentarios', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 27, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 28, 'o69_descr' => 'Saldo do Exercicio Anterior(V)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Saldo do Exercicio Anterior(V)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 28, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 29, 'o69_descr' => 'Caixa e Equivalentes de Caixa (exceto RPPS)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Caixa e Equivalentes de Caixa (EXCETO RPPS)', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 29, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 30, 'o69_descr' => 'Caixa e Equivalentes de Caixa RPPS', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Caixa e Equivalentes de Caixa RPPS', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 30, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 31, 'o69_descr' => 'Depositos Restituiveis e Valores Vinculados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Depositos Restituiveis e Valores Vinculados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 31, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 32, 'o69_descr' => 'TOTAL(VI) = (I+II+III+IV+V)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'TOTAL(VI) = (I+II+III+IV+V)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 32, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 33, 'o69_descr' => 'Despesa Orcamentaria (VII)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Despesa Orcamentaria (VII)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 33, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 34, 'o69_descr' => 'Recursos Nao Vinculados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Nao Vinculados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 34, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 35, 'o69_descr' => 'Recursos Vinculados (EXCETO RPPS) ', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados (EXCETO RPPS) ', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 35, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 36, 'o69_descr' => 'Recursos Vinculados a Educacao', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados a Educacao', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 36, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 37, 'o69_descr' => 'Recursos Vinculados a Saude', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados a Saude', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 37, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 38, 'o69_descr' => 'Recursos Vinculados a Assistencia Social', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados a Assistencia Social', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 38, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 39, 'o69_descr' => 'Demais Vinculacoes Decorrentes de Transferencias', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Demais Vinculacoes Decorrentes de Transferencias', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 39, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 40, 'o69_descr' => 'Demais Vinculacoes Legais', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Demais Vinculacoes Legais', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 40, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 41, 'o69_descr' => 'Outras Vinculacoes', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Outras Vinculacoes', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 41, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 42, 'o69_descr' => 'Recursos Vinculados ao RPPS', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados ao RPPS', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 42, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 43, 'o69_descr' => 'Recursos Vinculados ao RPPS  - Fundo em Capitalizacao (PLANO PREVIDENCIARIO)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados ao RPPS  - Fundo em Capitalizacao (PLANO PREVIDENCIARIO)', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 43, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 44, 'o69_descr' => 'Recursos Vinculados ao RPPS  - Fundo em Reparticao (PLANO FINANCEIRO)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados ao RPPS  - Fundo em Reparticao (PLANO FINANCEIRO)', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 44, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 45, 'o69_descr' => 'Recursos Vinculados ao RPPS  - Taxa de Administracao', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recursos Vinculados ao RPPS  - Taxa de Administracao', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 45, 'o69_nivellinha' => 3, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 2 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 46, 'o69_descr' => 'Transferencias Financeiras Concedidas (VIII)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Financeiras Concedidas (VIII)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 46, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 47, 'o69_descr' => 'Transferencias Concedidas para Execucao Orcamentaria', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Concedidas para Execucao Orcamentaria', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 47, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 48, 'o69_descr' => 'Transferencias Financeiras Concedidas Independente de Execucao Orcamentaria', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Financeiras Concedidas Independente de Execucao Orcamentaria', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 48, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 49, 'o69_descr' => 'Transferencias Concedidas para Aportes de Recursos para o RPPS', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Concedidas para Aportes de Recursos para o RPPS', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 49, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 50, 'o69_descr' => 'Transferencias Concedidas para Aportes de Recursos para o RGPS', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Concedidas para Aportes de Recursos para o RGPS', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 50, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 51, 'o69_descr' => 'Transferencias Concedidas para o Sistema de Protecao Social dos Militares', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias Concedidas para o Sistema de Protecao Social dos Militares', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 51, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 52, 'o69_descr' => 'Outras Movimentacoes Financeiras Concedidas (IX)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Outras Movimentacoes Financeiras Concedidas (IX)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 52, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 53, 'o69_descr' => 'Transferencias para Investimentos e Aplicacoes Financeiras', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Transferencias para Investimentos e Aplicacoes Financeiras', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 53, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 54, 'o69_descr' => 'Bloqueios de Valores em Caixa', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Bloqueios de Valores em Caixa', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 54, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 55, 'o69_descr' => 'Recebimentos Extraorcamentarios (X)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Recebimentos Extraorcamentarios (X)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 55, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 56, 'o69_descr' => 'Pagamentos de Restos a Pagar Nao Processados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Pagamentos de Restos a Pagar Nao Processados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 56, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 4 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 57, 'o69_descr' => 'Pagamentos de Restos a Pagar Processados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Pagamentos de Restos a Pagar Processados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 57, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 4 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 58, 'o69_descr' => 'Depositos Restituiveis e Valores Vinculados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Depositos Restituiveis e Valores Vinculados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 58, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 59, 'o69_descr' => 'Outros Pagamentos Extraorcamentarios', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Outros Pagamentos Extraorcamentarios', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 59, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 60, 'o69_descr' => 'Saldo Para o Exercicio Seguinte (XI)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Saldo Para o Exercicio Seguinte (XI)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 60, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 61, 'o69_descr' => 'Caixa e Equivalentes de Caixa (exceto RPPS)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Caixa e Equivalentes de Caixa (exceto RPPS)', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 61, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 62, 'o69_descr' => 'Caixa e Equivalentes de Caixa RPPS', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Caixa e Equivalentes de Caixa RPPS', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 62, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 63, 'o69_descr' => 'Depositos Restituiveis e Valores Vinculados', 'o69_grupo' => 1, 'o69_grupoexclusao' => 0,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'Depositos Restituiveis e Valores Vinculados', 'o69_manual' => true, 'o69_totalizador' => false,'o69_ordem' => 63, 'o69_nivellinha' => 2, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 3 
            ],
            [
                'o69_codparamrel' => $o42_codparrel, 'o69_codseq' => 64, 'o69_descr' => 'TOTAL(X) = (VII+VIII+IX+X+XI)', 'o69_grupo' => 1, 'o69_grupoexclusao' => 1,'o69_nivel' => 1, 'o69_libnivel' => false, 'o69_librec' => false, 'o69_libsubfunc' => false, 'o69_libfunc' => false, 'o69_verificaano' => false, 'o69_labelrel' => 'TOTAL(X) = (VII+VIII+IX+X+XI)', 'o69_manual' => false, 'o69_totalizador' => true,'o69_ordem' => 64, 'o69_nivellinha' => 1, 'o69_observacao' => '', 'o69_desdobrarlinha' => false, 'o69_origem' => 0 
            ]
        ]);

        $aLinhas = [
            1  => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[2]->{exercicio}+F[3]+F[10]',
                'totalizador' => true],
            2  => [
                'contas' => ['400000000000000'],
                'recursos' => '15000000,15000001,15000002,15010000,15020000,15020001,15020002,15030000,25000000,25000001,25000002,25010000,25020000,25030000,25020001,25020002',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            3  => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[4]->{exercicio}+L[5]->{exercicio}+L[6]->{exercicio}+L[7]->{exercicio}+L[8]->{exercicio}+L[9]->{exercicio}',
                'totalizador' => true],
            4  => [
                'contas' => ['400000000000000'],
                'recursos' => '15400000,15400007,15410000,15420000,15420007,15430000,15440000,15500000,15510000,15520000,15530000,15690000,15700000,15710000,15720000,15730000,15740000,15750000,15760000,15760010,15990000,15990030,15990370,25400000,25400007,25410000,25420000,25420007,25430000,25440000,25500000,25510000,25520000,25530000,25690000,25700000,25710000,25720000,25730000,25740000,25750000,25760000,25760010,25990000,25990030,25990370,15450000,25450000',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            5  => [
                'contas' => ['400000000000000'],
                'recursos' => '16000000,16010000,16020000,16030000,16040000,16050000,16210000,16220000,16310000,16320000,16330000,16340000,16350000,16360000,16590000,16590020,16590190,16590260,16590270,16590280,16590310,16590320,16590350,16590380,16590480,16590500,26000000,26010000,26020000,26030000,26040000,26050000,26210000,26220000,26310000,26320000,26330000,26340000,26350000,26360000,26590000,26590020,26590190,26590260,26590270,26590280,26590310,26590320,26590350,26590380,26590480,26590500,',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            6  => [
                'contas' => ['400000000000000'],
                'recursos' => '16600000,16610000,16620000,16650000,16690000,16690170,16690180,16690190,16690290,26600000,26610000,26620000,26650000,26690000,26690170,26690180,26690190,26690290,',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            7  => [
                'contas' => ['400000000000000'],
                'recursos' => '17000000,17000070,17000140,17010000,17010150,17020000,17030000,17050000,17060000,17070000,17080000,17090000,17100000,17100100,17110000,17110140,17110150,17120000,17140000,17150000,17160000,17170000,17180000,17190000,17200000,17210000,17470140,17490000,17490120,17490140,17490150,27000000,27000070,27000140,27010000,27010150,27020000,27030000,27050000,27060000,27070000,27080000,27090000,27100000,27100100,27110000,27110140,27110150,27120000,27140000,27150000,27160000,27170000,27180000,27190000,27200000,27210000,27470140,27490000,27490120,27490140,27490150,17040000,27040000,17480000,27480000',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            8  => [
                'contas' => ['400000000000000'],
                'recursos' => '17500000,17510000,17520000,17530000,17540000,17550000,17560000,17580000,17590000,17590050,17590150,17610000,17990000,17990060,27500000,27510000,27520000,27530000,27540000,27550000,27560000,27580000,27590000,27590050,27590150,27610000,27990000,27990060,17570000,27570000',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            9  => [
                'contas' => ['400000000000000'],
                'recursos' => '18620000,18690000,18980000,18990000,18990040,18990060,18990200,18990210,18990220,18990230,18990240,18990250,18990300,18990330,18990340,18990360,18990390,18990400,18990410,18990420,18990430,18990440,18990450,18990460,18990470,18990490,28620000,28690000,28980000,28990000,28990040,28990060,28990200,28990210,28990220,28990230,28990240,28990250,28990300,28990330,28990340,28990360,28990390,28990400,28990410,28990420,28990430,28990440,28990450,28990460,28990470,28990490,18040000,28040000,18800000,28800000',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            10 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[11]->{exercicio}+L[12]->{exercicio}+L[13]->{exercicio}',
                'totalizador' => true],
            11 => [
                'contas' => ['400000000000000'],
                'recursos' => '18000000,18000001,18000002,28000000,28000001,28000002',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            12 => [
                'contas' => ['400000000000000'],
                'recursos' => '18010000,28010000',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            13 => [
                'contas' => ['400000000000000'],
                'recursos' => '18020000,28020000',
                'formula' => '#saldo_arrecadado',
                'totalizador' => false],
            14 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[15]->{exercicio}+L[16]->{exercicio}+L[17]->{exercicio}+L[18]->{exercicio}+L[19]->{exercicio}',
                'totalizador' => true],
            15 => [
                'contas' => ['451100000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            16 => [
                'contas' => ['451220100000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            17 => [
                'contas' => ['451300000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            18 => [
                'contas' => ['451400000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            19 => [
                'contas' => ['451500000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            20 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[21]->{exercicio}+L[22]->{exercicio}',
                'totalizador' => true],
            21 => [
                'contas' => [],
                'recursos' => '',
                'formula' => '',
                'totalizador' => false],
            22 => [
                'contas' => [],
                'recursos' => '',
                'formula' => '',
                'totalizador' => false],
            23 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[24]->{exercicio}+L[25]->{exercicio}+L[26]->{exercicio}+L[27]->{exercicio}',
                'totalizador' => true],
            24 => [
                'contas' => ['300000000000000'],
                'recursos' => '',
                'formula' => '#empenhado - #anulado - #liquidado',
                'totalizador' => false],
            25 => [
                'contas' => ['300000000000000'],
                'recursos' => '',
                'formula' => '#atual_a_pagar_liquidado',
                'totalizador' => false],
            26 => [
                'contas' => ['218800000000000', '113810000000000', '113519900000000', '362110300000000', '228800000000000'],
                'recursos' => '',
                'formula' => '#saldo_anterior_credito',
                'totalizador' => false],
            27 => [
                'contas' => ['218900000000000'],
                'recursos' => '',
                'formula' => '#saldo_anterior_credito',
                'totalizador' => false],
            28 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[29]->{exercicio}+L[30]->{exercicio}+L[31]->{exercicio}',
                'totalizador' => true],
            29 => [
                'contas' => ['111000000000000', '(e)111110600000000', '(e)111300000000000', '114410000000000'],
                'recursos' => '',
                'formula' => '#saldo_anterior',
                'totalizador' => false],
            30 => [
                'contas' => ['111110600000000'],
                'recursos' => '',
                'formula' => '#saldo_anterior',
                'totalizador' => false],
            31 => [
                'contas' => ['218910000000000'],
                'recursos' => '',
                'formula' => '#saldo_anterior',
                'totalizador' => false],
            32 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'F[1]+F[14]+F[20]+F[23]+F[28]',
                'totalizador' => true],
            33 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[34]->{exercicio}+F[35]+F[42]',
                'totalizador' => true],
            34 => [
                'contas' => ['300000000000000'],
                'recursos' => '15000000,15000001,15000002,15010000,15020000,15020001,15020002,15030000,25000000,25000001,25000002,25010000,25020000,25030000,25020001,25020002',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            35 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[36]->{exercicio}+L[37]->{exercicio}+L[38]->{exercicio}+L[39]->{exercicio}+L[40]->{exercicio}+L[41]->{exercicio}',
                'totalizador' => true],
            36 => [
                'contas' => ['300000000000000'],
                'recursos' => '15400000,15400007,15410000,15420000,15420007,15430000,15440000,15500000,15510000,15520000,15530000,15690000,15700000,15710000,15720000,15730000,15740000,15750000,15760000,15760010,15990000,15990030,15990370,25400000,25400007,25410000,25420000,25420007,25430000,25440000,25500000,25510000,25520000,25530000,25690000,25700000,25710000,25720000,25730000,25740000,25750000,25760000,25760010,25990000,25990030,25990370,15450000,25450000',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            37 => [
                'contas' => ['300000000000000'],
                'recursos' => '16000000,16010000,16020000,16030000,16040000,16050000,16210000,16220000,16310000,16320000,16330000,16340000,16350000,16360000,16590000,16590020,16590190,16590260,16590270,16590280,16590310,16590320,16590350,16590380,16590480,16590500,26000000,26010000,26020000,26030000,26040000,26050000,26210000,26220000,26310000,26320000,26330000,26340000,26350000,26360000,26590000,26590020,26590190,26590260,26590270,26590280,26590310,26590320,26590350,26590380,26590480,26590500,',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            38 => [
                'contas' => ['300000000000000'],
                'recursos' => '16600000,16610000,16620000,16650000,16690000,16690170,16690180,16690190,16690290,26600000,26610000,26620000,26650000,26690000,26690170,26690180,26690190,26690290,',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            39 => [
                'contas' => ['300000000000000'],
                'recursos' => '17000000,17000070,17000140,17010000,17010150,17020000,17030000,17050000,17060000,17070000,17080000,17090000,17100000,17100100,17110000,17110140,17110150,17120000,17140000,17150000,17160000,17170000,17180000,17190000,17200000,17210000,17470140,17490000,17490120,17490140,17490150,27000000,27000070,27000140,27010000,27010150,27020000,27030000,27050000,27060000,27070000,27080000,27090000,27100000,27100100,27110000,27110140,27110150,27120000,27140000,27150000,27160000,27170000,27180000,27190000,27200000,27210000,27470140,27490000,27490120,27490140,27490150,17040000,27040000,17480000,27480000',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            40 => [
                'contas' => ['300000000000000'],
                'recursos' => '17500000,17510000,17520000,17530000,17540000,17550000,17560000,17580000,17590000,17590050,17590150,17610000,17990000,17990060,27500000,27510000,27520000,27530000,27540000,27550000,27560000,27580000,27590000,27590050,27590150,27610000,27990000,27990060,17570000,27570000',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            41 => [
                'contas' => ['300000000000000'],
                'recursos' => '18620000,18690000,18980000,18990000,18990040,18990060,18990200,18990210,18990220,18990230,18990240,18990250,18990300,18990330,18990340,18990360,18990390,18990400,18990410,18990420,18990430,18990440,18990450,18990460,18990470,18990490,28620000,28690000,28980000,28990000,28990040,28990060,28990200,28990210,28990220,28990230,28990240,28990250,28990300,28990330,28990340,28990360,28990390,28990400,28990410,28990420,28990430,28990440,28990450,28990460,28990470,28990490,18040000,28040000,18800000,28800000',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            42 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[43]->{exercicio}+L[44]->{exercicio}+L[45]->{exercicio}',
                'totalizador' => true],
            43 => [
                'contas' => ['300000000000000'],
                'recursos' => '18000000,18000001,18000002,28000000,28000001,28000002',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            44 => [
                'contas' => ['300000000000000'],
                'recursos' => '18010000,28010000',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            45 => [
                'contas' => ['300000000000000'],
                'recursos' => '18020000,28020000',
                'formula' => '#empenhado - #anulado',
                'totalizador' => false],
            46 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[47]->{exercicio}+L[48]->{exercicio}+L[49]->{exercicio}+L[50]->{exercicio}+L[51]->{exercicio}',
                'totalizador' => true],
            47 => [
                'contas' => ['351100000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            48 => [
                'contas' => ['351220100000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            49 => [
                'contas' => ['351300000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            50 => [
                'contas' => ['351400000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            51 => [
                'contas' => ['351500000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            52 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[53]->{exercicio}+L[54]->{exercicio}',
                'totalizador' => true],
            53 => [
                'contas' => [],
                'recursos' => '',
                'formula' => '',
                'totalizador' => false],
            54 => [
                'contas' => [],
                'recursos' => '',
                'formula' => '',
                'totalizador' => false],
            55 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[56]->{exercicio}+L[57]->{exercicio}+L[58]->{exercicio}+L[59]->{exercicio}',
                'totalizador' => true],
            56 => [
                'contas' => ['300000000000000'],
                'recursos' => '',
                'formula' => '#vlrpagnproc',
                'totalizador' => false],
            57 => [
                'contas' => ['300000000000000'],
                'recursos' => '',
                'formula' => '#vlrpag',
                'totalizador' => false],
            58 => [
                'contas' => ['218800000000000', '113810000000000', '113519900000000', '362110300000000', '228800000000000'],
                'recursos' => '',
                'formula' => '#saldo_anterior_debito',
                'totalizador' => false],
            59 => [
                'contas' => ['218900000000000'],
                'recursos' => '',
                'formula' => '#saldo_anterior_debito',
                'totalizador' => false],
            60 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'L[61]->{exercicio}+L[62]->{exercicio}+L[63]->{exercicio}',
                'totalizador' => true],
            61 => [
                'contas' => ['111000000000000', '(e)111110600000000', '(e)111300000000000', '114410000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            62 => [
                'contas' => ['111110600000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            63 => [
                'contas' => ['111300000000000'],
                'recursos' => '',
                'formula' => '#saldo_final',
                'totalizador' => false],
            64 => [
                'contas' => [],
                'recursos' => '',
                'formula' => 'F[33]+F[46]+F[52]+F[55]+F[60]',
                'totalizador' => true],
        ];

        $aPeriodo = [17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28];

        $codExercicioAtual = DB::table('orcparamseqcoluna')->where('o115_nomecoluna', 'vlrexatual')->value('o115_sequencial');
        $codExercicioAnter = DB::table('orcparamseqcoluna')->where('o115_nomecoluna', 'vlrexanter')->value('o115_sequencial');

        foreach ($aLinhas as $seqLinha => $linha) {
            if (!$linha['totalizador']) {
                $sContas = '';
                foreach($linha['contas'] as $conta){
                    $sContas = empty($sContas) ? "<contas>" : $sContas;
                    if (str_starts_with($conta, '(e)')) {
                        $conta = substr($conta, 3);
                        $sExclusao = 'true';
                    } else {
                        $sExclusao = 'false';
                    }
                    $sNivel = preg_match('/^(400|300)/', $conta) ? '1' : '';

                    $sContas .= '<conta estrutural="'.$conta.'" nivel="'.$sNivel.'" exclusao="'.$sExclusao.'" indicador=""/>';
                }

                $sContas = empty($sContas) ? "<contas/>" : $sContas."</contas>";
                $sRecursos = $linha['recursos'];

                $o132_filtro = '<?xml version="1.0" encoding="ISO-8859-1"?>
                                <filter>'.$sContas.'
                                    <orgao operador="in" valor="" id="orgao"/>
                                    <unidade operador="in" valor="" id="unidade"/>
                                    <funcao operador="in" valor="" id="funcao"/>
                                    <subfuncao operador="in" valor="" id="subfuncao"/>
                                    <programa operador="in" valor="" id="programa"/>
                                    <projativ operador="in" valor="" id="projativ"/>
                                    <recurso operador="in" valor="'.$sRecursos.'" id="recurso"/>
                                    <recursocontalinha numerolinha="" id="recursocontalinha"/>
                                    <observacao valor=""/>
                                    <desdobrarlinha valor="false"/>
                                </filter>';
                DB::table('orcparamseqfiltropadrao')->insert([
                    [
                        'o132_sequencial'  => DB::table('orcparamseqfiltropadrao')->max('o132_sequencial') + 1,
                        'o132_orcparamrel' => $o42_codparrel,
                        'o132_orcparamseq' => $seqLinha,
                        'o132_anousu'      => 2024,
                        'o132_filtro'      => $o132_filtro
                    ],
                    [
                        'o132_sequencial'  => DB::table('orcparamseqfiltropadrao')->max('o132_sequencial') + 2,
                        'o132_orcparamrel' => $o42_codparrel,
                        'o132_orcparamseq' => $seqLinha,
                        'o132_anousu'      => 2025,
                        'o132_filtro'      => $o132_filtro
                    ]
                ]);
            }
            foreach ($aPeriodo as $iPeriodo) {
            DB::table('orcparamseqorcparamseqcoluna')->insert([
                    [
                        'o116_sequencial'        => DB::raw("nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq')"),
                        'o116_codseq'            => $seqLinha,
                        'o116_codparamrel'       => $o42_codparrel,
                        'o116_orcparamseqcoluna' => $codExercicioAtual,
                        'o116_ordem'             => 1,
                        'o116_periodo'           => $iPeriodo,
                        'o116_formula'           =>  str_replace('{exercicio}', 'vlrexatual', $linha['formula'])
                    ],
                    [
                        'o116_sequencial'        => DB::raw("nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq')"),
                        'o116_codseq'            => $seqLinha,
                        'o116_codparamrel'       => $o42_codparrel,
                        'o116_orcparamseqcoluna' => $codExercicioAnter,
                        'o116_ordem'             => 2,
                        'o116_periodo'           => $iPeriodo,
                        'o116_formula'           => str_replace('{exercicio}', 'vlrexanter', $linha['formula'])
                    ]
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        $o42_codparrel =  1522024;

        DB::table('orcparamseqfiltropadrao')->where('o132_orcparamrel', $o42_codparrel)->delete();

        DB::table('orcparamseqorcparamseqcoluna')->where('o116_codparamrel', $o42_codparrel)->delete();

        DB::table('orcparamseq')->where('o69_codparamrel', $o42_codparrel)->delete();

        DB::table('orcparamrelperiodos')->where('o113_orcparamrel', $o42_codparrel)->delete();

        DB::table('orcparamrel')->where('o42_codparrel', $o42_codparrel)->delete();

    }
}
