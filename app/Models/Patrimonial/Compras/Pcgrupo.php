<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;

class Pcgrupo extends LegacyModel
{
    protected $table = 'compras.pcgrupo';

    /**
     * @var string
     */
    protected $primaryKey = ' pc03_codgrupo';

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


    protected $fillable = [
        'pc03_codgrupo',
        'pc03_descrgrupo',
        'pc03_ativo',
        'pc03_instit'
    ];
}
