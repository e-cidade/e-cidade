<?php

use App\Support\Database\AsFunction;
use App\Support\Database\Instituition;
use Phinx\Migration\AbstractMigration;

class Oc22626 extends AbstractMigration
{
    use AsFunction;
    use Instituition;

    public const PMPIRAPORA = '23539463000121';

    public function up()
    {
        if (empty($this->checkInstituicaoExists(self::PMPIRAPORA))) {
            return;
        }
       $this->createFunction('public.fc_iptu_getaliquota_pirapora_2024', '2024-06-28');
    }

    public function down()
    {
        if (empty($this->checkInstituicaoExists(self::PMPIRAPORA))) {
            return;
        }
        $this->createFunction('public.fc_iptu_getaliquota_pirapora_2024', '2024-01-01');
    }
}
