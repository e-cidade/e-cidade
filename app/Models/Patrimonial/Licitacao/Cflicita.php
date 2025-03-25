<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Cflicita extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.cflicita';

    protected $primaryKey = 'l03_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'licitacao.l03_codigo';

    protected $fillable = [
        'l03_codigo',
        'l03_descr',
        'l03_tipo',
        'l03_codcom',
        'l03_instit',
        'l03_usaregistropreco',
        'l03_pctipocompratribunal',
        'l03_presencial',
    ];

    public function liclicitas()
    {
        return $this->hasMany(Liclicita::class, 'l20_codtipocom', 'l03_codigo');
    }
}
