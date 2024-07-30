<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class VeicMotoristas extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veicmotoristas_ve05_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veicmotoristas';

    /**
     * @var string
     */
    protected $primaryKey = 've05_codigo';

    /**
     * @var array
     */
    protected $fillable = [
        've05_codigo',
        've05_numcgm',
        've05_habilitacao',
        've05_veiccadcategcnh',
        've05_dtvenc',
        've05_dtprimcnh',
        've05_veiccadmotoristasit ',
        've05_instit',
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


    public function cgm()
    {
        return $this->belongsTo(Cgm::class, 've05_numcgm', 'z01_numcgm');
    }
}
