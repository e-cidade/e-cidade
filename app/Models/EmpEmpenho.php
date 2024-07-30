<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class EmpEmpenho extends LegacyModel
{
    use LegacyAccount;

     /**
     *
     * @var string
     */
    protected string $sequenceName = 'empenho.empempenho_e60_numemp_seq';

    /**
     * @var string
     */
    protected $table = 'empempenho';

    /**
     * @var string
     */
    protected $primaryKey = 'e60_numemp';

    /**
     * @var array
     */
    protected $fillable = [
        'e60_codemp',
        'e60_anousu',
        'e60_coddot',
        'e60_numcgm',
        'e60_emiss',
        'e60_vencim',
        'e60_vlrorc',
        'e60_vlremp',
        'e60_vlrliq',
        'e60_vlrpag',
        'e60_vlranu',
        'e60_codtipo',
        'e60_resumo',
        'e60_destin',
        'e60_salant',
        'e60_instit',
        'e60_codcom',
        'e60_tipol',
        'e60_numerol',
        'migra_elemento',
        'e60_concarpeculiar',
        'e60_convenio',
        'e60_numconvenio',
        'e60_dataconvenio',
        'e60_id_usuario',
        'e60_datasentenca',
        'e60_tipodespesa',
        'e60_informacaoop',
        'e60_vlrutilizado',
        'e60_emendaparlamentar',
        'e60_esferaemendaparlamentar ',
        'e60_codco',
    ];

     /**
     * Ind',icates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     *  Indicates if the timestamp is active.
     *
     * @var boolean
     */
    public $timestamps = false;
}
