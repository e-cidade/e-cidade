<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class IssbaseLog extends LegacyModel
{
    use LegacyAccount;

    public const ORIGEM_AUTOMATICO = 1;
    public const ORIGEM_MANUAL = 2;
    public const ORIGEM_REDESIM = 3;

    public const INCLUSAO = 1;
    public const ALTERACAO = 2;
    public const EXCLUSAO = 3;

    public $timestamps = false;

    protected $table = 'issqn.issbaselog';

    protected $primaryKey = 'q102_sequencial';

    protected string $sequenceName = 'issbaselog_q102_sequencial_seq';

    protected $fillable = [
        'q102_inscr',
        'q102_issbaselogtipo',
        'q102_data',
        'q102_hora',
        'q102_obs',
        'q102_origem',
    ];
}
