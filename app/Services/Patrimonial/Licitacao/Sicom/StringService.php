<?php

namespace App\Services\Licitacao\Sicom;

class StringService
{
    /**
     * Remove caracteres especiais de uma string.
     *
     * @param string $sString
     * @return string
     */
    public function removeCaracteres(string $sString): string
    {
        // Matriz de entrada
        $what = [
            'ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û',
            'Ä', 'Ã', 'À', 'Á', 'Â', 'Ê', 'Ë', 'È', 'É', 'Ï', 'Ì', 'Í', 'Ö', 'Õ', 'Ò', 'Ó', 'Ô', 'Ü', 'Ù', 'Ú', 'Û',
            'ñ', 'Ñ', 'ç', 'Ç', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', '°', "°", chr(13), chr(10), "'"
        ];

        // Matriz de saída
        $by = [
            'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u',
            'A', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U',
            'n', 'N', 'c', 'C', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '
        ];

        return iconv('UTF-8', 'ISO-8859-1//IGNORE', str_replace($what, $by, $sString));
    }
}
