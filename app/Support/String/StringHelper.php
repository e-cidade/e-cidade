<?php declare(strict_types=1);

namespace App\Support\String;

class StringHelper
{
    public static function removeAccent(string $string): string
    {
        return preg_replace(
            "[^a-zA-Z0-9]",
            "",
            strtr($string, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ", "aaaaeeiooouucAAAAEEIOOOUUC")
        );
    }

    public static function barCodeAmountFormart(float $amount): string
    {
        return str_pad(number_format($amount, 2, "", ""), 11, "0", STR_PAD_LEFT);
    }

    public static function utf8_decode_all($entrada) {

        return \DBString::formatStringRecursive($entrada, function($string) {

            if (self::isUTF8($string)) {
                $string = utf8_decode($string);
            }
            return $string;
        });
    }

    public static function isUTF8($sString): bool
    {
        if (mb_detect_encoding($sString . 'x', 'UTF-8, ISO-8859-1') == 'UTF-8') {
            return true;
        }
        return false;
    }

    public static function formatStringRecursive($entrada, \Closure $callback) {

        switch(getType($entrada)) {

            case "boolean":
            case "integer":
            case "double":
                return $entrada;
                break;

            case "string":
                $entrada = $callback($entrada);
                break;
            case "array":

                foreach ($entrada as $chave => $valor) {
                    $entrada[$chave] = self::formatStringRecursive($valor, $callback);
                }
                break;

            case "object":

                foreach ($entrada as $chave => $valor) {
                    $entrada->{$chave} = self::formatStringRecursive($valor, $callback);
                }
                break;

            case "NULL":
                return null;
            case "resource":
            case "unknown type":
                return "";
                break;
        }
        return $entrada;
    }

    public static function camelCaseToNormal($str) {
        $result = preg_replace('/([a-z])([A-Z])/', '$1 $2', $str);
        return ucwords($result);
    }
}
