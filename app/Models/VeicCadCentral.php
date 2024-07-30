<?php
namespace App\Models;

use App\Traits\LegacyAccount;

class VeicCadCentral  extends LegacyModel
{
    use LegacyAccount;


    /**
     * @var string
     */
    protected $table = 'veiccadcentral';

    /**
     * @var string
     */
    protected $primaryKey = 've36_sequencial';

    /**
     * @var array
     */
    protected $fillable = [
       've36_sequencial',
       've36_coddepto'
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

    public function veicCentral()
    {
        return $this->hasMany(VeicCentral::class, 've40_veiccadcentral', 've36_sequencial');
    }
}
