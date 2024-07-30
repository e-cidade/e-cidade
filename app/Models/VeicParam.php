<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class VeicParam extends LegacyModel
{
    use LegacyAccount;

    /**
     * @var string
     */
    protected $table = 'veicparam';

    /**
     * @var string
     */
    protected $primaryKey = 've50_codigo';

    /**
     * @var array
     */
    protected $fillable = [
        've50_codigo',
        've50_instit',
        've50_veiccadtipo',
        've50_veiccadcategcnh',
        've50_integrapatri',
        've50_postoproprio',
        've50_integrapessoal',
        've50_abastempenho',
        've50_datacorte'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

}
