<?php

namespace App\Models\Financeiro\Tesouraria;

use App\Traits\LegacyAccount;
use App\Models\LegacyModel;


class ContaBancaria extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'configuracoes.contabancaria';

    protected $primaryKey = 'db83_sequencial';

    protected string $sequenceName = 'contabancaria_db83_sequencial_seq';

    protected $fillable = [
        'db83_sequencial',
        'db83_descricao', 
        'db83_bancoagencia', 
        'db83_conta',
        'db83_dvconta', 
        'db83_identificador', 
        'db83_codigooperacao', 
        'db83_tipoconta', 
        'db83_contaplano', 
        'db83_convenio', 
        'db83_tipoaplicacao', 
        'db83_numconvenio',
        'db83_dataconvenio',
        'db83_nroseqaplicacao',
        'db83_codigoopcredito',
        'db83_dataimplantaoconta',
        'db83_fonteprincipal',
        'db83_codigotce',
        'db83_reduzido',
        'db83_instituicao'
    ];

}
