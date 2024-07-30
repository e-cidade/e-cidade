<?php
namespace App\Models;

use App\Traits\LegacyAccount;

class VeicCadComb extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veiccadcomb_ve26_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veiccadcomb';

    /**
     * @var string
     */
    protected $primaryKey = 've26_codigo';

    /**
     * @var array
     */
    protected $fillable = [
       've26_codigo',
       've26_descr'
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

    public function veiculoComb()
    {
        return $this->hasMany(VeiculoComb::class, 've06_veiccadcomb', 've26_codigo');
    }
}
