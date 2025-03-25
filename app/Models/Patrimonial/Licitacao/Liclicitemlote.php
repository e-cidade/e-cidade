<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Liclicitemlote extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.liclicitemlote';

    protected $primaryKey = 'l04_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'licitacao.liclicitemlote_l04_codigo_seq';

    protected $fillable = [
        'l04_codigo',
        'l04_liclicitem',
        'l04_descricao',
        'l04_seq',
        'l04_numerolote',
        'l04_codlilicitalote'
    ];

    public function liclicitem()
    {
        return $this->belongsTo(Liclicitem::class, 'l04_liclicitem', 'l21_codigo');
    }
}

