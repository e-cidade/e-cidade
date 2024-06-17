<?php

use Phinx\Migration\AbstractMigration;

class Oc22504 extends AbstractMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO
                vinculopcaspmsc (c210_pcaspestrut, c210_mscestrut, c210_anousu)
            SELECT
                '621330000' as c210_pcaspestrut,
                '621390000' as c210_mscestrut,
                2024 as c210_anousu
            FROM
                vinculopcaspmsc
            WHERE
                NOT EXISTS(
                    SELECT
                        c210_pcaspestrut
                    FROM
                        vinculopcaspmsc
                    WHERE
                        c210_pcaspestrut = '621330000'
                        AND c210_mscestrut = '621390000'
                        AND c210_anousu = 2024
                )
            LIMIT 1;";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "DELETE FROM vinculopcaspmsc WHERE c210_pcaspestrut = '621330000' AND c210_mscestrut = '621390000' AND c210_anousu = 2024";

        $this->execute($sql);
    }
}
