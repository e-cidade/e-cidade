<?php

namespace App\Models\Configuracao;

use App\Models\LegacyModel;

class AssinaturaDigitalAssinante extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'configuracoes.assinatura_digital_assinante';

    /**
     * @var string
     */
    protected $primaryKey = 'db243_codigo';


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public $timestamps = false;

    protected string $sequenceName = 'assinatura_digital_assinante_db243_codigo_seq';

    /**
     * @var array
     */
    protected $fillable = [
        'db243_instit',
        'db243_orgao',
        'db243_unidade',
        'db243_usuario',
        'db243_cargo',
        'db243_documento',
        'db243_data_inicio',
        'db243_data_final',
        'db243_anousu'
    ];
    public const DOCUMENTOS_ASSINAVEIS = [ 'Empenho', 'Liquidação',  'Pagamento', 'Pagamento Extra' ];

    /**
     *
     * @var array
     */
    public const ASSINTAURA_CARGOS = [
                    '0' => 'Outros',
                    '1' => 'Ordenador Despesa',
                    '2' => 'Ordenador Liquidação',
                    '3' => 'Ordenador Pagamento',
                    '4' => 'Contador',
                    '5' => 'Tesoureiro',
                    '6' => 'Gestor',
                    '7' => 'Controle Interno',
                    ];


}
