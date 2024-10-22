<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Itemprecoreferencia extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'sicom.itemprecoreferencia';

    protected $primaryKey = 'si02_sequencial';

    public $incrementing = false;

    protected string $sequenceName = 'sic_itemprecoreferencia_si02_sequencial_seq';

    protected $fillable = [
        'si02_sequencial',
        'si02_precoreferencia',
        'si02_itemproccompra',
        'si02_vlprecoreferencia',
        'si02_vlpercreferencia',
        'si02_coditem',
        'si02_qtditem',
        'si02_codunidadeitem',
        'si02_reservado',
        'si02_tabela',
        'si02_taxa',
        'si02_criterioadjudicacao',
        'si02_mediapercentual',
        'si02_vltotalprecoreferencia'
    ];
}
