<?php

namespace App\Models\Patrimonial\Licitacao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Julgfornehist extends Model
{
    use HasFactory;

    protected $table = 'licitacao.julgfornehist';
    protected $primaryKey = 'l36_codigo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    // Definindo os atributos que podem ser atribuídos em massa
    protected $fillable = [
        'l36_julgforne',
        'l36_julgfornestatus',
        'l36_motivo',
        'l36_created_at',
        'l36_updated_at',
    ];

    // Relacionamentos
    public function julgforne()
    {
        return $this->belongsTo(Julgforne::class, 'l36_julgforne', 'l34_codigo');
    }

    public function julgfornestatus()
    {
        return $this->belongsTo(Julgfornestatus::class, 'l36_julgfornestatus', 'l35_codigo');
    }
}
