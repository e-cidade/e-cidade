<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class Veiculo extends LegacyModel
{
    use LegacyAccount;

    /**
     *
     * @var string
     */
    protected string $sequenceName = 'veiculos.veiculos_ve01_codigo_seq ';

    /**
     * @var string
     */
    protected $table = 'veiculos';

    /**
     * @var string
     */
    protected $primaryKey = 've01_codigo';

    /**
     * @var array
     */
    protected $fillable = [
        've01_codigo',
        've01_placa',
        've01_veiccadtipo',
        've01_veiccadmarca',
        've01_veiccadmodelo',
        've01_veiccadcor',
        've01_veiccadproced',
        've01_veiccadcateg',
        've01_chassi',
        've01_ranavam',
        've01_placanum',
        've01_certif',
        've01_quantpotencia',
        've01_veiccadpotencia',
        've01_medidaini',
        've01_quantcapacidad',
        've01_veiccadtipocapacidade',
        've01_dtaquis',
        've01_veiccadcategcnh',
        've01_anofab',
        've01_anomod',
        've01_ceplocalidades',
        've01_ativo',
        've01_veictipoabast ',
        've01_nroserie',
        've01_codigoant',
        've01_codunidadesub ',
        've01_instit'
    ];

    /**
     * I',ndicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function veicCentral()
    {
        return $this->hasOne(VeicCentral::class, 've40_veiculos', 've01_codigo');
    }

    public function veiculoComb()
    {
        return $this->hasMany(VeiculoComb::class, 've06_veiculos', 've01_codigo');
    }

    public function veicRetirada()
    {
        return $this->hasMany(VeicRetirada::class, 've60_veiculo', 've01_codigo');
    }

    public function veicAbast()
    {
        return $this->hasMany(VeicAbast::class, 've70_veiculos', 've01_codigo');
    }
}
