<?php

use App\Support\Database\Sequence;
use Classes\PostgresMigration;

class Redesim008 extends PostgresMigration
{
    use Sequence;

    public function up()
    {
        $sql = "
        BEGIN;

        alter table redesim_log alter column q190_sequencial set default nextval('issqn.redesim_log_q190_sequencial_seq'::text::regclass);

        COMMIT;
        ";
        $this->execute($sql);
        $this->createRedesimErros();
    }

    public function createRedesimErros() {
        $this->createSequence('redesim_log_job_q191_sequencial_seq', 'issqn');

        $this->table(
            'redesim_log_job',
            [
                'id' => false,
                'primary_key' => ['q191_sequencial'],
                'schema' => 'issqn'
            ]
        )
            ->addColumn('q191_sequencial', 'integer')
            ->addColumn('q191_started', 'datetime')
            ->addColumn('q191_ended', 'datetime', ['null' => true])
            ->addColumn('q191_response', 'text', ['null' => true])
            ->create();


        $this->setAsAutoIncrement(
            'issqn.redesim_log_job',
            'q191_sequencial',
            'issqn.redesim_log_job_q191_sequencial_seq'
        );
    }
}
