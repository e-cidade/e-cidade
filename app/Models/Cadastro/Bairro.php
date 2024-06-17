<?php

namespace App\Models\Cadastro;

use App\Models\LegacyModel;
use App\Support\String\StringHelper;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Builder;

class Bairro extends LegacyModel
{
    use LegacyAccount;
    /**
     * @var string
     */
    protected $table = 'cadastro.bairro';

    /**
     * @var string
     */
    protected $primaryKey = 'j13_codi';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public $timestamps = false;

    public string $sequenceName = 'bairro_j13_codi_seq';

    protected $fillable = [
        'j13_codi',
        'j13_descr',
        'j13_codant',
        'j13_rural',
    ];

    public function scopeNome(Builder $query, $nome)
    {
        return $query->whereRaw(
            "upper(to_ascii(j13_descr)) = ?",
            [strtoupper(StringHelper::removeAccent($nome))]
        );
    }
}
