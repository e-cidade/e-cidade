<?php

namespace App\Models\Concerns;

use App\Models\LegacyModel;

class Veicbaixa extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veicbaixa_ve04_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veicbaixa';

    /**
     * @var string
     */
    protected $primaryKey = 've04_codigo';

    /**
     * @var array
     */
    protected $fillable = [
        've04_codigo',
        've04_veiculo',
        've04_data ',
        've04_hora',
        've04_usuario',
        've04_motivo',
        've04_veiccadtipobaixa'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     *  Indicates if the timestamp is active.
     *
     * @var boolean
     */
    public $timestamps = false;

}
