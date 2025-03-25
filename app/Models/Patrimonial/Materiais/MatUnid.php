<?php

namespace App\Models\Patrimonial\Materiais;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class MatUnid extends LegacyModel{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'material.matunid';

    protected $primaryKey = 'm61_codmatunid';

    public $incrementing = false;

    protected $fillable = [
        'm61_codmatunid',
        'm61_descr',
        'm61_usaquant',
        'm61_abrev',
        'm61_usadec',
        'm61_codsicom',
        'm61_ativo'
    ];
}
