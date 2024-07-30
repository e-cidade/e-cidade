<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\Filters;

use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IFilters;
use DateTime;

class BaseFilters implements IFilters
{
    /**
     * Paginação utilizada quando houver mais de 100 registros
     * @var ?int
     */
    public ?int $pagina = null;

    /**
     * Filtra registros incluídos a partir dessa data
     * @var ?DateTime
     */
    public ?DateTime $dataInicio = null;

    /**
     * Filtra registros incluídos até essa data
     * @var ?DateTime
     */
    public ?DateTime $dataTermino = null;

    /**
     * Filtro de pesquisa
     * @var ?int
     */
    public ?int $id = null;

    public function __construct(array $data = null)
    {

        if ($data === null) {
            return;
        }

        foreach ($data as $attribute => $value) {
            if (in_array($attribute, ['dataInicio', 'dataTermino'])) {
                $value = $this->formatDateBr($value);
            }

            $this->$attribute = $value;
        }
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        $array = [];

        if (!empty($this->id)) {
            $array['id'] = $this->id;
        }

        if (!empty($this->dataInicio)) {
            $array['dataInicio'] = $this->dataInicio->format('d/m/Y H:i');
        }

        if (!empty($this->dataInicio)) {
            $array['dataTermino'] = $this->dataTermino->format('d/m/Y H:i');
        }

        if (!empty($this->pagina)) {
            $array['pagina'] = $this->pagina;
        }

        return $array;
    }

    public function formatDateBr(string $value): DateTime
    {
        $value = DateTime::createFromFormat('d/m/Y H:i', $value);

        if (!$value) {
            $value = (new DateTime())->format('d/m/Y H:i');
        }

        return $value;
    }

}
