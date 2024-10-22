<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Licpropostavinc extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.licpropostavinc';

    protected $primaryKey = 'l223_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'licitacao.licpropostavinc_l223_codigo_seq';

    protected $fillable = [
        'l223_codigo',
        'l223_liclicita',
        'l223_fornecedor'
    ];

}
