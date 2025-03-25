<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Pcmater extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pcmater';

    protected $primaryKey = 'pc01_codmater';

    public $incrementing = false;

    protected $fillable = [
        'pc01_codmater',
        'pc01_descrmater',
        'pc01_complmater',
        'pc01_codsubgrupo',
        'pc01_ativo',
        'pc01_conversao',
        'pc01_id_usuario',
        'pc01_libaut',
        'pc01_servico',
        'pc01_veiculo',
        'pc01_validademinima',
        'pc01_obrigatorio',
        'pc01_fraciona',
        'pc01_liberaresumo',
        'pc01_data',
        'pc01_tabela',
        'pc01_taxa',
        'pc01_obras',
        'pc01_dataalteracao',
        'pc01_justificativa',
        'pc01_instit',
        'pc01_codmaterant',
        'pc01_regimobiliario'
    ];

}
