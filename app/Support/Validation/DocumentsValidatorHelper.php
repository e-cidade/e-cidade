<?php declare(strict_types=1);

namespace App\Support\Validation;

class DocumentsValidatorHelper
{
    public static function validar(string $documento): bool {
        $documento = preg_replace('/\D/', '', $documento);

        if (strlen($documento) === 11) {
            return self::validarCPF($documento);
        } elseif (strlen($documento) === 14) {
            return self::validarCNPJ($documento);
        }

        return false;
    }

    private static function validarCPF(string $cpf): bool {
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += (int) $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ((int) $cpf[$t] !== $d) {
                return false;
            }
        }

        return true;
    }

    private static function validarCNPJ(string $cnpj): bool {
        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        $pesos = [
            [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2],
            [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
        ];

        for ($t = 0; $t < 2; $t++) {
            $soma = 0;
            for ($i = 0; $i < count($pesos[$t]); $i++) {
                $soma += (int) $cnpj[$i] * $pesos[$t][$i];
            }
            $d = ($soma % 11) < 2 ? 0 : 11 - ($soma % 11);
            if ((int) $cnpj[12 + $t] !== $d) {
                return false;
            }
        }

        return true;
    }
}
