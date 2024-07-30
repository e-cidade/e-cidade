<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class VeicAbastRetirada extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veicabastretirada_ve73_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veicabastretirada';

    /**
     * @var string
     */
    protected $primaryKey = 've73_codigo';

    /**
     * @var array
     */
    protected $fillable = [
       've73_codigo',
       've73_veicabast',
       've73_veicretirada',
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

