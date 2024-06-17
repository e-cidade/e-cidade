<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;

class CertBaixaNumero extends LegacyModel
{
    public $timestamps = false;

    protected $table = 'issqn.certbaixanumero';

    protected $primaryKey = 'q79_sequencial';

    protected $fillable = [
        'q79_sequencial',
        'q79_anousu',
        'q79_ultcodcertbaixa',
    ];

    /**
     * @param int $ano
     * @return int|null
     */
    public function getLastNumeroCertidao(int $ano): ?int
    {
        $number = $this->newQuery()->where('q79_anousu', $ano)->first()->q79_ultcodcertbaixa;
        if (empty($number)) {
            return null;
        }
        return $number+1;
    }
}
