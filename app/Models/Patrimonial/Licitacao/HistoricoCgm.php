<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class HistoricoCgm extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.historicocgm';

    protected $primaryKey = 'z09_sequencial';

    public $incrementing = false;

    protected string $sequenceName = 'historicocgm_z09_sequencial_seq';

    protected $fillable = [
      'z09_sequencial',
      'z09_numcgm',
      'z09_usuario',
      'z09_datacadastro',
      'z09_dataservidor',
      'z09_horaalt',
      'z09_motivo',
      'z09_tipo',
      'z09_status',
    ];

}
