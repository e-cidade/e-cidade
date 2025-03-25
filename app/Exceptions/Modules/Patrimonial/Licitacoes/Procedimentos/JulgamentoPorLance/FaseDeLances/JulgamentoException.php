<?php

namespace App\Exceptions\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances;

use Exception;

class JulgamentoException extends Exception
{
    protected $code;
    protected $message;

    public function __construct($message = "Erro ao registrar julgamento.", $code = 400)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function render()
    {
        return response()->json([
            'error' => $this->message
        ], $this->code);
    }
}
