<?php

namespace App\Models\Configuracao;

use App\Traits\LegacyAccount;
use App\Traits\LegacyLabel;
use App\Models\LegacyModel;
use Illuminate\Support\Facades\DB;

class AssinaturaDigitalParametro extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'configuracoes.assinatura_digital_parametro';

    /**
     * @var string
     */
    protected $primaryKey = 'db242_codigo';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public $timestamps = false;


    protected string $sequenceName = 'assinatura_digital_parametro_db242_codigo_seq';

    /**
     * @var array
     */
    protected $fillable = [
        'db242_codigo',
        'db242_assinador_url',
        'db242_assinador_token',
        'db242_instit',
        'db242_assinador_ativo'
    ];

}
