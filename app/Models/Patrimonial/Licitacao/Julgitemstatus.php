<?php

namespace App\Models\Patrimonial\Licitacao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Julgitemstatus extends Model
{
    use HasFactory;

    // Definindo o nome da tabela no banco de dados
    protected $table = 'licitacao.julgitemstatus';

    // Definindo a chave primária da tabela
    protected $primaryKey = 'l31_codigo';

    // Indicando que a chave primária é auto-incrementada
    public $incrementing = true;

    // Definindo os tipos de dados da chave primária
    protected $keyType = 'int';

    // Informando ao Eloquent que a tabela não utiliza timestamps padrão
    public $timestamps = false;

    // Definindo os campos atribuíveis em massa
    protected $fillable = [
        'l31_label',
        'l31_desc',
    ];

    // Relacionamento com a tabela julgitem
    public function julgitems()
    {
        return $this->hasMany(Julgitem::class, 'l30_julgitemstatus', 'l31_codigo');
    }
}
