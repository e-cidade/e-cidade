<?php 

declare(strict_types=1);

namespace App\Helpers;

use InvalidArgumentException;

/**
 * Matematica traz uma forma robusta de relizar operações matematicas, principalmente no contexto de operações financeiras e/ou com casas decimais, utilizando BC Math Functions.
 * @context Todas as operacoes usam . (ponto) como separador decimal
 * @see https://www.php.net/manual/en/ref.bc.php
 * @author daandrn <dandrn7@gmail.com>
 * @exceptionCode Parametro string não númerico: 990
 * @exceptionCode Divisor igual a 0: 991
 * @exceptionCode Escala fora do intervalo: 992
 */
final class Matematica
{
    private const CASAS_DECIMAIS_SEM       = 0;
    private const CASAS_DECIMAIS_UMA       = 1;
    private const CASAS_DECIMAIS_DUAS      = 2;
    private const CASAS_DECIMAIS_TRES      = 3;
    private const CASAS_DECIMAIS_QUATRO    = 4;
    private const CASAS_DECIMAIS_CINCO     = 5;
    private const CASAS_DECIMAIS_SEIS      = 6;
    private const CASAS_DECIMAIS_SETE      = 7;
    private const CASAS_DECIMAIS_OITO      = 8;
    private const CASAS_DECIMAIS_NOVE      = 9;
    private const CASAS_DECIMAIS_DEZ       = 10;
    private const CASAS_DECIMAIS_ONZE      = 11;
    private const CASAS_DECIMAIS_DOZE      = 12;
    private const CASAS_DECIMAIS_TREZE     = 13;
    private const CASAS_DECIMAIS_QUATORZE  = 14;
    private const CASAS_DECIMAIS_QUINZE    = 15;
    private const CASAS_DECIMAIS_DEZESSEIS = 16;
    private const CASAS_DECIMAIS_DEZESSETE = 17;

    private const ERRO_NAO_NUMERICO     = 990;
    private const ERRO_DIVISAO_POR_ZERO = 991;
    private const ERRO_ESCALA_INVALIDA  = 992;

    /**
     * Soma dois valores, arredondando o resultado.
     * @param string|int $adendo valor inicial.
     * @param string|int $parcela valor a ser acrescido.
     * @param int $casas_decimais Numero de casas decimais após arredondamento (Padrão: 2). Para resultado sem casas decimais usar CASAS_DECIMAIS_SEM.
     * @return string Retorna o resultado arredondado como string. Padrao: 2 casas decimais.
     * @throws InvalidArgumentException Caso $adendo ou $parcela não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 17.
     */
    public static function somar($adendo, $parcela, int $casas_decimais = self::CASAS_DECIMAIS_DUAS): string
    {
        self::validarNumericos($adendo, 'adendo');
        self::validarNumericos($parcela, 'parcela');
        self::validarIntervaloEscala($casas_decimais);

        $valor = self::saoInteiros($adendo, $parcela)
            ? (string)($adendo + $parcela)
            : bcadd((string) $adendo, (string) $parcela, self::CASAS_DECIMAIS_DEZESSETE);

        return self::arredondar($valor, $casas_decimais);
    }

    /**
     * Realiza uma subtração, arredondando o resultado.
     * @param string|int $minuendo valor de onde será subtraido.
     * @param string|int $subtraendo valor a ser subtraido.
     * @param int $casas_decimais Numero de casas decimais após arredondamento (Padrão: 2). Para resultado sem casas decimais usar CASAS_DECIMAIS_SEM.
     * @return string Retorna o resultado arredondado como string. Padrao: 2 casas decimais.
     * @throws InvalidArgumentException Caso $minuendo ou $subtraendo não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 17.
     */
    public static function subtrair($minuendo, $subtraendo, int $casas_decimais = self::CASAS_DECIMAIS_DUAS): string
    {
        self::validarNumericos($minuendo, 'minuendo');
        self::validarNumericos($subtraendo, 'subtraendo');
        self::validarIntervaloEscala($casas_decimais);

        $valor = self::saoInteiros($minuendo, $subtraendo)
            ? (string)($minuendo - $subtraendo)
            : bcsub((string) $minuendo, (string) $subtraendo, self::CASAS_DECIMAIS_DEZESSETE);

        return self::arredondar($valor, $casas_decimais);
    }

