<?php

namespace App\Models\Cadastro;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuaBairro extends LegacyModel
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
    protected $table = 'cadastro.ruasbairro';
    /**
     * @var string
     */
    protected $primaryKey = 'j16_codigo';

    public string $sequenceName = 'ruasbairro_j16_codigo_seq';

    protected $fillable = [
        'j16_codigo',
        'j16_lograd',
        'j16_bairro'
    ];

    public function rua(): BelongsTo
    {
        return $this->belongsTo(Rua::class, 'j14_codigo', 'j16_lograd');
    }

    public function bairro(): BelongsTo
    {
        return $this->belongsTo(Bairro::class, 'j13_codi', 'j16_bairro');
    }
}
