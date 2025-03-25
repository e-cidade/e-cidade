<?php

namespace App\Models\Patrimonial\Licitacao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Julgparam extends Model
{
    use HasFactory;

    protected $table = 'licitacao.julgparam';
    protected $primaryKey = 'l13_julgparam';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'l13_instit',
        'l13_precoref',
        'l13_difminlance',
        'l13_clapercent',
        'l13_avisodeacoestabela',
    ];
}
