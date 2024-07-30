<?php

namespace App\Models;

class EmpAnexo extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'empanexo';

    /**
     * @var string
     */
    protected $primaryKey = 'e100_sequencial';

    protected string $sequenceName = "empanexo_e100_sequencial_seq";

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
        'e100_sequencial',
        'e100_empenho',
        'e100_usuario',
        'e100_instit',
        'e100_tipoanexo',
        'e100_datalancamento',
        'e100_titulo',
        'e100_sequencialpncp',
        'e100_sequencialarquivo',
        'e100_anexo'
    ];
}
