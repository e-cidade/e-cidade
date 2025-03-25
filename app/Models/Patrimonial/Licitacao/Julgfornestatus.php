<?php

namespace App\Models\Patrimonial\Licitacao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Julgfornestatus extends Model
{
    use HasFactory;

    // Definindo o nome da tabela no banco de dados
    protected $table = 'licitacao.julgfornestatus';

    // Definindo a chave primária da tabela
    protected $primaryKey = 'l35_codigo';

    // Indicando que a chave primária é auto-incrementada
    public $incrementing = true;

    // Definindo os tipos de dados da chave primária
    protected $keyType = 'int';

    // Informando ao Eloquent que a tabela não utiliza timestamps padrão
    public $timestamps = false;

    // Definindo os campos atribuíveis em massa
    protected $fillable = [
        'l35_label',
        'l35_desc',
    ];

    // Relacionamento com a tabela julgforne
    public function julgfornes()
    {
        return $this->hasMany(Julgforne::class, 'l34_julgfornestatus', 'l35_codigo');
    }
}