    /**
     * Realiza uma multiplicação, arredondando o resultado.
     * @param string|int $multiplicando valor a ser multiplicado.
     * @param string|int $multiplicador valor pelo qual será multiplicado.
     * @param int $casas_decimais Numero de casas decimais após arredondamento (Padrão: 2). Para resultado sem casas decimais usar CASAS_DECIMAIS_SEM.
     * @return string Retorna o resultado arredondado como string. Padrao: 2 casas decimais.
     * @throws InvalidArgumentException Caso $multiplicando ou $multiplicador não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 17.
     */
    public static function multiplicar($multiplicando, $multiplicador, int $casas_decimais = self::CASAS_DECIMAIS_DUAS): string
    {
        self::validarNumericos($multiplicando, 'multiplicando');
        self::validarNumericos($multiplicador, 'multiplicador');
        self::validarIntervaloEscala($casas_decimais);

        $valor = self::saoInteiros($multiplicando, $multiplicador)
            ? (string)($multiplicando * $multiplicador)
            : bcmul((string) $multiplicando, (string) $multiplicador, self::CASAS_DECIMAIS_DEZESSETE);

        return self::arredondar($valor, $casas_decimais);
    }

    /**
     * Realiza uma divisão, arredondando o resultado.
     * @param string|int $dividendo valor a ser dividido.
     * @param string|int $divisor valor pelo qual sera dividido.
     * @param int $casas_decimais Numero de casas decimais após arredondamento (Padrão: 2). Para resultado sem casas decimais usar CASAS_DECIMAIS_SEM.
     * @return string Retorna o resultado arredondado como string. Padrao: 2 casas decimais.
     * @throws InvalidArgumentException Caso $valor1 ou $valor2 não forem numericos.
     * @throws InvalidArgumentException Caso $valor2 seja 0.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 17.
     */
    public static function dividir($dividendo, $divisor, int $casas_decimais = self::CASAS_DECIMAIS_DUAS): string
    {
        self::validarNumericos($dividendo, 'dividendo');
        self::validarNumericos($divisor, 'divisor');
        self::validarDivisaoPorZero($divisor);
        self::validarIntervaloEscala($casas_decimais);

        $valor = self::saoInteiros($dividendo, $divisor)
            ? (string)($dividendo / $divisor)
            : bcdiv((string) $dividendo, (string) $divisor, self::CASAS_DECIMAIS_DEZESSETE);

        return self::arredondar($valor, $casas_decimais);
    }

    /**
     * Verifica a porcentagem que o valor a esquerda representa do valor a direita, arredondando o resultado.
     * @param string|int $parte valor parte.
     * @param string|int $valor2 valor total.
     * @param int $casas_decimais Numero de casas decimais após arredondamento (Padrão: 2). Para resultado sem casas decimais usar CASAS_DECIMAIS_SEM.
     * @return string Retorna o resultado arredondado como string. Padrao: 2 casas decimais.
     * @throws InvalidArgumentException Caso $valor1 ou $valor2 não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 17.
     */
    public static function percentual($parte, $total, int $casas_decimais = self::CASAS_DECIMAIS_DUAS): string
    {
        self::validarNumericos($parte, 'parte');
        self::validarNumericos($total, 'total');
        self::validarDivisaoPorZero($total);
        self::validarIntervaloEscala($casas_decimais);

        $valor = self::saoInteiros($parte, $total)
            ? (string)($parte / $total)
            : bcdiv((string) $parte, (string) $total, self::CASAS_DECIMAIS_DEZESSETE);

        $valor = bcmul($valor, "100", self::CASAS_DECIMAIS_DEZESSETE);

        return self::arredondar($valor, $casas_decimais);
    }

    /**
     * Compara dois valores.
     * @param $valor1 valor a esquerda.
     * @param $valor2 valor a direita.
     * @param $casas_decimais Numero de casas decimais que será usada na comparação (Padrão: 17). Para resultado sem casas decimais usar CASAS_DECIMAIS_SEM.
     * @return int 0 se os valores forem iguais, 1 se $valor1 maior que $valor2, -1 caso contrário.
     * @throws InvalidArgumentException Caso $valor1 ou $valor2 não forem numericos.
     * @throws InvalidArgumentException Caso número de casas decimais menor que 0 ou maior que 17.
     */
    public static function comparar($valor1, $valor2, int $casas_decimais = self::CASAS_DECIMAIS_DEZESSETE): int
    {
        self::validarNumericos($valor1, 'valor1');
        self::validarNumericos($valor2, 'valor2');
        self::validarIntervaloEscala($casas_decimais);

        return bccomp((string) $valor1, (string) $valor2, $casas_decimais);
    }

