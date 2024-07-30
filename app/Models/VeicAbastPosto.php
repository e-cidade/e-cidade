<?php

namespace App\Models;

class VeicAbastPosto extends LegacyModel
{
    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veicabastposto_ve71_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veicabastposto';

    /**
     * @var string
     */
    protected $primaryKey = 've71_codigo';

    /**
     * @var array
     */
    protected $fillable = [
        've71_codigo',
        've71_veicabast',
        've71_veiccadposto',
        've71_nota',
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
