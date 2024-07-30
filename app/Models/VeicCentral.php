<?php
namespace App\Models;

use App\Traits\LegacyAccount;

class VeicCentral extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veiccentral_ve40_sequencial_seq';

    /**
     * @var string
     */
    protected $table = 'veiccentral';

    /**
     * @var string
     */
    protected $primaryKey = 've40_sequencial';

    /**
     * @var array
     */
    protected $fillable = [
       've40_sequencial',
       've40_veiccadcentral',
       've40_veiculos'
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

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 've40_veiculos', 've01_codigo');
    }

    public function veicCadCentral()
    {
        return $this->belongsTo(VeicCadCentral::class, 've40_veiccadcentral', 've36_sequencial');
    }
}
