<?php

use App\Support\Database\AsFunction;
use App\Support\Database\MigrationBase;

class UpdateFcCorre extends MigrationBase
{
    use AsFunction;

    public function up()
    {
        $this->createFunction('public.fc_corre', '2025-02-11');
    }

    public function down()
    {
        $this->createFunction('public.fc_corre', '2025-01-01');
    }
}
