<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ativid extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'issqn.ativid';

    protected $primaryKey = 'q03_ativ';

    protected $fillable = [
        'q03_descr',
        'q03_atmemo',
        'q03_limite',
        'q03_horaini',
        'q03_horafim',
        'q03_deducao',
        'q03_tributacao_municipio',
    ];


    public function atividCnae(): HasOne
    {
        return $this->hasOne(AtividCnae::class, 'q03_ativ', 'q74_ativid');
    }

    public function tabativ(): HasMany
    {
        return $this->hasMany(Ativprinc::class, 'q07_ativ', 'q03_ativ');
    }
}