    public static function arredondar($valor, int $casas_decimais): string
    {
        self::validarNumericos($valor, 'valor');
        self::validarIntervaloEscala($casas_decimais);
        
        return self::bcround($valor, $casas_decimais);
    }

    private static function bcround(string $valor, int $casasDecimais): string
    {
        // Escala maior para capturar mais casas após o ponto
        $escalaInterna = $casasDecimais + 20;

        // Multiplica para deslocar o dígito de arredondamento para antes da vírgula
        $multiplicado = bcmul($valor, bcpow('10', (string)($casasDecimais + 1), $escalaInterna), $escalaInterna);

        $sinal = bccomp($multiplicado, '0', $escalaInterna) < 0 ? -1 : 1;
        $absoluto = bcmul($multiplicado, (string)$sinal, $escalaInterna);

        // Remove a parte decimal e converte para string
        $numeroStr = bcmul($absoluto, '1', 0);

        if (strlen($numeroStr) < 2) {
            $numeroStr = str_pad($numeroStr, 2, '0', STR_PAD_LEFT);
        }

        $digitoN     = (int)substr($numeroStr, -2, 1); // dígito que vai permanecer
        $digitoNp1   = (int)substr($numeroStr, -1);    // dígito a ser avaliado
        $parteInt    = substr($numeroStr, 0, -1);      // remove o dígito N+1

        // Para saber se após o 5 há algum dígito diferente de zero
        $completo = bcmul($valor, bcpow('10', (string)($casasDecimais + 10), $escalaInterna), $escalaInterna);
        $strCompleto = bcmul($completo, '1', 0);
        $digitosAposNp1 = substr($strCompleto, strlen($numeroStr));

        $arredondar = false;

        if ($digitoNp1 < 5) {
            $arredondar = false; // Regra 2.1
        } elseif ($digitoNp1 > 5) {
            $arredondar = true; // Regra 2.2
        } else { // digitoNp1 == 5
            if (preg_match('/[1-9]/', $digitosAposNp1)) {
                $arredondar = true; // Regra 2.2 (há dígitos após o 5)
            } elseif ($digitoN % 2 !== 0) {
                $arredondar = true; // Regra 2.3
            }
            // Senão: Regra 2.4 (mantém se par e zeros após 5)
        }

        if ($arredondar) {
            $parteInt = bcadd($parteInt, '1');
        }

        $resultado = bcmul($parteInt, (string)$sinal);
        return bcdiv($resultado, bcpow('10', (string)$casasDecimais), $casasDecimais);
    }

    /**
     * Verifica se os valores passados como string são numéricos.
     * @param string|int $valor Recebe os valores dos parametros $valor.
     * @param string $nome Recebe o nome do parametro para ser exibido na exceção.
     * @throws InvalidArgumentException
     * @return void
     */
    private static function validarNumericos($valor, $nome): void
    {
        if (!is_numeric($valor)) {
            throw new InvalidArgumentException("Valor '{$valor}' inválido para {$nome}.", self::ERRO_NAO_NUMERICO);
        }
    }

    /**
     * Verifica se o valor a direita é 0, pois divisão por 0 é inválida.
     * @param int $valor2 Recebe o valor do parametro $valor2.
     * @throws InvalidArgumentException
     * @return void
     */
    private static function validarDivisaoPorZero($valor): void
    {
        if ($valor == 0) {
            throw new InvalidArgumentException('Divisão por 0 é inválida.', self::ERRO_DIVISAO_POR_ZERO);
        }
    }

    /**
     * Verifica se o numero de casas decimais é válido.
     * @param int $casas_decimais Recebe o valor do parametro $casas_decimais.
     * @throws InvalidArgumentException
     * @return void
     */
    private static function validarIntervaloEscala($casas_decimais): void
    {
        if ($casas_decimais < 0 || $casas_decimais > 17) {
            throw new InvalidArgumentException('Valor inválido para $casas_decimais. Use de 0 a 17.', self::ERRO_ESCALA_INVALIDA);
        }
    }

    /**
     * Verifica se ambos os parametros são inteiros.
     * @param string|int $valor1 Recebe o parametro $valor1.
     * @param string|int $valor2 Recebe o parametro $valor2.
     * @return bool
     */
    private static function saoInteiros($valor1, $valor2): bool
    {
        return is_int($valor1) && is_int($valor2);
    }
}
