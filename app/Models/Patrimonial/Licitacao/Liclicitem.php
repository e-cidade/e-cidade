<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Models\Patrimonial\Compras\Pcprocitem;
use App\Models\Patrimonial\Compras\Solicitem;
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

    public function liclicita()
    {
        return $this->belongsTo(Liclicita::class, 'l21_codliclicita', 'l20_codigo');
    }

    public function pcprocitem()
    {
        return $this->belongsTo(Pcprocitem::class, 'l21_codpcprocitem', 'pc81_codprocitem');
    }

    public function solicititem()
    {
        return $this->hasMany(Solicitem::class, 'pc11_codigo', 'pc81_solicitem');
    }

    public function liclicitemlote()
    {
        return $this->hasOne(Liclicitemlote::class, 'l04_liclicitem', 'l21_codigo');
    }
}
