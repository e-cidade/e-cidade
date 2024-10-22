<?php

namespace App\Models;

class PcSubGrupo extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'compras.pcsubgrupo';

    /**
     * @var string
     */
    protected $primaryKey = ' pc04_codsubgrupo';

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


    protected $fillable = [
        'pc04_codsubgrupo',
        'pc04_descrsubgrupo',
        'pc04_codgrupo',
        'pc04_codtipo',
        'pc04_ativo',
        'pc04_tipoutil',
        'pc04_instit',
    ];

    public function acordoItem()
    {
        return $this->hasMany(AcordoItem::class, 'ac20_pcmater', 'pc01_codmater');
    }
}
