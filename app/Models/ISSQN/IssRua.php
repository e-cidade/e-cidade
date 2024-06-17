<?php

namespace App\Models\ISSQN;

use App\Models\Cadastro\Bairro;
use App\Models\Cadastro\Rua;
use App\Models\Issbase;
use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssRua extends LegacyModel
{
    use LegacyAccount;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'issqn.issruas';
    /**
     * @var string
     */
    protected $primaryKey = 'q02_inscr';

    protected $fillable = [
        'q02_inscr',
        'j14_codigo',
        'q02_numero',
        'q02_compl',
        'q02_cxpost',
        'z01_cep'
    ];

    public function issbase(): BelongsTo
    {
        return $this->belongsTo(Issbase::class, 'q02_inscr', 'q02_inscr');
    }

    public function rua(): BelongsTo
    {
        return $this->belongsTo(Rua::class, 'j14_codigo', 'j14_codigo');
    }
}
