<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class VeicDevolucao extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veicdevolucao_ve61_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veicdevolucao';

    /**
     * @var string
     */
    protected $primaryKey = 've61_codigo';

    /**
     * @var array
     */
    protected $fillable = [
        've61_codigo',
        've61_veicretirada',
        've61_veicmotoristas',
        've61_datadevol',
        've61_horadevol',
        've61_medidadevol',
        've61_usuario',
        've61_data',
        've61_hora',
        've61_importado'
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

    public function veicRetirada()
    {
        return $this->belongsTo(VeicRetirada::class,  've61_veicretirada', 've60_codigo');
    }
}
