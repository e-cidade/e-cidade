<?php

namespace App\Models\Patrimonial\Licitacao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Julgforne extends Model
{
    use HasFactory;

    protected $table = 'licitacao.julgforne';
    protected $primaryKey = 'l34_codigo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'l34_julgfornestatus',
        'l34_orcamforne',
        'l34_orcamitem',
        'l34_numerolote',
        'l34_motivo',
        'l34_created_at',
        'l34_updated_at',
    ];

    public function julgfornestatus()
    {
        return $this->belongsTo(Julgfornestatus::class, 'l34_julgfornestatus', 'l35_codigo');
    }
}