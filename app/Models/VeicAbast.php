<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class VeicAbast extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veicabast_ve70_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veicabast';

    /**
     * @var string
     */
    protected $primaryKey = 've70_codigo';

    /**
     * @var array
     */
    protected $fillable = [
        've70_codigo',
        've70_veiculos',
        've70_veiculoscomb',
        've70_dtabast',
        've70_litros',
        've70_valor',
        've70_vlrun',
        've70_medida',
        've70_ativo',
        've70_usuario',
        've70_data',
        've70_hora',
        've70_observacao',
        've70_origemgasto',
        've70_importado'
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
