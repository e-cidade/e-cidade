<?php

namespace App\Models\Empenho;

use App\Models\LegacyModel;

class EmpAutoriza extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'empenho.empautoriza';

    /**
     * @var string
     */
    protected $primaryKey = 'e54_autori';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public $timestamps = false;

    protected string $sequenceName = 'empautoriza_e54_autori_seq';

    /**
     * @var array
     */
    protected $fillable = [
        'e54_numcgm', 'e54_login', 'e54_codcom', 'e54_destin', 'e54_valor', 'e54_anousu',
        'e54_tipol', 'e54_numerl', 'e54_praent', 'e54_entpar', 'e54_conpag', 'e54_codout',
        'e54_contat', 'e54_telef', 'e54_numsol', 'e54_anulad', 'e54_emiss', 'e54_resumo',
        'e54_codtipo', 'e54_instit', 'e54_depto', 'e54_concarpeculiar', 'e54_tipodespesa',
        'e54_gestaut', 'e54_codlicitacao', 'e54_nummodalidade', 'e54_licoutrosorgaos',
        'e54_adesaoregpreco', 'e54_tipoorigem', 'e54_tipoautorizacao', 'e54_desconto',
        'e54_numerotermo', 'e54_datainclusao',
    ];
}
