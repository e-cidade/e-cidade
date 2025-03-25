<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Solicitem extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.solicitem';

    protected $primaryKey = 'pc11_codigo';

    protected string $sequenceName = 'solicitem_pc11_codigo_seq';

    public $incrementing = false;

    protected $fillable = [
        'pc11_codigo',
        'pc11_numero',
        'pc11_seq',
        'pc11_quant',
        'pc11_vlrun',
        'pc11_prazo',
        'pc11_pgto',
        'pc11_resum',
        'pc11_just',
        'pc11_liberado',
        'pc11_servicoquantidade',
        'pc11_reservado',
        'pc11_exclusivo',
        'pc11_usuario'
    ];
    
    public function solicitempcmater()
    {
        return $this->belongsTo(Solicitempcmater::class, 'pc11_codigo', 'pc16_solicitem');
    }
}
