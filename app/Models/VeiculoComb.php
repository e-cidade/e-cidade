<?php
namespace App\Models;

use App\Traits\LegacyAccount;

class VeiculoComb extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veiculoscomb_ve06_sequencial_seq';

    /**
     * @var string
     */
    protected $table = 'veiculoscomb';

    /**
     * @var string
     */
    protected $primaryKey = 've06_sequencial';

    /**
     * @var array
     */
    protected $fillable = [
       've06_sequencial',
       've06_veiccadcomb',
       've06_veiculos',
       've06_padrao'
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
        return $this->belongTo(Veiculo::class, 've06_veiculos', 've01_codigo');
    }

    public function veicCadComb()
    {
        return $this->belongsTo(VeicCadComb::class,'ve06_veiccadcomb', 've26_codigo');
    }
}
