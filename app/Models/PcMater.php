<?php

namespace App\Models;

class PcMater extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'compras.pcmater';

    /**
     * @var string
     */
    protected $primaryKey = ' pc01_codmater';

     /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected string $sequenceName = 'compras.pcmater_pc01_codmater_seq';

    protected $fillable = [
        'pc01_codmater',
        'pc01_descrmater',
        'pc01_complmater',
        'pc01_codsubgrupo',
        'pc01_ativo',
        'pc01_conversao',
        'pc01_id_usuario',
        'pc01_libaut',
        'pc01_servico',
        'pc01_veiculo',
        'pc01_validademinima',
        'pc01_obrigatorio',
        'pc01_fraciona',
        'pc01_liberaresumo',
        'pc01_data',
        'pc01_tabela',
        'pc01_taxa',
        'pc01_obras',
        'pc01_dataalteracao',
        'pc01_justificativa',
        'pc01_instit',
        'pc01_codmaterant',
        'pc01_regimobiliario',
    ];

    /**
     *  Indicates if the timestamp is active.
     *
     * @var boolean
     */
    public $timestamps = false;


    public function acordoItem()
    {
        return $this->hasMany(AcordoItem::class, 'ac20_pcmater', 'pc01_codmater');
    }
}
