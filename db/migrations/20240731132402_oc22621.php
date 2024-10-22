<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22621 extends PostgresMigration
{

    public function up()
    {
        $sSql = "
                BEGIN;

                update
                    pcmater
                set
                    pc01_justificativa = 'ajuste'
                where
                    pc01_codmater in (
                    select
                        db150_pcmater
                    from
                        historicomaterial
                    where
                        db150_tipocadastro = 2
                        and db150_data > '2024-01-01'
                        and (db150_justificativaalteracao = ''
                            or db150_justificativaalteracao is null));

                update
                    historicomaterial
                set
                    db150_justificativaalteracao = 'ajuste'
                where
                    db150_tipocadastro = 2
                    and db150_data > '2024-01-01'
                    and (db150_justificativaalteracao = ''
                        or db150_justificativaalteracao is null);

                COMMIT;
                    ";
        $this->execute($sSql);
    }
}
