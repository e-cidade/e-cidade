<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class Arrepaga extends LegacyModel
{
    use LegacyAccount;

    /**
     * @var string
     */
    protected $table = 'caixa.arrecant';

    /**
     * @var string
     */
    protected $primaryKey = 'k00_numpre, k00_numpar';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'k00_numcgm',
        'k00_dtoper',
        'k00_receit',
        'k00_hist',
        'k00_valor',
        'k00_dtvenc',
        'k00_numpre',
        'k00_numpar',
        'k00_numtot',
        'k00_numdig',
        'k00_conta',
        'k00_dtpaga',
    ];
}