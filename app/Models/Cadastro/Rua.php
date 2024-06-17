<?php

namespace App\Models\Cadastro;

use App\Models\LegacyModel;
use App\Support\String\StringHelper;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Builder;

class Rua extends LegacyModel
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
    protected $table = 'cadastro.ruas';
    /**
     * @var string
     */
    protected $primaryKey = 'j14_codigo';

    protected string $sequenceName = 'custom';

    protected $fillable = [
        'j14_codigo',
        'j14_nome',
        'j14_tipo',
        'j14_rural',
        'j14_lei',
        'j14_dtlei',
        'j14_bairro',
        'j14_obs',
    ];

    public function getNextval(): int
    {
        $rua = $this->newQuery()->orderByDesc('j14_codigo')->first();

        if (empty($rua)) {
            return 1;
        }

        return $rua->j14_codigo+1;
    }

    public function scopeNome(Builder $query, $nome): Builder
    {
        return $query->whereRaw(
            "upper(to_ascii(j14_nome)) = ?",
            [strtoupper(StringHelper::removeAccent($nome))]
        );
    }
}
