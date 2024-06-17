<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cnae extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'issqn.cnae';

    protected $primaryKey = 'q71_sequencial';

    protected string $sequenceName = 'issqn.cnae_q71_sequencial_seq';

    protected $fillable = [
        'q71_estrutural',
        'q71_descr',
    ];

    public function cnaeAnalitica(): HasOne
    {
        return $this->hasOne(CnaeAnalitica::class, 'q72_cnae', 'q71_sequencial');
    }
}
