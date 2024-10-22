<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Builders\LegacyBuilder;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use LogicException;
use OwenIt\Auditing\Contracts\Auditable;

class LegacyModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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

    public function transformAudit(array $data): array
    {
        // Aplicar utf8_encode ou utf8_decode nos valores antigos e novos
        if (isset($data['new_values'])) {
            $data['new_values'] = $this->convertToUtf8($data['new_values']);
        }

        if (isset($data['old_values'])) {
            $data['old_values'] = $this->convertToUtf8($data['old_values']);
        }

        return $data;
    }

    /**
     * Método para codificar em UTF-8 os valores de um array
     */
    public function convertToUtf8($data, $encodingfrom = 'ISO-8859-1', $encodingto = 'UTF-8')
    {
        if (is_array($data)) {
            return array_map(function($item) use ($encodingfrom, $encodingto) {
                return $this->convertToUtf8($item, $encodingfrom, $encodingto);
            }, $data);
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = $this->convertToUtf8($value, $encodingfrom, $encodingto);
            }
            return $data;
        } elseif (is_string($data)) {
            // Verificar se a string já está na codificação desejada
            $currentEncoding = mb_detect_encoding($data, [$encodingfrom, $encodingto], true);

            // Só converter se a string estiver na codificação de origem esperada
            if ($currentEncoding === $encodingfrom) {
                return mb_convert_encoding($data, $encodingto, $encodingfrom);
            }
        }

        return $data;
    }
}
