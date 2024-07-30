<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class EmpVeiculos extends LegacyModel
{
    use LegacyAccount;
    /**
     *
     * @var string
     */
    protected string $sequenceName = 'public.sic_empveiculos_si05_sequencial_seq';

    /**
     * @var string
     */
    protected $table = 'empveiculos';

    /**
     * @var string
     */
    protected $primaryKey = 'si05_sequencial';

    /**
     * @var array
     */
    protected $fillable = [
        'si05_sequencial',
        'si05_numemp',
        'si05_atestado',
        'si05_codabast',
        'si05_item_empenho',
    ];

     /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     *  Indicates if the timestamp is active.
     *
     * @var boolean
     */
    public $timestamps = false;
}
