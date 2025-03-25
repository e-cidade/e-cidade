<?php

namespace App\Models\Empenho;

use App\Models\LegacyModel;

class Empempaut extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'empenho.empempaut';

    /**
     * @var string
     */
    protected $primaryKey = 'e61_numemp';

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
      'e61_numemp',
      'e61_autori'
    ];
}
