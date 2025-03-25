<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class ObrasDadosComplementares extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.obrasdadoscomplementares';

    protected $primaryKey = 'db150_sequencial';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'db150_sequencial',
        'db150_codobra',
        'db150_pais',
        'db150_estado',
        'db150_municipio',
        'db150_distrito',
        'db150_bairro',
        'db150_numero',
        'db150_logradouro',
        'db150_grauslatitude',
        'db150_minutolatitude',
        'db150_segundolatitude',
        'db150_grauslongitude',
        'db150_minutolongitude',
        'db150_segundolongitude',
        'db150_classeobjeto',
        'db150_grupobempublico',
        'db150_subgrupobempublico',
        'db150_atividadeobra',
        'db150_atividadeservico',
        'db150_descratividadeservico',
        'db150_atividadeservicoesp',
        'db150_descratividadeservicoesp',
        'db150_bdi',
        'db150_cep',
        'db150_seqobrascodigos',
        'db150_planilhatce',
    ];

}
