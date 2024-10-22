<?php

namespace App\Models\Cadastro;

use App\Models\LegacyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IptuFoto extends LegacyModel
{
    use HasFactory;

    public $table = 'cadastro.iptufotos';

    protected $fillable = [
        'j54_fotoativa',
        'j54_principal',
        'j54_matric',
        'j54_url',
        'j54_nomearquivo'
    ];
}
