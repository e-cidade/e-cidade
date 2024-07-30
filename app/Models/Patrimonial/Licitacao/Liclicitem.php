<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Liclicitem extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.liclicitem';

    protected $primaryKey = 'l21_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'licitacao.liclicitem_l21_codigo_seq';

    protected $fillable = [
        'l21_codigo',
        'l21_codliclicita',
        'l21_codpcprocitem',
        'l21_situacao',
        'l21_ordem',
        'l21_reservado',
        'l21_sigilo'
    ];

}
