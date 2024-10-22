<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use LogicException;

trait LegacyAccount
{
    public string $tableName;
    public string $schemaName = 'public';

    public function save(array $options = []): bool
    {
        $this->persistLegacyAccount($this, $this->exists ? 'A' : 'I');
        return parent::save($options);
    }
    public function update(array $attributes = [], array $options = [])
    {
        $this->persistLegacyAccount($this, 'A');
        parent::update($attributes, $options);
    }

    public function delete()
    {
        $this->persistLegacyAccount($this, 'D');
        parent::delete();
    }

    public function persistLegacyAccount(Model $model, string $action = 'I')
    {
        return;
        $sequence = $this->getAccountNextVal();

        if (empty($model->fillable)) {
            throw new LogicException('Para salvar os dados do log do account do e-cidade,
             utilizando o eloquent, configure a propriedade $fillable dentro do model.');
        }

        if (empty($model->primaryKey)) {
            throw new LogicException('Para salvar os dados do log do account do e-cidade,
             utilizando o eloquent, configure a propriedade $primaryKey dentro do model.');
        }

        if (empty($model->table)) {
            throw new LogicException('Para salvar os dados do log do account do e-cidade,
             utilizando o eloquent, configure a propriedade $table dentro do model.');
        }

        $this->setTableNameAndSchemaName();

        $this->insertAcountAcesso($sequence);
        $this->insertAcountKey($sequence, $action);
        $codArq = $this->getCodarqByTableName($this->tableName);

        if (empty($codArq)) {
            throw new LogicException("Tabela {$this->tableName} não cadastrada no discionário de dados do E-cidade.");
        }

        /**
         * $model->attributes
         * @todo replace with the attribute property
         */
        foreach ($model->fillable as $field) {
            $oldValue = empty($model->getOriginal($field)) ? '' : $model->getOriginal($field);
            $newValue = empty($model->getAttributeValue($field)) ? '' : $model->getAttributeValue($field);
            $codCam = $this->getCodcamByName($field);
            if (empty($codCam)) {
                continue;
            }
            $this->insertAcount($sequence, $codArq, $codCam, $newValue, $oldValue);
        }
    }

    protected function getAccountNextVal(): int
    {
        ['acount' => $sequence] = (array)DB::connection()
            ->selectOne("select nextval('configuracoes.db_acount_id_acount_seq') as acount");
        return $sequence;
    }

    protected function setTableNameAndSchemaName(): void
    {
        $tableNameParts = explode('.', $this->table);
        if (count($tableNameParts) === 1) {
            $this->tableName = $tableNameParts[0];
            return;
        }
        $this->schemaName = $tableNameParts[0];
        $this->tableName = $tableNameParts[1];
    }

    protected function insertAcountAcesso($sequence)
    {
        DB::connection()
            ->unprepared("insert into configuracoes.db_acountacesso values($sequence," . session("DB_acessado") . ")");
    }

    protected function insertAcountKey(int $sequence, string $action)
    {
        foreach (explode(',', $this->getKeyName()) as $primaryKey) {
            $codCam = $this->getCodcamByName($primaryKey);
            if (empty($codCam)) {
                continue;
            }
            DB::connection()
                ->unprepared("insert into configuracoes.db_acountkey values($sequence,$codCam,'$primaryKey','$action')");
        }
    }

    protected function getCodcamByName(string $name): ?int
    {
        $sql = "select db_syscampo.codcam
                from configuracoes.db_syscampo
                inner join configuracoes.db_sysarqcamp on db_syscampo.codcam = db_sysarqcamp.codcam
                inner join configuracoes.db_sysarquivo on db_sysarquivo.codarq = db_sysarqcamp.codarq
                where db_sysarquivo.nomearq = '{$this->tableName}'
                and db_syscampo.nomecam = '{$name}'";
        ['codcam' => $codCam] = (array)DB::connection()->selectOne($sql);
        return $codCam;
    }

    protected function getCodarqByTableName(string $tableName): ?int
    {
        ['codarq' => $codarq] = (array)DB::connection()
            ->selectOne("select codarq from configuracoes.db_sysarquivo where nomearq = '{$tableName}'");
        return $codarq;
    }

    protected function insertAcount(int $sequence, int $codarq, int $codcam, string $newValue, string $oldValue = '')
    {
        $newValue = addslashes($newValue);
        $oldValue = addslashes($oldValue);
        DB::connection()
            ->unprepared("insert into configuracoes.db_acount
                        values(
                               $sequence,
                               $codarq,
                               $codcam,
                               '{$oldValue}',
                               '{$newValue}',
                               " . session('DB_datausu') . ",
                               " . session('DB_id_usuario') . ")"
            );
    }
}
