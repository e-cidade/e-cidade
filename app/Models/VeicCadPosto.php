<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class VeicCadPosto extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veiccadposto_ve29_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veiccadposto';

    /**
     * @var string
     */
    protected $primaryKey = 've29_codigo';

    /**
     * @var array
     */
    protected $fillable = [
       've29_codigo',
       've29_tipo'
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


    public function veicCadPostoExterno()
    {
        return $this->hasOne(VeicCadPostoExterno::class, 've34_veiccadposto','ve29_codigo');
    }
}

