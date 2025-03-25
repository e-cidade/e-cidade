<?php

namespace App\Models\Patrimonial\Licitacao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Julglance extends Model
{
    use HasFactory;

    protected $table = 'licitacao.julglances';
    protected $primaryKey = 'l32_codigo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'l32_julgitem',
        'l32_julgforne',
        'l32_lance',
        'l32_created_at',
        'l32_updated_at',
    ];

    public function julgitem()
    {
        return $this->belongsTo(Julgitem::class, 'l32_julgitem', 'l30_codigo');
    }

    public function julgforne()
    {
        return $this->belongsTo(Julgforne::class, 'l32_julgforne', 'l34_codigo');
    }
}
