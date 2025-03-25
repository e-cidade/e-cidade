<?php

namespace App\Models\Patrimonial\Licitacao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Julgitemhist extends Model
{
    use HasFactory;

    /**
     * O nome da tabela.
     *
     * @var string
     */
    protected $table = 'licitacao.julgitemhist';

    /**
     * A chave primária da tabela.
     *
     * @var string
     */
    protected $primaryKey = 'l33_codigo';

    /**
     * Indica se o ID é auto-incrementado.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * O tipo da chave primária.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indica se os campos de timestamp são gerenciados automaticamente.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'l33_julgitem',
        'l33_julgitemstatus',
        'l33_motivo',
    ];

    /**
     * Os nomes dos campos de timestamps.
     *
     * @var string
     */
    const CREATED_AT = 'l33_created_at';
    const UPDATED_AT = 'l33_updated_at';

    /**
     * Define a relação com o modelo Julgitem.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function julgitem()
    {
        return $this->belongsTo(Julgitem::class, 'l33_julgitem', 'l30_codigo');
    }

    /**
     * Define a relação com o modelo Julgitemstatus.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function julgitemstatus()
    {
        return $this->belongsTo(Julgitemstatus::class, 'l33_julgitemstatus', 'l31_codigo');
    }
}
