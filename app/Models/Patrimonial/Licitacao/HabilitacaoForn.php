<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class HabilitacaoForn extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.habilitacaoforn';

    protected $primaryKey = 'l206_sequencial';

    public $incrementing = false;

    // protected string $sequenceName = 'parecerlicitacao_l200_sequencial_seq';

    protected $fillable = [
      'l206_sequencial',
      'l206_fornecedor',
      'l206_licitacao',
      'l206_representante',
      'l206_datahab',
      'l206_numcertidaoinss',
      'l206_dataemissaoinss',
      'l206_datavalidadeinss',
      'l206_numcertidaofgts',
      'l206_dataemissaofgts',
      'l206_datavalidadefgts',
      'l206_numcertidaocndt',
      'l206_dataemissaocndt',
      'l206_datavalidadecndt'
    ];

}
