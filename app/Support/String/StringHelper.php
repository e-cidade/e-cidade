<?php declare(strict_types=1);

namespace App\Support\String;

use DateTime;

class StringHelper
{
    public static function removeAccent(string $string): string
    {
        $mapa = [
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c', 'ñ' => 'n', 'ý' => 'y', 'ÿ' => 'y',
            'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ç' => 'C', 'Ñ' => 'N', 'Ý' => 'Y'
        ];
    
        // Apenas remove os acentos, mantendo todo o restante inalterado
        return str_replace(array_keys($mapa), array_values($mapa), $string);
    }

    public static function barCodeAmountFormart(float $amount): string
    {
        return str_pad(number_format($amount, 2, "", ""), 11, "0", STR_PAD_LEFT);
    }

    /**
     * Codifica o objeto passado recursivamente(*se necessário)
     *
     * @param mixed $entrada
     */
    public static function utf8_encode_all($entrada)
    {

        return self::formatStringRecursive($entrada, function ($string) {

            if (!self::isUTF8($string)) {
                $string = utf8_encode($string);
            }
            return $string;
        });
    }

    public static function utf8_decode_all($entrada)
    {

        return self::formatStringRecursive($entrada, function ($string) {

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

    public static function formatStringRecursive($entrada, \Closure $callback)
    {

        switch (getType($entrada)) {

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
                    if ($entrada->{$chave} instanceof DateTime) {
                        $entrada->{$chave} = $entrada->{$chave}->format('Y-m-d H:i');
                    }
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

    public static function camelCaseToNormal($str)
    {
        $result = preg_replace('/([a-z])([A-Z])/', '$1 $2', $str);
        return ucwords($result);
    }

    public static function convertToUtf8($data, $encodingfrom = 'ISO-8859-1', $encodingto = 'UTF-8') {
        if (is_array($data)) {
            return array_map(function($item) use ($encodingfrom, $encodingto) {
                return self::convertToUtf8($item, $encodingfrom, $encodingto);
            }, $data);
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = self::convertToUtf8($value, $encodingfrom, $encodingto);
            }
            return $data;
        } elseif (is_string($data)) {
            // Verificar se a string j est na codificao desejada
            $currentEncoding = mb_detect_encoding($data, [$encodingfrom, $encodingto], true);

            // S converter se a string estiver na codificao de origem esperada
            if ($currentEncoding === $encodingfrom) {
                return mb_convert_encoding($data, $encodingto, $encodingfrom);
            }
        }

        return $data;
    }

    public static function StringReplaceSicom($string){
        $string = preg_replace(
            array(
                "/(á|à|ã|â|ä|å|æ)/",
                "/(Á|À|Ã|Â|Ä|Å|Æ)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö|Ø)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/",
                "/(Ñ)/",
                "/(ç)/",
                "/(Ç)/",
                "/(ý|ÿ)/",
                "/(Ý)/"),
            explode(" ","a A e E i I o O u U n N c C y Y"),
            $string
        );
        
        $string = preg_replace('/[^A-Za-z0-9 ?|_;{}\[\]]/', '', $string);
        $string = preg_replace("/[?|?_??]/u", "-", $string);
        $string = preg_replace("/[;]/u", ".", $string);
        $string = preg_replace("/[\[<{|]/u", "(", $string);
        $string = preg_replace("/[\]>}]/u", ")", $string);
        $string = preg_replace("/[+$&]/u", "", $string);
        return $string = preg_replace('/\s{2,}/', ' ', $string);
  }
}
