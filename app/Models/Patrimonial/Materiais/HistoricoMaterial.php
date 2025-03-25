<?php

namespace App\Models\Patrimonial\Materiais;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class HistoricoMaterial extends LegacyModel{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.historicomaterial';

    protected $primaryKey = 'db150_sequencial';

    public $incrementing = false;

    protected $fillable = [
      'db150_sequencial',
      'db150_tiporegistro',
      'db150_coditem',
      'db150_pcmater',
      'db150_dscitem',
      'db150_unidademedida',
      'db150_tipocadastro',
      'db150_justificativaalteracao',
      'db150_mes',
      'db150_data',
      'db150_instit',
      'db150_codunid',
      'db150_status',
    ];
}
