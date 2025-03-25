<?php

namespace App\Models\Contabilidade;

use App\Models\LegacyModel;

class OperacaoCredito extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'public.db_operacaodecredito';

    /**
     * @var string
     */
    protected $primaryKey = 'op01_sequencial';


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    public $timestamps = false;

    protected string $sequenceName = 'db_operacaodecredito_op01_sequencial_seq';

    /**
     * @var array
     */
    protected $fillable = [
        "op01_sequencial",
        "op01_numerocontratoopc",
        "op01_dataassinaturacop",
        "op01_numeroleiautorizacao",
        "op01_dataleiautorizacao",
        "op01_valorautorizadoporlei",
        "op01_credor",
        "op01_tipocredor",
        "op01_tipolancamento",
        "op01_detalhamentodivida",
        "op01_subtipolancamento",
        "op01_objetocontrato",
        "op01_descricaodividaconsolidada",
        "op01_descricaocontapcasp",
        "op01_moedacontratacao",
        "op01_taxajurosdemaisencargos",
        "op01_valorcontratacao",
        "op01_dataquitacao",
        "op01_instituicao",
        "op01_datadepublicacaodalei",
        "op01_datadecadastro"
    ];
}
