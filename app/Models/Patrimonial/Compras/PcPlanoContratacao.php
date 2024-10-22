<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\Configuracoes\DbDepart;
use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PcPlanoContratacao extends LegacyModel {

    public $timestamps = false;

    protected $table = 'compras.pcplanocontratacao';

    protected $primaryKey = 'mpc01_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'mpc01_codigo_seq';

    protected $fillable = [
        'mpc01_codigo',
        'mpc01_ano',
        'mpc01_uncompradora',
        'mpc01_data',
        'mpc01_datacria',
        'mpc01_usuario',
        'mpc01_is_send_pncp',
        'mpc01_sequencial'
    ];

    public function unidadeCompradora() {
        return $this->belongsTo(DbDepart::class, 'coddepto', 'mpc01_uncompradora');
    }
}
