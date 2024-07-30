<?php
namespace App\Models;

use App\Traits\LegacyAccount;

class VeicCadPostoExterno extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veiccadpostoexterno_ve34_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veiccadpostoexterno';

    /**
     * @var string
     */
    protected $primaryKey = 've34_codigo';

    /**
     * @var array
     */
    protected $fillable = [
       've34_codigo',
       've34_veiccadposto',
       've34_numcgm'
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

    public function veicCadPosto()
    {
        return $this->belongsTo(VeicCadPosto::class,'ve34_veiccadposto','ve29_codigo');
    }
}
