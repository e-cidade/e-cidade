<?php

namespace App\Models\Empenho;

use App\Models\LegacyModel;

class Empord extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'empenho.empord';

    /**
     * @var string
     */
    protected $primaryKey = 'e82_codmov';

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
        'e82_codmov',
        'e82_codord',
        'e82_id_documento_assinado',
        'e82_node_id_libresing',
    ];
}
