<?php

namespace App\Models\Sicom;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class ItensRegPreco extends LegacyModel
{
  use LegacyAccount;

  public $timestamps = false;

  protected $table = 'sicom.itensregpreco';

  protected $primaryKey = 'si07_sequencial';

  public $incrementing = false;

  protected string $sequenceName = 'sic_itensregpreco_si07_sequencial_seq';

  protected $fillable = [
    'si07_sequencial',
    'si07_numerolote',
    'si07_numeroitem',
    'si07_descricaoitem',
    'si07_item',
    'si07_precounitario',
    'si07_quantidadelicitada',
    'si07_quantidadeaderida',
    'si07_unidade',
    'si07_sequencialadesao',
    'si07_descricaolote',
    'si07_fornecedor',
    'si07_codunidade',
    'si07_percentual',
  ];

}
