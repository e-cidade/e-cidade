<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

class CompanyActivityDTO extends BaseDTO
{
    public ActivityDTO $atividade;
    public bool $atividadePrincipal;

    public function __construct(array $data)
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $attribute => $value) {
            if ($attribute === 'atividade') {
                $value = new ActivityDTO((array)$value);
            }
            $this->$attribute = $value;
        }
    }
}
