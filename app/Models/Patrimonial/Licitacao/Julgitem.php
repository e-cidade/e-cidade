<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\Patrimonial\Compras\Pcorcamitem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Julgitem extends Model
{
    use HasFactory;

    // Definindo o nome da tabela no banco de dados
    protected $table = 'licitacao.julgitem';

    // Definindo a chave primária da tabela
    protected $primaryKey = 'l30_codigo';

    // Indicando que a chave primária não é auto-incrementada
    public $incrementing = true;

    // Definindo os tipos de dados das colunas
    protected $keyType = 'int';

    // Informando ao Eloquent que as colunas de data não seguem o padrão "timestamps"
    public $timestamps = false;

    // Definindo os campos atribuíveis em massa
    protected $fillable = [
        'l30_orcamitem',
        'l30_julgitemstatus',
        'l30_numerolote',
        'l30_motivo',
        'l30_created_at',
        'l30_updated_at',
    ];

    // Relacionamento com a tabela julgitemstatus
    public function julgitemstatus()
    {
        return $this->belongsTo(Julgitemstatus::class, 'l30_julgitemstatus', 'l31_codigo');
    }

    // Relacionamento com a tabela pcorcamitem
    public function pcorcamitem()
    {
        return $this->belongsTo(Pcorcamitem::class, 'l30_orcamitem', 'pc22_orcamitem');
    }
}
