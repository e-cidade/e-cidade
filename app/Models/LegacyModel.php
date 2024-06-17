<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Builders\LegacyBuilder;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class LegacyModel extends Model
{
    public array $legacy = [];

    protected string $builder = LegacyBuilder::class;

    public function __construct(array $attributes = [])
    {
        if (!$this->shouldUseLegacyNextval() && $this->incrementing) {
            $this->sequenceName = $this->getSequenceName();
        }
        parent::__construct($attributes);
    }

    /**
     * Used to override the sequence name
     * @see $this->getSequenceName()
     * @var string
     */
    protected string $sequenceName = '';

    /**
     * Ensure that the primary key has a auto-increment valid for legacy nextval
     * @param array $options
     * @return bool
     */
    public function save(array $options = []): bool
    {
        if(!$this->exists && $this->shouldUseLegacyNextval()) {
            $this->setAttribute($this->primaryKey, $this->getNextval());
        }
        return parent::save($options);
    }

    public function getNextval(): int
    {
        if (empty($this->sequenceName)) {
            throw new LogicException('É necessário informar a propriedade sequenceName no model.');
        }
        ['acount' => $sequence] = (array)DB::connection()
            ->selectOne("select nextval('{$this->sequenceName}') as acount");
        return $sequence;
    }

    private function shouldUseLegacyNextval(): bool
    {
        return property_exists($this, 'sequenceName') && !empty($this->sequenceName);
    }

    /**
     * Workaround of trying to get the sequence name
     * @return string
     */
    private function getSequenceName(): string
    {
        $tableNameWithoutSchema = $this->getTableNameWithoutSchema();
        return "{$tableNameWithoutSchema}_{$this->primaryKey}_seq";
    }

    public function getTableNameWithoutSchema(): string
    {
        return $this->getTableName()['tableName'];
    }

    public function getSchemaName(): string
    {
        return $this->getTableName()['schemaName'];
    }

    /**
     * @return string[]
     */
    public function getTableName(): array
    {
        $tableName = ['schemaName' => 'public'];
        $tableNameParts = explode('.', $this->table);
        if (count($tableNameParts) === 1) {
            $tableName['tableName'] = $tableNameParts[0];
            return $tableName;
        }
        $tableName['schemaName'] = $tableNameParts[0];
        $tableName['tableName'] = $tableNameParts[1];

        return $tableName;
    }
}
