<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;

class IssAlvara extends LegacyModel
{
    public $timestamps = false;

    protected $table = 'issqn.issalvara';

    protected $primaryKey = 'q123_sequencial';

    protected $fillable = [
        'q123_sequencial',
        'q123_isstipoalvara',
        'q123_inscr',
        'q123_dtinclusao',
        'q123_situacao',
        'q123_usuario',
        'q123_geradoautomatico',
        'q123_numalvara',
    ];
}
