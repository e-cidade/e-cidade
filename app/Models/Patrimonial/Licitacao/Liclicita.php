<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Liclicita extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.liclicita';

    protected $primaryKey = 'l20_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'licitacao.liclicita_l20_codigo_seq';

    protected $fillable = [
        'l20_codigo',
        'l20_codtipocom',
        'l20_numero',
        'l20_id_usucria',
        'l20_datacria',
        'l20_horacria',
        'l20_dataaber',
        'l20_dtpublic',
        'l20_horaaber',
        'l20_local',
        'l20_objeto',
        'l20_tipojulg',
        'l20_liccomissao',
        'l20_liclocal',
        'l20_procadmin',
        'l20_correto',
        'l20_instit',
        'l20_licsituacao',
        'l20_edital',
        'l20_anousu',
        'l20_usaregistropreco',
        'l20_localentrega',
        'l20_prazoentrega',
        'l20_condicoespag',
        'l20_validadeproposta',
        'l20_razao',
        'l20_justificativa',
        'l20_aceitabilidade',
        'l20_equipepregao',
        'l20_nomeveiculo2',
        'l20_datapublicacao2',
        'l20_nomeveiculo1',
        'l20_datapublicacao1',
        'l20_datadiario',
        'l20_recdocumentacao',
        'l20_dtpubratificacao',
        'l20_registroata',
        'l20_numeroconvidado',
        'l20_descontotab',
        'l20_regimexecucao',
        'l20_naturezaobjeto',
        'l20_tipliticacao',
        'l20_critdesempate',
        'l20_execucaoentrega',
        'l20_diames',
        'l20_codepartamento',
        'l20_descricaodep',
        'l20_clausulapro',
        'l20_veicdivulgacao',
        'l20_tipoprocesso',
        'l20_tipnaturezaproced',
        'l20_destexclusiva',
        'l20_subcontratacao',
        'l20_limitcontratacao',
        'l20_regata',
        'l20_interporrecurso',
        'l20_descrinterporrecurso',
        'l20_formacontroleregistropreco',
        'l20_criterioadjudicacao',
        'l20_dtlimitecredenciamento',
        'l20_nroedital',
        'l20_cadinicial',
        'l20_exercicioedital',
        'l20_leidalicitacao',
        'l20_dtpulicacaopncp',
        'l20_linkpncp',
        'l20_diariooficialdivulgacao',
        'l20_dtpulicacaoedital',
        'l20_linkedital',
        'l20_mododisputa',
        'l20_dataaberproposta',
        'l20_dataencproposta',
        'l20_amparolegal',
        'l20_orcsigiloso',
        'l20_justificativapncp',
        'l20_categoriaprocesso',
        'l20_receita',
        'l20_horaaberturaprop',
        'l20_horaencerramentoprop',
        'l20_dispensaporvalor',
        'l20_lances'
    ];

}
