<?php

namespace App\Models\Sicom;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class AdesaoRegPrecosDocumentos extends LegacyModel
{
  use LegacyAccount;

  public $timestamps = false;

  protected $table = 'sicom.adesaoregprecosdocumentos';

  protected $primaryKey = 'sd1_sequencial';

  public $incrementing = false;

  protected string $sequenceName = 'adesaoregprecosdocumentos_sd1_sequencial_seq';

  protected $fillable = [
    'sd1_sequencial',
    'sd1_nomearquivo',
    'sd1_tipo',
    'sd1_liclicita',
    'sd1_arquivo',
    'sd1_extensao',
    'sd1_sequencialadesao',
  ];

}
