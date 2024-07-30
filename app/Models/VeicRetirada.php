<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class VeicRetirada extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veicretirada_ve60_codigo_seq';

    /**
     * @var string
     */
    protected $table = 'veicretirada';

    /**
     * @var string
     */
    protected $primaryKey = 've60_codigo';

    /**
     * @var array
     */
    protected $fillable = [
        've60_codigo',
        've60_veiculo',
        've60_veicmotoristas',
        've60_datasaida',
        've60_horasaida',
        've60_medidasaida',
        've60_destino',
        've60_coddepto',
        've60_usuario',
        've60_data',
        've60_hora',
        've60_destinonovo',
        've60_importado'
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
        return $this->belongsTo(Veiculo::class,  've60_veiculo', 've01_codigo');
    }

    public function veicDevolucao()
    {
        return $this->hasMany(VeicDevolucao::class, 've61_veicretirada', 've60_codigo');
    }
}
