<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class ParecerLicitacao extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.parecerlicitacao';

    protected $primaryKey = 'l200_sequencial';

    public $incrementing = false;

    protected string $sequenceName = 'parecerlicitacao_l200_sequencial_seq';

    protected $fillable = [
      'l200_sequencial',
      'l200_licitacao',
      'l200_exercicio',
      'l200_data',
      'l200_tipoparecer',
      'l200_numcgm',
      'l200_enviosicom',
      'l200_descrparecer'
    ];

}
