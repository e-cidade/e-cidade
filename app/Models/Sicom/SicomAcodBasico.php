<?php

namespace App\Models\Sicom;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class SicomAcodBasico extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'sicom.sicomacodbasico';

    protected $primaryKey = 'l228_sequencial';

    public $incrementing = false;

    protected string $sequenceName = 'sicomacodbasico_l228_sequencial_seq';

    protected $fillable = [
      'l228_sequencial',
      'l228_codremessa',
      'l228_seqremessa',
      'l228_usuario',
      'l228_dataenvio',
      'l228_datainicial',
      'l228_datafim',
      'l228_instit'
    ];

}
