<?php

namespace App\Traits;

use Exception;

trait ValidatesControllerProperties
{
    /**
     * Valida as propriedades obrigatórias do controlador.
     *
     * Este método verifica se a propriedade 'model' é um modelo Eloquent válido e se
     * a propriedade 'columns' é um array não vazio, onde cada coluna contém tanto
     * as chaves 'name' quanto 'label', que não podem estar vazias. Se qualquer uma
     * dessas condições falhar, uma exceção é lançada com uma mensagem apropriada.
     *
     * @throws Exception Se 'model' não for um modelo Eloquent válido ou 'columns' estiver definido incorretamente.
     */
    protected function validateRequiredProperties()
    {
        if (!isset($this->columns) || !is_array($this->columns) || empty($this->columns)) {
            throw new Exception("A propriedade 'columns' deve ser um array não vazio.");
        }

        foreach ($this->columns as $column) {
            // if (!isset($column['name']) || empty($column['name']) || !isset($column['label']) || empty($column['label'])) {
            if (!isset($column['name']) || empty($column['name'])) {
                throw new Exception("Cada coluna deve conter 'name' e 'label', e ambos não podem ser vazios.");
            }
        }
    }
}
