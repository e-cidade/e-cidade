<?php

namespace App\Models\Sicom;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class HistoricoCgmEnvioSicom extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'sicom.historicocgmenviosicom';

    protected $primaryKey = 'z18_sequencial';

    public $incrementing = false;

    protected string $sequenceName = 'historicocgmenviosicom_z18_sequencial_seq';

    protected $fillable = [
      'z18_sequencial',
      'z18_instit',
      'z18_statusenvio',
      'z18_cgm',
      'z18_seqremessa'
    ];

}
