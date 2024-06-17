<?php

namespace App\Models\ISSQN;

use App\Models\Cadastro\Bairro;
use App\Models\Issbase;
use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssBairro extends LegacyModel
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
    protected $table = 'issqn.issbairro';
    /**
     * @var string
     */
    protected $primaryKey = 'q13_inscr';

    protected $fillable = [
        'q13_inscr',
        'q13_bairro',
    ];

    public function issbase(): BelongsTo
    {
        return $this->belongsTo(Issbase::class, 'q02_inscr', 'q13_inscr');
    }

    public function bairro(): BelongsTo
    {
        return $this->belongsTo(Bairro::class, 'j13_codi', 'q13_bairro');
    }
}
